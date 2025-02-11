<?php

namespace App\Filament\Resources\UjianResource\Pages;

use App\Filament\Resources\UjianResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUjians extends ListRecords
{
    protected static string $resource = UjianResource::class;

    protected static ?string $title = 'Ujian';

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
