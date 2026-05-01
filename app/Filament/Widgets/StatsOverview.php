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
            Card::make('Peserta', number_format(User::count() , 0, ',', '.'))->color('success'),
            Card::make('Materi', number_format(Materi::count(), 0, ',', '.')),
            Card::make('Angkatan', number_format(Angkatan::count(), 0, ',', '.')),
        ];
    }
}
