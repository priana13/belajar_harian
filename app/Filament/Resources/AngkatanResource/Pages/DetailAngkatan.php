<?php

namespace App\Filament\Resources\AngkatanResource\Pages;

use App\Filament\Resources\AngkatanResource;
use Filament\Resources\Pages\Page;

class DetailAngkatan extends Page
{
    protected static string $resource = AngkatanResource::class;

    protected static string $view = 'filament.resources.angkatan-resource.pages.detail-angkatan';
}
