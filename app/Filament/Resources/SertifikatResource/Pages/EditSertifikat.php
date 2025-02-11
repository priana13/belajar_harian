<?php

namespace App\Filament\Resources\SertifikatResource\Pages;

use App\Filament\Resources\SertifikatResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSertifikat extends EditRecord
{
    protected static string $resource = SertifikatResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
