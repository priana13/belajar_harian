<?php

namespace App\Filament\Resources\AbsensiKegiatanResource\Pages;

use App\Filament\Resources\AbsensiKegiatanResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAbsensiKegiatan extends EditRecord
{
    protected static string $resource = AbsensiKegiatanResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
