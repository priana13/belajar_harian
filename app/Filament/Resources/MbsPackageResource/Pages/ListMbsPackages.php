<?php

namespace App\Filament\Resources\MbsPackageResource\Pages;

use App\Filament\Resources\MbsPackageResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMbsPackages extends ListRecords
{
    protected static string $resource = MbsPackageResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
