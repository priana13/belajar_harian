<?php

namespace App\Filament\Widgets;

// use App\Models\JenisKelompok;
use Filament\Widgets\BarChartWidget;
use Filament\Widgets\LineChartWidget;
use DB;

class GrafikDpu extends LineChartWidget
{
    protected static ?string $heading = 'KBM';

    protected static ?int $sort = 3;

    protected function getData(): array
    {

        // $jenis_kelompok = JenisKelompok::with(['anggota'])->get();

        // foreach ($jenis_kelompok as $row) {          

        //     $jumlah_anggota[] = $row->anggota->count();
        //     $list_dpu[] = $row->nama_jenis;

        // }

        return [
            'datasets' => [
                [
                    'label' => 'Aktivitas KBM',
                    'data' => '',
                ],
            ],
            'labels' => '',
            'color' => [
                '#55s44g',
                'rgba(255, 159, 64, 0.2)',
                'rgba(255, 205, 86, 0.2)',
            ]
        ];
    }
}
