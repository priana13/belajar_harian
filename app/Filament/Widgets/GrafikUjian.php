<?php

namespace App\Filament\Widgets;

use App\Models\Ujian;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Filament\Widgets\LineChartWidget;

class GrafikUjian extends LineChartWidget
{
    protected static ?string $heading = 'Grafik Ujian';

    protected int | string | array $columnSpan = 2;


    protected static ?int $sort = 4;

   
    protected function getData(): array
    {
        $tgl = Carbon::now();
        $bulan_lalu = $tgl->addMonth(-1);      

        $ujian = Ujian::select([
            'created_at as tanggal', DB::raw('count(*) as qty')
        ])->whereDate('created_at', '>=', $bulan_lalu)->groupBy('tanggal')->get();
        

        $jumlah_ujian = [];
        $tanggal = [];
        
        foreach ($ujian as $row) {
            $jumlah_ujian[] = $row->qty;
            $tanggal[] = date('d' , strtotime($row->tanggal) );
        }

        return [
            'datasets' => [
                [
                    'label' => 'Grafik Ujian',
                    'data' => $jumlah_ujian,
                ],
            ],
            'labels' => $tanggal,
        ];

    }

}