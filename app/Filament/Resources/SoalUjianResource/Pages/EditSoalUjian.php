<?php

namespace App\Filament\Resources\SoalUjianResource\Pages;

use App\Filament\Resources\SoalUjianResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSoalUjian extends EditRecord
{
    protected static string $resource = SoalUjianResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
