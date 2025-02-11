<?php

namespace App\Filament\Resources\JenisUjianResource\Pages;

use App\Filament\Resources\JenisUjianResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJenisUjians extends ListRecords
{
    protected static string $resource = JenisUjianResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
