<?php

namespace App\Filament\Resources\QueueResource\Pages;

use App\Filament\Resources\QueueResource;
use Filament\Resources\Pages\ListRecords;

class ListQueueJobs extends ListRecords
{
    protected static string $resource = QueueResource::class;
}
