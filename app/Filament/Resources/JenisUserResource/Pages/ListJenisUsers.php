<?php

namespace App\Filament\Resources\JenisUserResource\Pages;

use App\Filament\Resources\JenisUserResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJenisUsers extends ListRecords
{
    protected static string $resource = JenisUserResource::class;
    protected static ?string $title = 'Jenis User';

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
