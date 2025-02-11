<?php

namespace App\Filament\Widgets;

use App\Models\Ujian;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Filament\Widgets\LineChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class GrafikUjian extends LineChartWidget
{
    protected static ?string $heading = 'Grafik Ujian';

    protected int | string | array $columnSpan = 2;


    protected static ?int $sort = 2;

   
    protected function getData(): array
    {   
     


        $data = Trend::model(Ujian::class)
        ->between(
            start: now()->addMonth(-3),
            end: now()->endOfMonth(),
        )
        ->perDay()
        ->count();
 
        return [
            'datasets' => [
                [
                    'label' => 'Aktifitas Ujian',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];


    }

}