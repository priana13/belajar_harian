<?php

namespace App\Filament\Resources\GelombangResource\Pages;

use App\Filament\Resources\GelombangResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGelombangs extends ListRecords
{
    protected static string $resource = GelombangResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
