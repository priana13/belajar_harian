<?php

namespace App\Filament\Resources\SertifikatResource\Pages;

use App\Filament\Resources\SertifikatResource;
use Filament\Resources\Pages\Page;

class UploadSertifikat extends Page
{
    protected static string $resource = SertifikatResource::class;

    protected static string $view = 'filament.resources.sertifikat-resource.pages.upload-sertifikat';
}
