<?php

namespace App\Filament\Resources\SoalUjianResource\Pages;

use App\Filament\Resources\SoalUjianResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\SoalUjianResource\Widgets\SoalUjianOverview;

class ListSoalUjians extends ListRecords
{
    protected static string $resource = SoalUjianResource::class;

    protected static ?string $title = 'Soal Ujian';

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }


    protected function getHeaderWidgets(): array
    {
        return [
            SoalUjianOverview::class,
        ];
    }


}
