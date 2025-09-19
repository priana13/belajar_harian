<?php

namespace App\Filament\Resources\RoadmapResource\Pages;

use App\Filament\Resources\RoadmapResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRoadmaps extends ListRecords
{
    protected static string $resource = RoadmapResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
