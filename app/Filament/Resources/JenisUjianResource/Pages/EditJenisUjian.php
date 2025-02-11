<?php

namespace App\Filament\Resources\JenisUjianResource\Pages;

use App\Filament\Resources\JenisUjianResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJenisUjian extends EditRecord
{
    protected static string $resource = JenisUjianResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
