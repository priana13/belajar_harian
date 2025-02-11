<?php

namespace App\Filament\Resources\MateriDetailResource\Pages;

use App\Filament\Resources\MateriDetailResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMateriDetails extends ListRecords
{
    protected static string $resource = MateriDetailResource::class;

    protected static ?string $title = 'Detail Materi / Bab';

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
