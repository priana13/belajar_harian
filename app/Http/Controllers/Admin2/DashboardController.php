<?php

namespace App\Http\Controllers\Admin2;

use App\Http\Controllers\Controller;
use App\Models\Belajar;
use App\Models\JadwalUjian;
use App\Models\Materi;
use App\Models\Roadmap;
use App\Models\User;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $participants = fn () => User::whereHas('jenis_user', fn ($q) => $q->where('nama_jenis', 'Peserta'));
        $months = collect(range(5, 0))->map(function ($offset) use ($participants) {
            $date = now()->startOfMonth()->subMonths($offset);

            return ['label' => $date->translatedFormat('M Y'), 'value' => $participants()->whereBetween('created_at', [$date, $date->copy()->endOfMonth()])->count()];
        });
        $statuses = $participants()->selectRaw('kategori, count(*) as total')->groupBy('kategori')->get()->map(fn ($row) => ['label' => $row->kategori ?: 'Belum dikategorikan', 'value' => $row->total]);

        return Inertia::render('Admin2/Dashboard', [
            'stats' => [
                ['label' => 'Total Peserta', 'value' => $participants()->count(), 'url' => '/admin2/peserta', 'icon' => 'users'],
                ['label' => 'Materi Aktif', 'value' => Materi::where('is_active', true)->count(), 'url' => '/admin2/materi', 'icon' => 'book'],
                ['label' => 'Roadmap Belajar', 'value' => Roadmap::count(), 'url' => '/admin2/roadmap', 'icon' => 'map'],
                ['label' => 'Jadwal Hari Ini', 'value' => Belajar::whereDate('tanggal', today())->count(), 'url' => '/admin2/jadwal-belajar', 'icon' => 'calendar'],
            ],
            'months' => $months, 'statuses' => $statuses,
            'recent' => $participants()->latest()->limit(5)->get(['id', 'name', 'email', 'kategori', 'created_at']),
            'upcoming' => JadwalUjian::with('angkatan:id,kode_angkatan')->where('tanggal', '>=', today())->orderBy('tanggal')->limit(5)->get(['id', 'type', 'tanggal', 'angkatan_id']),
        ]);
    }
}
