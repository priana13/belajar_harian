<?php

namespace App\Filament\Resources\KategoriMateriResource\Pages;

use App\Filament\Resources\KategoriMateriResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKategoriMateris extends ListRecords
{
    protected static string $resource = KategoriMateriResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
