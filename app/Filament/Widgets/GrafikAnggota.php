<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\LineChartWidget;
use Illuminate\Support\Facades\DB;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class GrafikAnggota extends LineChartWidget
{
    protected static ?string $heading = 'Peserta';

    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 2;


    protected function getColumns(): int | array
    {
        return 1;
    }

    protected function getData(): array
    {
      


        $data = Trend::model(User::class)
        ->between(
            start: now()->startOfYear(),
            end: now()->endOfYear(),
        )
        ->perMonth()
        ->count();
 
        return [
            'datasets' => [
                [
                    'label' => 'Grafik Penambahan Peserta',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];


    }
}
