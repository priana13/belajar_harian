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

                    \App\Models\JadwalRoadmap::create([
                        'gelombang_id' => $gelombang->id,
                        'roadmap_id' => $this->record->id,
                        'materi_id' => $materi->id,
                        'judul' => $materi->nama_materi,                      
                        'bulan_tahun' => $this->bulan_tahun,
                        'tanggal_ujian' => null,
                        'is_aktif' => true,
                    ]);

                    // bulan di tambah 1 bulan
                    $this->bulan_tahun = date('Y-m-d', strtotime($this->bulan_tahun . ' +1 month'));

                }               

                

            }

        }


       
    }
}
