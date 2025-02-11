<?php

namespace App\Filament\Resources\MateriResource\Pages;

use App\Filament\Resources\MateriResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMateris extends ListRecords
{
    protected static string $resource = MateriResource::class;

    protected static ?string $title = 'Materi';

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
