<?php

namespace App\Filament\Resources\UserResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\User;

class StatsOverview extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Total Pengguna', number_format(User::all()->count(), 0, ',', '.'))->color('success'),
            Card::make('Peserta', number_format(User::type(2)->count(), 0, ',', '.')),
            Card::make('Admin', number_format(User::type(1)->count(), 0, ',', '.')),  
                     
        ];
    }
}
