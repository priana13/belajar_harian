<?php

namespace App\Filament\Resources\JenisUserResource\Pages;

use App\Filament\Resources\JenisUserResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJenisUser extends EditRecord
{
    protected static string $resource = JenisUserResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
