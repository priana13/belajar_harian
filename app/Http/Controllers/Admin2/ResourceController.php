<?php

namespace App\Http\Controllers\Admin2;

use App\Admin\CreateAngkatan;
use App\Admin\Resources;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class ResourceController extends Controller
{
    private function query(Request $request, array $resource)
    {
        $query = Resources::model($resource['model'])->newQuery();
        $search = mb_substr(trim((string) $request->query('search', '')), 0, 100);
        $fields = collect($resource['fields']);
        if ($search !== '') {
            $query->where(function ($query) use ($fields, $search) {
                foreach ($fields->whereIn('type', ['text', 'email', 'textarea']) as $field) {
                    $query->orWhere($field['name'], 'like', '%'.$search.'%');
                }
                foreach ($fields->where('type', 'reference') as $field) {
                    $related = Resources::model($field['model']);
                    $query->orWhereIn($field['name'], $related->newQuery()->select('id')->where($field['labelColumn'], 'like', '%'.$search.'%'));
                }
            });
        }
        foreach ($fields->whereIn('type', ['select', 'reference', 'checkbox']) as $field) {
            $value = $request->query('filter_'.$field['name']);
            if (is_scalar($value) && (string) $value !== '') {
                $query->where($field['name'], $value);
            }
        }
        $sort = in_array($request->query('sort'), $resource['columns'], true) ? $request->query('sort') : 'id';

        return $query->orderBy($sort, $request->query('direction') === 'asc' ? 'asc' : 'desc')->orderBy('id');
    }

    public function index(Request $request)
    {
        $resource = explode('.', $request->route()->getName())[1];
        $definition = Resources::get($resource);
        $records = $this->query($request, $definition)->paginate(15)->withQueryString();
        $labels = [];
        foreach ($definition['fields'] as $field) {
            if ($field['type'] === 'reference') {
                $labels[$field['name']] = Resources::model($field['model'])->newQuery()
                    ->whereIn('id', $records->getCollection()->pluck($field['name'])->filter())
                    ->pluck($field['labelColumn'], 'id');
            }
        }
        // Return only catalog fields, never the model's complete attributes.
        $records->through(fn ($record) => $record->only(array_merge(['id'], array_diff(array_column($definition['fields'], 'name'), ['password']))));

        return Inertia::render('Admin2/Records', [
            'resourceKey' => $resource, 'definition' => $definition,
            'records' => $records, 'labels' => $labels,
            'filters' => $request->only(array_merge(['search', 'sort', 'direction'], array_map(fn ($f) => 'filter_'.$f['name'], $definition['fields']))),
        ]);
    }

    public function form(Request $request, ?string $record = null)
    {
        $resource = explode('.', $request->route()->getName())[1];
        $definition = Resources::get($resource);
        $model = $record ? Resources::model($definition['model'])->newQuery()->findOrFail($record) : null;
        $values = $model?->only(array_diff(array_column($definition['fields'], 'name'), ['password'])) ?? [];
        $selected = [];
        foreach ($definition['fields'] as $field) {
            if ($field['type'] === 'reference' && ! empty($values[$field['name']])) {
                $selected[$field['name']] = Resources::model($field['model'])->newQuery()->find($values[$field['name']])?->getAttribute($field['labelColumn']);
            }
        }
        $relations = [];
        foreach ($definition['relations'] ?? [] as $name => [$class, $label]) {
            $relations[$name] = $model ? $model->{$name}()->get()->map(fn ($item) => ['value' => $item->id, 'label' => $item->{$label}])->all() : [];
        }

        return Inertia::render('Admin2/Edit', [
            'resourceKey' => $resource, 'definition' => $definition,
            'record' => $model ? ['id' => $model->id, ...$values] : null,
            'selected' => $selected, 'relations' => $relations,
        ]);
    }

    public function options(Request $request, string $field)
    {
        $resource = explode('.', $request->route()->getName())[1];
        $definition = Resources::get($resource);
        $config = collect($definition['fields'])->firstWhere('name', $field);
        if (isset($definition['relations'][$field])) {
            [$model, $label] = $definition['relations'][$field];
        } else {
            abort_unless($config && $config['type'] === 'reference', 404);
            $model = $config['model'];
            $label = $config['labelColumn'];
        }
        $search = mb_substr((string) $request->query('search', ''), 0, 100);
        $query = Resources::model($model)->newQuery();
        if ($search !== '') {
            $query->where($label, 'like', '%'.$search.'%');
        }

        return $query->orderBy($label)->limit(30)->get(['id', $label])->map(fn ($item) => ['value' => $item->id, 'label' => (string) $item->{$label}]);
    }

    public function save(Request $request, ?string $record = null)
    {
        $resource = explode('.', $request->route()->getName())[1];
        $definition = Resources::get($resource);
        $model = Resources::model($definition['model']);
        if ($record) {
            $model = $model->newQuery()->findOrFail($record);
        }
        $rules = [];
        foreach ($definition['fields'] as $field) {
            $name = $field['name'];
            $required = $field['required'];
            if ($field['type'] === 'file') {
                $rules[$name] = [$required && ! $model->{$name} ? 'required' : 'nullable', 'file', 'max:51200', ($field['accept'] ?? '') === 'audio/*' ? 'mimes:mp3,wav,ogg,m4a,mpga' : 'image'];

                continue;
            }
            if ($field['type'] === 'password') {
                $rules[$name] = [$model->exists ? 'nullable' : 'required', 'string', 'min:8', 'max:72'];

                continue;
            }
            $rules[$name] = [$required ? 'required' : 'nullable'];
            $rules[$name][] = match ($field['type']) {
                'number' => 'numeric', 'checkbox' => 'boolean', 'date', 'datetime-local' => 'date',
                'email' => 'email', 'url' => 'url:http,https', 'reference' => 'integer', default => 'string',
            };
            if (in_array($field['type'], ['text', 'email', 'url', 'textarea'])) {
                $rules[$name][] = 'max:'.($field['max'] ?? ($field['type'] === 'textarea' ? 65535 : 255));
            }
            if ($field['type'] === 'number') {
                $rules[$name][] = 'min:'.($field['min'] ?? 0);
                if (isset($field['max'])) {
                    $rules[$name][] = 'max:'.$field['max'];
                }
            }
            if ($field['type'] === 'reference') {
                $rules[$name][] = Rule::exists(Resources::model($field['model'])->getTable(), 'id');
            }
            if ($field['type'] === 'select') {
                $rules[$name][] = Rule::in(array_column($field['options'], 'value'));
            }
            if ($field['unique'] ?? false) {
                $rules[$name][] = Rule::unique($model->getTable(), $name)->ignore($model->getKey());
            }
        }
        foreach ($definition['relations'] ?? [] as $name => [$class, $label]) {
            $rules['relations.'.$name] = ['sometimes', 'array'];
            $rules['relations.'.$name.'.*'] = ['integer', 'distinct', Rule::exists(Resources::model($class)->getTable(), 'id')];
        }
        if ($resource === 'angkatan') {
            $rules['akhir_pendaftaran'][] = 'after_or_equal:mulai_pendaftaran';
            $rules['tanggal_akhir'][] = 'after_or_equal:tanggal_mulai';
            $rules['tanggal_ujian'][] = 'after_or_equal:tanggal_mulai';
        }
        if ($resource === 'jadwal-roadmap') {
            $rules['tanggal_ujian'][] = 'after_or_equal:tanggal_mulai';
        }
        if ($resource === 'anggota-kelompok') {
            $rules['user_id'][] = Rule::unique('group_users', 'user_id')->where('group_id', $request->input('group_id'))->ignore($model->getKey());
        }
        if ($resource === 'peserta-angkatan') {
            $rules['kelas_id'][] = Rule::exists('kelas', 'id')->where('angkatan_id', $request->input('angkatan_id'));
            $rules['user_id'][] = Rule::unique('angkatan_users', 'user_id')->where('angkatan_id', $request->input('angkatan_id'))->ignore($model->getKey());
        }
        $data = $request->validate($rules);
        if ($resource === 'peserta' && $model->id === $request->user()->id && (int) $data['jenis_user_id'] !== (int) $model->jenis_user_id) {
            throw ValidationException::withMessages(['jenis_user_id' => 'Jenis pengguna akun yang sedang digunakan tidak dapat diubah.']);
        }
        if ($resource === 'jenis-user' && $model->nama_jenis === 'Admin' && $data['nama_jenis'] !== 'Admin') {
            throw ValidationException::withMessages(['nama_jenis' => 'Jenis Admin digunakan untuk otorisasi dan tidak dapat diganti.']);
        }
        $paths = [];
        try {
            DB::transaction(function () use ($data, $definition, $request, $model, $resource, &$paths) {
                $relations = array_replace(array_fill_keys(array_keys($definition['relations'] ?? []), []), $data['relations'] ?? []);
                unset($data['relations']);
                foreach ($definition['fields'] as $field) {
                    $name = $field['name'];
                    if ($field['type'] === 'password') {
                        if (! empty($data[$name])) {
                            $data[$name] = Hash::make($data[$name]);
                        } else {
                            unset($data[$name]);
                        }
                    }
                    if ($field['type'] === 'file') {
                        unset($data[$name]);
                        if ($request->hasFile($name)) {
                            $data[$name] = $request->file($name)->store('admin2/'.$resource, 'public');
                            $paths[] = $data[$name];
                        }
                    }
                }
                $creating = ! $model->exists;
                if ($creating && $resource === 'angkatan') {
                    $data['kode_daftar'] = (string) Str::uuid();
                }
                if ($creating && in_array($resource, ['jadwal-belajar', 'peserta-angkatan'], true)) {
                    $data['code'] = (string) Str::uuid();
                }
                $model->fill($data)->save();
                foreach ($relations as $name => $ids) {
                    $model->{$name}()->sync($ids);
                }
                if ($creating && $resource === 'angkatan') {
                    app(CreateAngkatan::class)->handle($model, $request->user()->id);
                }
            });
        } catch (\Throwable $exception) {
            Storage::disk('public')->delete($paths);
            throw $exception;
        }

        return redirect(Resources::url($resource))->with('success', $definition['title'].' berhasil disimpan.');
    }

    public function destroy(Request $request, string $record)
    {
        $resource = explode('.', $request->route()->getName())[1];
        $definition = Resources::get($resource);
        $model = Resources::model($definition['model'])->newQuery()->findOrFail($record);
        if (($resource === 'peserta' && $model->id === $request->user()->id) || ($resource === 'jenis-user' && $model->nama_jenis === 'Admin')) {
            return back()->with('error', 'Akun atau jenis Admin yang digunakan tidak dapat dihapus.');
        }
        try {
            $model->delete();
        } catch (QueryException $exception) {
            if (! str_starts_with((string) $exception->getCode(), '23')) {
                throw $exception;
            }

            return back()->with('error', 'Data masih digunakan. Hapus relasi terkait terlebih dahulu.');
        }

        return back()->with('success', 'Data berhasil dihapus.');
    }

    public function export(Request $request)
    {
        $resource = explode('.', $request->route()->getName())[1];
        $definition = Resources::get($resource);
        $query = $this->query($request, $definition);

        return response()->streamDownload(function () use ($query, $definition) {
            $stream = fopen('php://output', 'w');
            fwrite($stream, "\xEF\xBB\xBF");
            fputcsv($stream, $definition['columns']);
            foreach ($query->cursor() as $row) {
                fputcsv($stream, array_map(function ($column) use ($row) {
                    $value = (string) $row->{$column};

                    return preg_match('/^[=+@\-\t\r\n]/', $value) ? "'".$value : $value;
                }, $definition['columns']));
            }
            fclose($stream);
        }, $resource.'-'.now()->format('Y-m-d').'.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
