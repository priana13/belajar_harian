<?php

namespace App\Filament\Resources\RoadmapResource\Pages;

use App\Filament\Resources\RoadmapResource;
use App\Models\Roadmap;
use Filament\Resources\Pages\Page;

class BuatJadwalRoadmap extends Page
{
    protected static string $resource = RoadmapResource::class;

    protected static string $view = 'filament.resources.roadmap-resource.pages.buat-jadwal-roadmap';

    public $gelombang;

    public $bulan_tahun;

    public $record;


    public function mount($record): void
    {      
        $this->record = Roadmap::find($record);
     
    }

    public function getViewData(): array
    {  


        $tanggal_awal = $this->getSeninAwalBulan("2025-10");      


        return [
            'record' => $this->record,
            'list_gelombang' => \App\Models\Gelombang::all(),
        ];
    }

    public function buatJadwal()
    {
        // Logic untuk membuat jadwal roadmap

        $list_materi = $this->record->materi; // Mengambil materi dari roadmap


        if($this->gelombang == 'semua'){

            $list_gelombang = \App\Models\Gelombang::all();

            foreach ($list_gelombang as $gelombang) {

                $this->bulan_tahun = date('Y-m-d'); // reset bulan tahun ke tanggal sekarang

                foreach($list_materi as $materi){

                    $cek = \App\Models\JadwalRoadmap::where('gelombang_id', $gelombang->id)->where('materi_id', $materi->id)->where('roadmap_id', $this->record->id)->first();

                    if(!$cek){

                        \App\Models\JadwalRoadmap::create([
                            'gelombang_id' => $gelombang->id,
                            'roadmap_id' => $this->record->id,
                            'materi_id' => $materi->id,
                            'judul' => $materi->nama_materi,                      
                            'bulan_tahun' => $this->bulan_tahun,
                            'tanggal_ujian' => date('Y-m-t', strtotime($this->bulan_tahun)),
                            'is_aktif' => true,
                        ]);

                    }

                    // bulan di tambah 1 bulan
                    $this->bulan_tahun = date('Y-m-d', strtotime($this->bulan_tahun . ' +1 month'));

                }               

                

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


}
