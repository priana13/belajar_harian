<?php

namespace App\Http\Controllers\Admin2;

use App\Http\Controllers\Controller;
use App\Models\Belajar;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReportController extends Controller
{
    public function activity(Request $request)
    {
        $filters = $request->validate(['start' => ['nullable', 'date'], 'end' => ['nullable', 'date', 'after_or_equal:start'], 'angkatan' => ['nullable', 'integer', 'exists:angkatan,id'], 'maximum' => ['nullable', 'integer', 'min:0']]);
        $start = $filters['start'] ?? now()->startOfMonth()->toDateString();
        $end = $filters['end'] ?? today()->toDateString();
        $maximum = $filters['maximum'] ?? 0;
        $scope = fn ($q) => $q->whereDate('created_at', '>=', $start)->whereDate('created_at', '<=', $end)->when($filters['angkatan'] ?? null, fn ($q, $id) => $q->where('angkatan_id', $id));
        $query = User::whereHas('jenis_user', fn ($q) => $q->where('nama_jenis', 'Peserta'))
            ->when($filters['angkatan'] ?? null, fn ($q, $id) => $q->whereHas('angkatan', fn ($q) => $q->where('angkatan.id', $id)))
            ->withCount(['ujian' => $scope])->whereHas('ujian', $scope, '<=', $maximum)->orderBy('name');
        if ($request->boolean('export')) {
            return response()->streamDownload(function () use ($query) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['Nama', 'Email', 'Telepon', 'Jumlah ujian']);
                foreach ($query->cursor() as $user) {
                    fputcsv($file, array_map(fn ($v) => preg_match('/^[=+@\-\t\r\n]/', (string) $v) ? "'".$v : $v, [$user->name, $user->email, $user->no_hp, $user->ujian_count]));
                }
                fclose($file);
            }, 'keaktifan-peserta.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
        }

        return Inertia::render('Admin2/Reports', ['kind' => 'activity', 'filters' => ['start' => $start, 'end' => $end, 'maximum' => $maximum, 'angkatan' => $filters['angkatan'] ?? ''], 'records' => $query->paginate(20, ['id', 'name', 'email', 'no_hp'])->withQueryString()]);
    }

    public function links(Request $request)
    {
        $data = $request->validate(['date' => ['nullable', 'date']]);
        $date = $data['date'] ?? today()->toDateString();
        $records = Belajar::with(['materi_detail.materi', 'gelombang'])->whereDate('tanggal', $date)->orderBy('id')->paginate(20)->withQueryString();
        $records->through(fn ($row) => ['id' => $row->id, 'title' => $row->materi_detail?->judul, 'materi' => $row->materi_detail?->materi?->nama_materi, 'gelombang' => $row->gelombang?->gel, 'url' => $row->code ? route('link_materi', $row->code) : null]);

        return Inertia::render('Admin2/Reports', ['kind' => 'links', 'filters' => ['date' => $date], 'records' => $records]);
    }
}
