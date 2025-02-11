<?php

namespace App\Filament\Resources\MateriDetailResource\Pages;

use App\Filament\Resources\MateriDetailResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMateriDetail extends EditRecord
{
    protected static string $resource = MateriDetailResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
