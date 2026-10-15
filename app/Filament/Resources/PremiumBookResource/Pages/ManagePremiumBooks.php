<?php

namespace App\Filament\Resources\PremiumBookResource\Pages;

use App\Filament\Resources\PremiumBookResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManagePremiumBooks extends ManageRecords
{
    protected static string $resource = PremiumBookResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
