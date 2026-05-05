<?php

namespace App\Filament\Widgets;

use App\Models\Ujian;
use Carbon\Carbon;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class GrafikUjian extends ChartWidget
{
    protected static ?string $heading = 'Grafik Ujian';

    protected int | string | array $columnSpan = 2;

    protected static ?int $sort = 2;

    // protected static ?array $options = null;

   
    protected function getData(): array
    {        
        $data = Trend::model(Ujian::class)
            ->between(
                start: now()->addMonth(-3),
                end: now()->endOfMonth(),
            )
            ->perDay()
            ->count();
        
        // Filter data yang tidak kosong (aggregate > 0)
        $filteredData = $data->filter(fn (TrendValue $value) => $value->aggregate > 0);
    
        return [
            'datasets' => [
                [
                    'label' => 'Aktifitas Ujian',
                    'data' => $filteredData->map(fn (TrendValue $value) => $value->aggregate)->values(),
                ],
            ],
            'labels' => $filteredData->map(fn (TrendValue $value) => $value->date)->values(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    /**
     * @return array<string, mixed> | RawJs | null
     */
    protected function getOptions(): array | RawJs | null
    {
        return [
            'elements' => [
                'line' => [
                    'tension' => 0.7,
                    'borderCapStyle' => 'round',
                    'borderJoinStyle' => 'round',
                ],
            ],
        ];
    }

}