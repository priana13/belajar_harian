<?php

namespace App\Filament\Resources\BelajarResource\Pages;

use App\Filament\Resources\BelajarResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBelajar extends EditRecord
{
    protected static string $resource = BelajarResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
