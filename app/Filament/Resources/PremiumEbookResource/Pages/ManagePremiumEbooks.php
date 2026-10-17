<?php

namespace App\Filament\Resources\PremiumEbookResource\Pages;

use App\Filament\Resources\PremiumEbookResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManagePremiumEbooks extends ManageRecords
{
    protected static string $resource = PremiumEbookResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
