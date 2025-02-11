<?php

namespace App\Filament\Resources\JadwalUjianResource\Pages;

use App\Filament\Resources\JadwalUjianResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJadwalUjians extends ListRecords
{
    protected static string $resource = JadwalUjianResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
