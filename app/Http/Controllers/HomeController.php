<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Soal;
use App\Models\Banner;
use App\Models\Belajar;
use App\Models\Angkatan;
use App\Models\Setting;
use Inertia\Inertia;
use App\Models\JadwalUjian;
use App\Models\AngkatanUser;
use App\Models\AbsensiKegiatan;
use App\Models\JadwalRoadmap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $jadwal = null;
        $materi = null;
        $ujian_harian = null;
        $soal_harian = 0;
        $jadwal_roadmap = null;
        $jadwal_ujian = [];
        $jadwal_khusus = null;
        $materi_khusus = null;
        $ujian_harian_khusus = null;
        $mulai_belajar = null;
        $jadwal_ujian_khusus = [];
        $user_groups = [];
        $status_absen = null;
        $jadwal_berikutnya = [];
        $jadwal_ujian_berikutnya = [];

        if (Auth::check()) {
            if ($request->trial) {
                $trialData = $this->getTrialData();
                extract($trialData);
            } else {
                $userData = $this->getUserData();
                extract($userData);
                $jadwal_ujian = $this->getJadwalUjian($jadwal_roadmap);
                $jadwal_ujian_khusus = $this->getJadwalUjianKhusus($roadmap_khusus ?? null);

                $user_groups = auth()->user()->groups->map(fn ($group) => [
                    'id' => $group->id,
                    'nama_group' => $group->nama_group,
                ])->all();

                $berikutnya = $this->getMateriBerikutnya();
                $jadwal_berikutnya = $berikutnya['jadwal_berikutnya'];
                $jadwal_ujian_berikutnya = $berikutnya['jadwal_ujian'];
            }
        }

        return Inertia::render('Home', [
            'banners' => Banner::where('status', true)->get()->map(fn ($banner) => [
                'id' => $banner->id,
                'image_url' => asset('storage/' . $banner->image),
                'url' => $banner->url,
            ]),
            'angkatan' => $this->getAngkatan()->map(fn ($angkatan) => [
                'id' => $angkatan->id,
                'kode_angkatan' => $angkatan->kode_angkatan,
            ]),
            'angkatanCount' => Angkatan::count(),
            'pengumuman' => $this->presentPengumuman($this->getPengumuman()),
            'trial' => $request->trial,
            'jadwal' => $this->presentJadwal($jadwal),
            'jadwalKhusus' => $this->presentJadwal($jadwal_khusus),
            'ujianHarian' => $this->presentUjianHarian($ujian_harian),
            'soalHarian' => $soal_harian,
            'ujianHarianKhusus' => $this->presentUjianHarian($ujian_harian_khusus),
            'mulaiBelajar' => $mulai_belajar ? ['tanggal_mulai' => $mulai_belajar->tanggal_mulai] : null,
            'userGroups' => $user_groups,
            'jadwalUjian' => $this->presentJadwalUjianList($jadwal_ujian),
            'jadwalUjianKhusus' => $this->presentJadwalUjianList($jadwal_ujian_khusus),
            'jadwalBerikutnya' => $this->presentJadwalBerikutnya($jadwal_berikutnya),
            'jadwalUjianBerikutnya' => $this->presentJadwalUjianBerikutnya($jadwal_ujian_berikutnya),
            'statusAbsen' => $status_absen,
        ]);
    }

    public function mendaftar(Angkatan $angkatan)
    {
        $kelas = $angkatan->kelas->first();

        AngkatanUser::create([
            'kode_angkatan' => $angkatan->kode_angkatan . auth()->user()->id,
            'user_id' => auth()->user()->id,
            'angkatan_id' => $angkatan->id,
            'kelas_id' => $kelas->id,
        ]);

        return redirect()->route('home')->with('success', 'Berhasil mendaftar!');
    }

    private function getAngkatan()
    {
        return Angkatan::pendaftaran()->where('is_umum', true)->get();
    }

    private function getTrialData()
    {
        $materi_trial = \App\Models\Materi::find(config('app.materi_trial_id')) ?? \App\Models\Materi::whereHas('soal')->first();
        $materi_trial_detail = $materi_trial->materi_detail->pluck('id');
        $jadwal = Belajar::whereHas('gelombang')->whereIn('materi_detail_id', $materi_trial_detail)->latest()->first();

        $materi = null;
        $ujian_harian = null;
        $soal_harian = 0;

        if ($jadwal) {
            $materi = $jadwal->materi_detail->materi;
            $ujian_harian = JadwalUjian::where('type', 'Harian')
                ->where('gelombang_id', auth()->user()->gelombang_id)
                ->where('urutan', $jadwal->materi_detail->pertemuan)
                ->first();

            $soal_harian = ($ujian_harian) ? Soal::where('materi_id', $jadwal->materi_detail->materi_id)->where('jenis_ujian_id', 1)->where('urutan', $ujian_harian->urutan)->count() : 0;
        }

        return compact('jadwal', 'materi', 'ujian_harian', 'soal_harian');
    }

    private function getStartDate()
    {
        $user = auth()->user();
        $group_user = $user->groups->pluck('id')->toArray();
        return JadwalRoadmap::whereIn('group_id', $group_user)->first();
    }

    private function getJadwalKhusus()
    {
        $user = auth()->user();
        $group_user = $user->groups->pluck('id')->toArray();
        $hari_ini = Carbon::today();

        $jadwal_roadmap_group = JadwalRoadmap::whereIn('group_id', $group_user)
            ->whereMonth('tanggal_mulai', $hari_ini->month)
            ->whereYear('tanggal_mulai', $hari_ini->year)
            ->first();

        $jadwal_khusus = null;
        $materi_khusus = null;
        $ujian_harian_khusus = null;
        $roadmap_khusus = null;

        if ($jadwal_roadmap_group) {
            $roadmap_khusus = $jadwal_roadmap_group->roadmap;

            $jadwal_khusus = Belajar::where('jadwal_roadmap_id', $jadwal_roadmap_group->id)
                ->where('tanggal', date('Y-m-d'))
                ->latest()->first();

            if ($jadwal_khusus) {
                $materi_khusus = $jadwal_khusus->materi_detail->materi;

                $ujian_harian_khusus = JadwalUjian::where('type', 'Harian')
                    ->where('roadmap_id', $jadwal_roadmap_group->roadmap_id)
                    ->where('urutan', $jadwal_khusus->materi_detail->pertemuan)
                    ->where('materi_id', $jadwal_roadmap_group->materi_id)
                    ->first();
            }
        }

        return compact('jadwal_khusus', 'materi_khusus', 'ujian_harian_khusus', 'roadmap_khusus');
    }

    private function getJadwalUtama()
    {
        $user = auth()->user();
        $hari_ini = Carbon::today();

        $jadwal_roadmap = JadwalRoadmap::where('gelombang_id', $user->gelombang_id)
            ->whereMonth('tanggal_mulai', $hari_ini->month)
            ->whereYear('tanggal_mulai', $hari_ini->year)
            ->first();

        $jadwal = null;
        $materi = null;
        $ujian_harian = null;
        $soal_harian = 0;
        $roadmap_standar = null;
        $status_absen = null;

        if ($jadwal_roadmap) {
            $roadmap_standar = $jadwal_roadmap->roadmap;

            $jadwal = Belajar::where('gelombang_id', $user->gelombang_id)
                ->where('roadmap_id', $jadwal_roadmap->roadmap_id)
                ->where('tanggal', date('Y-m-d'))
                ->latest()->first();

            if ($jadwal) {
                $materi = $jadwal->materi_detail->materi;

                $ujian_harian = JadwalUjian::where('type', 'Harian')
                    ->where('gelombang_id', $user->gelombang_id)
                    ->where('roadmap_id', $jadwal_roadmap->roadmap_id)
                    ->where('urutan', $jadwal->materi_detail->pertemuan)
                    ->where('materi_id', $jadwal_roadmap->materi_id)
                    ->first();

                $soal_harian = ($ujian_harian) ? Soal::where('materi_id', $materi->id)
                    ->where('jenis_ujian_id', 1)
                    ->where('urutan', $ujian_harian->urutan)
                    ->count() : 0;

                $status_absen = AbsensiKegiatan::where('user_id', auth()->id())
                    ->where('materi_detail_id', $jadwal->materi_detail->id)
                    ->first();
            }
        }

        return compact('jadwal', 'materi', 'ujian_harian', 'soal_harian', 'jadwal_roadmap', 'roadmap_standar', 'status_absen');
    }

    private function getUserData()
    {
        $mulai_belajar = $this->getStartDate();
        $khususData = $this->getJadwalKhusus();
        $jadwalUtama = $this->getJadwalUtama();

        return array_merge(
            $khususData,
            $jadwalUtama,
            compact('mulai_belajar')
        );
    }

    private function getJadwalUjian($jadwal_roadmap)
    {
        if (!$jadwal_roadmap) {
            return [];
        }

        return JadwalUjian::where('gelombang_id', auth()->user()->gelombang_id)
            ->where('roadmap_id', $jadwal_roadmap->roadmap_id)
            ->whereIn('type', ["Pekanan", "Akhir"])
            ->duaHari()
            ->get();
    }

    private function getJadwalUjianKhusus($jadwal_roadmap)
    {
        if (!$jadwal_roadmap) {
            return [];
        }

        return JadwalUjian::where('roadmap_id', $jadwal_roadmap->id)
            ->whereIn('type', ["Pekanan", "Akhir"])
            ->duaHari()
            ->get();
    }

    private function getMateriBerikutnya()
    {
        $jadwal_roadmap = JadwalRoadmap::where('gelombang_id', auth()->user()->gelombang_id)->first();

        $jadwal_berikutnya = [];
        $jadwal_ujian = [];

        if ($jadwal_roadmap) {
            $jadwal_berikutnya = Belajar::where('gelombang_id', auth()->user()->gelombang_id)
                ->where('roadmap_id', $jadwal_roadmap->roadmap_id)
                ->aktif()
                ->whereDate('tanggal', '>', now())
                ->take(1)->orderBy('id', 'asc')->get();

            $jadwal_ujian = JadwalUjian::whereIn('type', ["Pekanan", "Akhir"])
                ->where('gelombang_id', auth()->user()->gelombang_id)
                ->where('roadmap_id', $jadwal_roadmap->roadmap_id)
                ->whereDate('tanggal', '>', now())
                ->take(1)->orderBy('id', 'asc')->get();
        }

        return compact('jadwal_berikutnya', 'jadwal_ujian');
    }

    private function getPengumuman()
    {
        return Setting::getValue('pengumuman');
    }

    private function presentJadwal($jadwal)
    {
        if (!$jadwal) {
            return null;
        }

        $materiDetail = $jadwal->materi_detail;
        $materi = $materiDetail->materi;

        return [
            'id' => $jadwal->id,
            'tanggal' => $jadwal->tanggal,
            'materi_detail' => [
                'judul' => $materiDetail->judul,
                'pertemuan' => $materiDetail->pertemuan,
                'jenis_kontent' => $materiDetail->jenis_kontent,
                'video_url' => $materiDetail->video_url,
                'multimedia_url' => $materiDetail->multimedia_url,
            ],
            'materi' => [
                'id' => $materi->id,
                'nama_materi' => $materi->nama_materi,
                'kategori' => [
                    'nama_kategori' => $materi->kategori->nama_kategori,
                ],
            ],
        ];
    }

    private function presentUjianHarian($ujian)
    {
        if (!$ujian) {
            return null;
        }

        return [
            'id' => $ujian->id,
            'urutan' => $ujian->urutan,
        ];
    }

    private function presentJadwalUjianList($list)
    {
        return collect($list)->map(fn ($row) => [
            'id' => $row->id,
            'type' => $row->type,
            'urutan' => $row->urutan,
            'materi_id' => $row->materi_id,
            'materi' => ['nama_materi' => $row->materi->nama_materi],
        ])->values();
    }

    private function presentJadwalBerikutnya($list)
    {
        return collect($list)->map(fn ($row) => [
            'tanggal' => $row->tanggal,
            'materi_detail' => ['judul' => $row->materi_detail->judul],
        ])->values();
    }

    private function presentJadwalUjianBerikutnya($list)
    {
        return collect($list)->map(fn ($row) => [
            'tanggal' => $row->tanggal,
            'type' => $row->type,
            'materi' => ['nama_materi' => $row->materi->nama_materi],
        ])->values();
    }

    private function presentPengumuman($pengumuman)
    {
        return [
            'is_active' => (bool) ($pengumuman->is_active ?? false),
            'value' => $pengumuman->value ?? null,
        ];
    }
}
