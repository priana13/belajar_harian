<?php

namespace App\Filament\Resources\MbsTransactionResource\Pages;

use App\Filament\Resources\MbsTransactionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMbsTransactions extends ListRecords
{
    protected static string $resource = MbsTransactionResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function selesaikan($record){

        $package = $record->package;
        $user = $record->user;

        $user->subscribe($package);

       $record->status = "Completed";
       $record->save();
    }
}
