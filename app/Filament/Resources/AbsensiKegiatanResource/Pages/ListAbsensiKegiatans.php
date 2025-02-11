<?php

namespace App\Filament\Resources\AbsensiKegiatanResource\Pages;

use Actions\LocaleSwitcher;
use Filament\Pages\Actions;
use ListRecords\Concerns\Translatable;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\AbsensiKegiatanResource;

class ListAbsensiKegiatans extends ListRecords
{
    protected static string $resource = AbsensiKegiatanResource::class;
    protected static ?string $title = 'Absensi';

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah'),
        ];
    }
}
