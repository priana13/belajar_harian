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
            Card::make('Total Pengguna', User::all()->count())->color('success'),
            Card::make('Peserta', User::type(2)->count()),
            Card::make('Admin', User::type(1)->count()),  
                     
        ];
    }
}
