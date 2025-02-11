<?php

namespace App\Filament\Resources\UjianResource\Pages;

use App\Filament\Resources\UjianResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUjian extends EditRecord
{
    protected static string $resource = UjianResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
