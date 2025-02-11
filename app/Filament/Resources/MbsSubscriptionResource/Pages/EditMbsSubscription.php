<?php

namespace App\Filament\Resources\MbsSubscriptionResource\Pages;

use App\Filament\Resources\MbsSubscriptionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMbsSubscription extends EditRecord
{
    protected static string $resource = MbsSubscriptionResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
