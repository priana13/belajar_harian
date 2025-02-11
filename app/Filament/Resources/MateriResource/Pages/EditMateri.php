<?php

namespace App\Filament\Resources\MateriResource\Pages;

use App\Filament\Resources\MateriResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMateri extends EditRecord
{
    protected static string $resource = MateriResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
