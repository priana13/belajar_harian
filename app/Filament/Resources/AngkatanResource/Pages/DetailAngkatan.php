<?php

namespace App\Filament\Resources\AngkatanResource\Pages;

use App\Models\Angkatan;
use Filament\Resources\Pages\Page;
use App\Filament\Resources\AngkatanResource;

class DetailAngkatan extends Page
{
    public $record;

    protected static string $resource = AngkatanResource::class;

    protected static string $view = 'filament.resources.angkatan-resource.pages.detail-angkatan';

    public function mount(Angkatan $record){

        $this->record = $record;

        setlocale(LC_TIME, 'id_ID.UTF-8');
    }

    protected function getViewData(): array
    {
        $materi_hari_ini = $this->record->jadwal_belajar()->hariIni()->first();


        $jadwal_belajar = $this->record->jadwal_belajar;


        return [
            "materi_hari_ini" => $materi_hari_ini,
            "jadwal_belajar" => $jadwal_belajar
        ];
    }



}
