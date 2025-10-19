<?php

namespace App\Filament\Resources\RoadmapResource\Pages;

use App\Filament\Resources\RoadmapResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRoadmap extends EditRecord
{
    protected static string $resource = RoadmapResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\Action::make('Jadwal Roadmap')->url(function(){

                return route('filament.resources.roadmaps.buat-jadwal' , $this->record->id);
            })
        ];
    }
}
