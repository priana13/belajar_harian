<?php

namespace App\Filament\Resources\SertifikatUserResource\Pages;

use App\Filament\Resources\SertifikatUserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSertifikatUser extends EditRecord
{
    protected static string $resource = SertifikatUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
