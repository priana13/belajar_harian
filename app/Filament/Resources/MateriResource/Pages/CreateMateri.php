<?php

namespace App\Filament\Resources\MateriResource\Pages;

use App\Filament\Resources\MateriResource;
use App\Models\MateriDetail;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMateri extends CreateRecord
{
    protected static string $resource = MateriResource::class;  

    protected function afterCreate(): void
    {
       
        for ($i=1; $i <= 20 ; $i++) { 

          MateriDetail::create([
            "materi_id" => $this->record->id,
            "judul" => "Pertemuan " . $i,
            "pertemuan" => $i
          ]);

        }

    }
}
