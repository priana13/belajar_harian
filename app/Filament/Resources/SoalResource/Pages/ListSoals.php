<?php

namespace App\Filament\Resources\SoalResource\Pages;

use App\Filament\Resources\SoalResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSoals extends ListRecords
{
    protected static string $resource = SoalResource::class;

    protected static ?string $title = 'Soal';

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
