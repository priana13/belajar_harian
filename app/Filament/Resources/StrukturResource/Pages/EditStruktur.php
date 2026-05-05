<?php

namespace App\Filament\Resources\StrukturResource\Pages;

use App\Filament\Resources\StrukturResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStruktur extends EditRecord
{
    protected static string $resource = StrukturResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
