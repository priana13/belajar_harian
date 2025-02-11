<?php

namespace App\Filament\Resources\KategoriKegiatanResource\Pages;

use App\Filament\Resources\KategoriKegiatanResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKategoriKegiatans extends ListRecords
{
    protected static string $resource = KategoriKegiatanResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
