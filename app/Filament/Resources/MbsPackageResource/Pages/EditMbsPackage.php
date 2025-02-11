<?php

namespace App\Filament\Resources\MbsPackageResource\Pages;

use App\Filament\Resources\MbsPackageResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMbsPackage extends EditRecord
{
    protected static string $resource = MbsPackageResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
