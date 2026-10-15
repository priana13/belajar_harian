<?php

namespace App\Filament\Resources\PremiumPlanResource\Pages;

use App\Filament\Resources\PremiumPlanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManagePremiumPlans extends ManageRecords
{
    protected static string $resource = PremiumPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
