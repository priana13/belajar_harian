<?php

namespace App\Http\Livewire\Homepage;

use DateTime;
use Carbon\Carbon; 
use App\Models\Soal;
use App\Models\Belajar;
use Livewire\Component;
use App\Models\Angkatan;
use App\Models\JadwalUjian;

use App\Models\AngkatanUser;
use App\Models\AbsensiKegiatan;
use App\Models\JadwalRoadmap;
use App\Models\Materi;
use App\Models\Roadmap;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;

class HomeNew extends Component
{

    public $status_absen = null;

    private function getAngkatan()
    {
        return Angkatan::pendaftaran()->where('is_umum', true)->get();
    }

    private function getTrialData()
    {
        $materi_trial = Materi::find(config('app.materi_trial_id')) ?? Materi::whereHas('soal')->first();
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

    private function getUserData()
    {
        $user = auth()->user();
        $group_user = $user->groups->pluck('id')->toArray();
        $hari_ini = Carbon::today();

        $jadwal_roadmap_group = JadwalRoadmap::whereIn('group_id', $group_user)
            ->whereMonth('tanggal_mulai', $hari_ini->month)
            ->whereYear('tanggal_mulai', $hari_ini->year)
            ->first();

        $mulai_belajar = JadwalRoadmap::whereIn('group_id', $group_user)->first();

        $jadwal_khusus = null;
        $materi_khusus = null;
        $ujian_harian_khusus = null;

        if ($jadwal_roadmap_group) {
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

        $jadwal_roadmap = JadwalRoadmap::where('gelombang_id', $user->gelombang_id)
            ->whereMonth('tanggal_mulai', $hari_ini->month)
            ->whereYear('tanggal_mulai', $hari_ini->year)
            ->first();

        $jadwal = null;
        $materi = null;
        $ujian_harian = null;
        $soal_harian = 0;

        if ($jadwal_roadmap) {
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

                $soal_harian = ($ujian_harian) ? Soal::where('materi_id', $materi->id)->where('jenis_ujian_id', 1)->where('urutan', $ujian_harian->urutan)->count() : 0;
            }
        }

        if ($jadwal) {
            $this->status_absen = AbsensiKegiatan::where('user_id', auth()->id())->where('materi_detail_id', $jadwal->materi_detail->id)->first();
        }

        return compact('jadwal', 'materi', 'ujian_harian', 'soal_harian', 'jadwal_khusus', 'materi_khusus', 'ujian_harian_khusus', 'mulai_belajar', 'jadwal_roadmap');
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

    private function getPengumuman()
    {
        return Setting::getValue('pengumuman');
    }

    public function render()
    {
        $angkatan = $this->getAngkatan();

        $jadwal = null;
        $materi = null;
        $ujian_harian = null;
        $soal_harian = 0;
        $jadwal_roadmap = null;
        $pengumuman = null;
        $jadwal_ujian = [];
        $jadwal_khusus = null;
        $materi_khusus = null;
        $ujian_harian_khusus = null;
        $mulai_belajar = null;

        if (Auth::check()) {
            if (request()->trial) {
                $trialData = $this->getTrialData();               ;
                extract($trialData);
            } else {
                $userData = $this->getUserData();
                extract($userData);
                $jadwal_ujian = $this->getJadwalUjian($jadwal_roadmap);
            }
        }

        $pengumuman = $this->getPengumuman();

        return view('livewire.homepage.home-new', compact(
            'materi',
            'angkatan',
            'jadwal_ujian',
            'ujian_harian',
            'soal_harian',
            'pengumuman',
            'jadwal',
            'jadwal_khusus',
            'materi_khusus',
            'ujian_harian_khusus',
            'mulai_belajar'
        ))->extends('layouts.app')->section('content');
    }

    public function login(){

        return redirect()->route('login');
    }

    public function register(){

        return redirect()->route('register');
    }

    public function mendaftar(Angkatan $angkatan){
        
        $kelas = $angkatan->kelas->first();

        AngkatanUser::create([
            "kode_angkatan" => $angkatan->kode_angkatan . auth()->user()->id,
            "user_id" => auth()->user()->id,
            "angkatan_id" => $angkatan->id,
            "kelas_id" => $kelas->id
        ]);

    }

}
