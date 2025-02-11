<?php

namespace App\Filament\Resources\MbsTransactionResource\Pages;

use App\Filament\Resources\MbsTransactionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMbsTransaction extends EditRecord
{
    protected static string $resource = MbsTransactionResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
