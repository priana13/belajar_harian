<?php

namespace App\Filament\Resources\AngkatanResource\Pages;

use App\Filament\Resources\AngkatanResource;
use App\Models\AngkatanUser;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAngkatan extends EditRecord
{
    protected static string $resource = AngkatanResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        // Runs after the form fields are saved to the database.

        if($this->record->status == 'Selesai'){

           $angkatan_users = AngkatanUser::where('angkatan_id', $this->record->id)->update([
            'status' => "Selesai"
           ]);
           
        }
    }
}
