<?php

namespace App\Filament\Widgets;

// use App\Models\JenisKelompok;
use App\Models\User;
use App\Models\Materi;
use App\Models\Angkatan;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsOverview extends BaseWidget
{

    protected static ?int $sort = 1;

    protected function getCards(): array
    {
        return [
            // Card::make('DPU', JenisKelompok::count())
            //         ->description('Total'),                    
            Card::make('Peserta',User::count()),
            Card::make('Materi',Materi::count()),
            Card::make('Angkatan',Angkatan::count()),
            
        ];
    }
}
