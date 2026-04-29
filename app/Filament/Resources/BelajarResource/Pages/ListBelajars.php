<?php

namespace App\Filament\Resources\BelajarResource\Pages;

use App\Filament\Resources\BelajarResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBelajars extends ListRecords
{
    protected static string $resource = BelajarResource::class;

    protected static ?string $title = 'Jadwal Belajar';

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
