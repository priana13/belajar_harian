<?php

namespace App\Filament\Resources\GelombangResource\Pages;

use App\Filament\Resources\GelombangResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGelombang extends EditRecord
{
    protected static string $resource = GelombangResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
