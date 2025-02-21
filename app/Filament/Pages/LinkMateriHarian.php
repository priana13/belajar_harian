<?php

namespace App\Filament\Pages;

use App\Models\Belajar;
use Filament\Pages\Page;

class LinkMateriHarian extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.link-materi-harian';

    public function mount(){

        $this->record = [];

        setlocale(LC_TIME, 'id_ID.UTF-8');
    }

    protected function getViewData(): array
    {
        $materi_hari_ini = Belajar::first();

        $jadwal_belajar = Belajar::first();


        return [
            "materi_hari_ini" => $materi_hari_ini,
            "jadwal_belajar" => $jadwal_belajar
        ];
    }
}
