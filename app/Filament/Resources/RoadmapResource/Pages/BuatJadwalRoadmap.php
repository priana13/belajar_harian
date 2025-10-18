<?php

namespace App\Filament\Resources\RoadmapResource\Pages;

use App\Models\User;
use App\Models\Kelas;
use App\Models\Materi;
use App\Models\Belajar;
use App\Models\Roadmap;
use App\Models\Angkatan;
use App\Models\Gelombang;
use App\Models\JadwalUjian;
use App\Models\JadwalRoadmap;
use App\Models\JadwalUjianSoal;
use Filament\Resources\Pages\Page;
use Filament\Notifications\Notification;
use App\Filament\Resources\RoadmapResource;
use Livewire\WithPagination;

class BuatJadwalRoadmap extends Page
{
    use WithPagination;
    
    protected static string $resource = RoadmapResource::class;

    protected static string $view = 'filament.resources.roadmap-resource.pages.buat-jadwal-roadmap';

    public $gelombang;

    public $tanggal_mulai;

    public $record;

    public $filter_gelombang;

    public $filter_materi;


    public function mount($record): void
    {      
        $this->record = Roadmap::find($record);
     
    }

    public function getViewData(): array
    {  

        $tanggal_awal = $this->getSeninAwalBulan("2025-10");  

        $jadwal_roadmap = $this->record->jadwalRoadmaps()->withCount('jadwal_belajar')->orderBy('tanggal_mulai');

        if($this->filter_gelombang){

            $jadwal_roadmap = $jadwal_roadmap->where('gelombang_id', $this->filter_gelombang);
            
        }

        if($this->filter_materi){

            $jadwal_roadmap = $jadwal_roadmap->where('materi_id', $this->filter_materi);
            
        }


        return [
            'record' => $this->record,
            'list_gelombang' => \App\Models\Gelombang::all(),
            'jadwal_roadmap'=> $jadwal_roadmap->paginate(10)
        ];
    }

    public function buatJadwal()
    {
        // Logic untuk membuat jadwal roadmap

        $list_materi = $this->record->materi; // Mengambil materi dari roadmap


        if($this->gelombang == 'semua'){

            $list_gelombang = \App\Models\Gelombang::all();

            foreach ($list_gelombang as $gelombang) {

                $tanggal = $this->tanggal_mulai; // reset bulan tahun ke tanggal sekarang
              

                foreach($list_materi as $materi){

                    $cek = \App\Models\JadwalRoadmap::where('gelombang_id', $gelombang->id)->where('materi_id', $materi->id)->where('roadmap_id', $this->record->id)->first();

                    if(!$cek){

                        \App\Models\JadwalRoadmap::create([
                            'gelombang_id' => $gelombang->id,
                            'roadmap_id' => $this->record->id,
                            'materi_id' => $materi->id,
                            'judul' => $materi->nama_materi,                      
                            'tanggal_mulai' => $this->getSeninAwalBulan($tanggal),
                            'tanggal_ujian' => date('Y-m-t', strtotime($tanggal)),
                            'is_aktif' => true,
                        ]);

                    }

                    // bulan di tambah 1 bulan
                    $tanggal = date('Y-m-d', strtotime($tanggal . ' +1 month'));

                }               

                

            }

        }else{

            $gelombang = \App\Models\Gelombang::find($this->gelombang);

            $tanggal = $this->tanggal_mulai; // reset bulan tahun ke tanggal sekarang
              

                foreach($list_materi as $materi){

                    $cek = \App\Models\JadwalRoadmap::where('gelombang_id', $gelombang->id)->where('materi_id', $materi->id)->where('roadmap_id', $this->record->id)->first();

                    if(!$cek){

                        \App\Models\JadwalRoadmap::create([
                            'gelombang_id' => $gelombang->id,
                            'roadmap_id' => $this->record->id,
                            'materi_id' => $materi->id,
                            'judul' => $materi->nama_materi,                      
                            'tanggal_mulai' => $this->getSeninAwalBulan($tanggal),
                            'tanggal_ujian' => date('Y-m-t', strtotime($tanggal)),
                            'is_aktif' => true,
                        ]);

                    }

                    // bulan di tambah 1 bulan
                    $tanggal = date('Y-m-d', strtotime($tanggal . ' +1 month'));

                }     




        }


       
    }

    /**
     * Mendapatkan tanggal Senin pertama dari bulan yang diberikan
     * Format input: YYYY-MM (contoh: 2025-10)
     * Format output: YYYY-MM-DD (contoh: 2025-10-07)
     */
    public function getSeninAwalBulan($bulan): string
    {
        // Tambahkan -01 untuk mendapatkan tanggal pertama bulan
        $tanggal_awal_bulan = $bulan . '-01';
        
        // Dapatkan hari dari tanggal 1
        $dayOfWeek = date('N', strtotime($tanggal_awal_bulan)); // 1 = Senin, 7 = Minggu
        
        if ($dayOfWeek == 1) {
            // Jika tanggal 1 adalah Senin, gunakan tanggal tersebut
            return $tanggal_awal_bulan;
        } else {
            // Jika bukan Senin, maju ke Senin berikutnya
            $daysToAdd = 8 - $dayOfWeek; // 8 dikurangi hari ini untuk dapat Senin depan
            return date('Y-m-d', strtotime("+{$daysToAdd} days", strtotime($tanggal_awal_bulan)));
        }
    }

    public function buatJadwalHarian($jadwal_id): void
    {
        $jadwal = \App\Models\JadwalRoadmap::find($jadwal_id);
     
        if($jadwal){          

            $this->buatJadwalPeserta($jadwal , $jadwal->gelombang_id , $jadwal->materi_id);
           

        }

    }


    public function buatJadwalPeserta(JadwalRoadmap $jadwal, int $gelombang_id , int $materi_id): void 
    {

        // $angkatan = Angkatan::first(); // sementara

        // Kelas::create([
        //     "angkatan_id" => $angkatan->id,
        //     "nama_kelas" => $angkatan->kode_angkatan . '-' . "Kelas 1"           

        // ]);   
        
        // Buat Jadwal Ujian Harian
        $materi = Materi::find($materi_id);  


        $hari_ke = 1;
        $tanggal = $jadwal->tanggal_mulai;
        $materi_per_pekan = $materi->materi_per_pekan;

        // Pastikan tanggal mulai adalah hari Senin
        $dayOfWeek = date('N', strtotime($tanggal)); // 1 = Senin, 7 = Minggu
        if ($dayOfWeek != 1) {
            // Jika bukan Senin, mundur ke Senin sebelumnya
            $daysToSubtract = $dayOfWeek - 1;
            $tanggal = date('Y-m-d', strtotime('-' . $daysToSubtract . ' days', strtotime($tanggal)));
        }

    
        foreach ($materi->pertemuan()->orderBy('pertemuan')->get() as $pertemuan) {

            $jadwal_ujian = JadwalUjian::create([
                "tanggal" => $tanggal,
                "type" => "Harian",
                // "angkatan_id" => $angkatan->id,
                "urutan" => $hari_ke,
                'roadmap_id' => $jadwal->roadmap_id,
                'gelombang_id' => $jadwal->gelombang_id,              
            ]);

            // tambahkan jadwal belajar
            Belajar::create([
                "tanggal" => $tanggal,
                "materi_detail_id" => $pertemuan->id,
                "user_id" => auth()->user()->id,
                // "angkatan_id" => $angkatan->id,
                "code" => uniqid(),
                'roadmap_id' => $jadwal->roadmap_id,
                'gelombang_id' => $jadwal->gelombang_id,
                'jadwal_roadmap_id' => $jadwal->id,
            ]);

            // tambahkan soal ke jadwal ujian
            $list_soal = $materi->soal()->where('urutan', $hari_ke)->harian()->get();

            foreach ($list_soal as $soal) {
                JadwalUjianSoal::create([
                    "jadwal_ujian_id" => $jadwal_ujian->id,
                    "soal_id" => $soal->id
                ]);
            }           
            
            // Tentukan tanggal berikutnya berdasarkan pola materi per pekan
            $posisi_dalam_pekan = (($hari_ke - 1) % $materi_per_pekan) + 1;
            
            if ($materi_per_pekan == 2) {
                // Pola: Senin dan Kamis
                if ($posisi_dalam_pekan == 1) {
                    // Dari Senin ke Kamis (tambah 3 hari)
                    $penambah_hari = 3;
                } else {
                    // Dari Kamis ke Senin minggu berikutnya (tambah 4 hari)
                    $penambah_hari = 4;
                }
            } elseif ($materi_per_pekan == 3) {
                // Pola: Senin, Rabu, Jumat
                if ($posisi_dalam_pekan == 1) {
                    // Dari Senin ke Rabu (tambah 2 hari)
                    $penambah_hari = 2;
                } elseif ($posisi_dalam_pekan == 2) {
                    // Dari Rabu ke Jumat (tambah 2 hari)
                    $penambah_hari = 2;
                } else {
                    // Dari Jumat ke Senin minggu berikutnya (tambah 3 hari)
                    $penambah_hari = 3;
                }
            } else {
                // Fallback untuk kasus lain (setiap hari)
                $penambah_hari = 1;
            }

            $tanggal = date('Y-m-d', strtotime('+' . $penambah_hari . ' day', strtotime($tanggal)));
            $hari_ke++;
        }

        // Buat Jadwal Ujian Pekanan
        // ujian pekanan mulai dari hari ke-6,7 ; 

        $ujian_pekanan = [];
        $tanggal_ujian = $tanggal;

        for ($i=1; $i <= 4; $i++) { 

            if($i == 1){

                $penambah = 6;

            }else{
                $penambah = 7;
            }
           
            $tanggal_ujian = date('Y-m-d', strtotime('+'.$penambah.' day' , strtotime($tanggal_ujian)));

            $jadwal_ujian = JadwalUjian::create([
                "tanggal" => $tanggal_ujian,
                "type" => "Pekanan",
                // "angkatan_id" => $angkatan->id,
                "urutan" => $i,
                'roadmap_id' => $jadwal->roadmap_id,
                'gelombang_id' => $jadwal->gelombang_id,              
           ]);

           //tambahkan soal ke jadwal ujian

                      
           $list_soal_pekanan = $materi->soal()->where('urutan', $jadwal_ujian->urutan)->pekanan()->get();

           foreach ($list_soal_pekanan as $soal) {

                JadwalUjianSoal::create([

                    "jadwal_ujian_id" => $jadwal_ujian->id,
                    "soal_id" => $soal->id
                ]);

           }


        }


            // Buat jadwal ujian Akhir
            $jadwal_ujian_akhir = JadwalUjian::create([
                "tanggal" => $jadwal->tanggal_ujian,
                "type" => "Akhir",
                // "angkatan_id" => $angkatan->id,
                "urutan" => 1,
                'roadmap_id' => $jadwal->roadmap_id,
                'gelombang_id' => $jadwal->gelombang_id,         
           ]);

           $soal_ujian_akhir = $materi->soal()->akhir()->get();

           foreach ($soal_ujian_akhir as $soal) {

                JadwalUjianSoal::create([

                    "jadwal_ujian_id" => $jadwal_ujian_akhir->id,
                    "soal_id" => $soal->id
                ]);

                // Daftarkan Para Peserta

                // $list_peserta = User::where('gelombang_id', $gelombang_id)->get();      

                // // daftarkan peserta ke angkatan

                // foreach ($list_peserta as $peserta) {

                //     Angkatan::daftarkanPeserta($angkatan , $peserta->id);
                // }

            }


        Notification::make()
                ->title( 'Jadwal Harian untuk Materi ' . $materi->nama_materi . ' Berhasil Dibuat' )
                ->success()
                ->send();

       
    }

    public function hapusJadwal($jadwal_id): void
    {
        $jadwal = \App\Models\JadwalRoadmap::find($jadwal_id);
     
        if($jadwal){

            // hapus jadwal belajar
            $list_jadwal_belajar = Belajar::where('jadwal_roadmap_id', $jadwal->id)->get();

            foreach ($list_jadwal_belajar as $belajar) {
                $belajar->delete();
            }

            // hapus jadwal ujian
            $list_jadwal_ujian = JadwalUjian::where('roadmap_id', $jadwal->roadmap_id)->where('gelombang_id', $jadwal->gelombang_id)->get();

            foreach ($list_jadwal_ujian as $ujian) {

                // hapus soal ujian
                $list_soal_ujian = JadwalUjianSoal::where('jadwal_ujian_id', $ujian->id)->get();

                foreach ($list_soal_ujian as $soal) {
                    $soal->delete();
                }

                $ujian->delete();
            }

            // hapus jadwal roadmap
            $jadwal->delete();

            Notification::make()
                ->title( 'Jadwal Roadmap Berhasil Dihapus' )
                ->success()
                ->send();

        }

    }


}
