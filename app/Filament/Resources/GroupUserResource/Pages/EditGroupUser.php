<?php

namespace App\Filament\Resources\GroupUserResource\Pages;

use App\Filament\Resources\GroupUserResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGroupUser extends EditRecord
{
    protected static string $resource = GroupUserResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
