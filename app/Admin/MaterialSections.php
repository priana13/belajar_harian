<?php

namespace App\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MaterialSections
{
    public const PARENTS = [
        'pertemuan' => ['resource' => 'materi', 'field' => 'materi_id', 'label' => 'nama_materi'],
        'soal' => ['resource' => 'materi', 'field' => 'materi_id', 'label' => 'nama_materi'],
        'gambar-materi' => ['resource' => 'pertemuan', 'field' => 'materi_detail_id', 'label' => 'judul'],
    ];

    public static function parent(string $resource, Request $request, ?Model $record): ?array
    {
        $config = self::PARENTS[$resource] ?? null;
        if (! $config) {
            return null;
        }
        $id = $record?->getAttribute($config['field']) ?? $request->input('_parent_id', $request->query($config['field']));
        if (! $id) {
            return null;
        }
        abort_unless(is_scalar($id) && ctype_digit((string) $id), 404);
        $model = Resources::model(Resources::get($config['resource'])['model'])->findOrFail($id);
        $context = [...$config, 'id' => $model->id, 'title' => $model->{$config['label']}, 'url' => Resources::url($config['resource']).'/'.$model->id.'/edit?tab='.$resource];
        if ($config['resource'] === 'pertemuan') {
            $context['ancestor'] = ['title' => $model->materi?->nama_materi, 'url' => '/admin2/materi/'.$model->materi_id.'/edit?tab=pertemuan'];
        }

        return $context;
    }

    public static function children(string $resource, ?Model $record, Request $request): array
    {
        if (! $record) {
            return [];
        }
        $sections = [];
        foreach (self::PARENTS as $key => $parent) {
            if ($parent['resource'] !== $resource) {
                continue;
            }
            $definition = Resources::get($key);
            $query = Resources::model($definition['model'])->where($parent['field'], $record->id);
            $search = $request->query($key.'_search');
            if (is_string($search) && $search !== '' && $key !== 'gambar-materi') {
                $query->where('judul', 'like', '%'.mb_substr($search, 0, 100).'%');
            }
            $rows = $query->orderBy($key === 'pertemuan' ? 'pertemuan' : ($key === 'soal' ? 'nomor' : 'id'))
                ->paginate(10, ['*'], $key.'_page')->withQueryString()->appends(['tab' => $key]);
            $columns = array_values(array_diff($definition['columns'], [$parent['field']]));
            $labels = [];
            foreach ($definition['fields'] as $field) {
                if ($field['type'] === 'reference' && in_array($field['name'], $columns, true)) {
                    $labels[$field['name']] = Resources::model($field['model'])->whereIn('id', $rows->getCollection()->pluck($field['name'])->filter())->pluck($field['labelColumn'], 'id');
                }
            }
            $rows->through(function ($row) use ($definition, $key) {
                $values = $row->only(['id', ...array_column($definition['fields'], 'name')]);
                if ($key === 'gambar-materi') {
                    $values['image_url'] = Storage::disk('public')->url($row->image);
                }

                return $values;
            });
            $sections[] = ['key' => $key, 'title' => $definition['title'], 'columns' => $columns, 'fields' => $definition['fields'], 'records' => $rows, 'labels' => $labels,
                'search' => is_string($search) ? $search : '', 'createUrl' => Resources::url($key).'/create?'.$parent['field'].'='.$record->id];
        }

        return $sections;
    }
}
