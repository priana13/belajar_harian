<?php

namespace App\Filament\Pages;

use App\Models\Belajar;
use App\Models\JadwalUjian;
use Filament\Pages\Page;

class LinkMateriHarian extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.link-materi-harian';

    protected ?string $heading = '';


    public $tanggal;

    public function mount(){
        

        setlocale(LC_TIME, 'id_ID.UTF-8');

        $this->tanggal = date("Y-m-d");
    }

    protected function getViewData(): array
    {
                
        $materi_hari_ini = Belajar::tanggal( $this->tanggal )->get();

        $ujian_pekanan = JadwalUjian::pekanan()->tanggal($this->tanggal)->get();  

        $ujian_akhir = JadwalUjian::akhir()->tanggal($this->tanggal)->get();       
   
        return [
            "materi_hari_ini" => $materi_hari_ini, 
            "ujian_pekanan" => $ujian_pekanan,         
            "ujian_akhir" => $ujian_akhir          
        ];
    }
}
