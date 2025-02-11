<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\LineChartWidget;
use Illuminate\Support\Facades\DB;

class GrafikAnggota extends LineChartWidget
{
    protected static ?string $heading = 'Peserta';

    protected static ?int $sort = 2;



    protected function getColumns(): int | array
    {
        return 1;
    }

    protected function getData(): array
    {
        $tgl = Carbon::now();
        $bulan_lalu = $tgl->addMonth(-1);      

        $anggota = User::select([
            'created_at as tanggal', DB::raw('count(*) as qty')
        ])->whereDate('created_at', '>=', $bulan_lalu)->groupBy('tanggal')->get();

        $jumlah_anggota = [];
        $tanggal = [];
        
        foreach ($anggota as $row) {
            $jumlah_anggota[] = $row->qty;
            $tanggal[] = date('d' , strtotime($row->tanggal) );
        }

        return [
            'datasets' => [
                [
                    'label' => 'Penambahan Peserta Baru',
                    'data' => $jumlah_anggota,
                ],
            ],
            'labels' => $tanggal,
        ];

    }
}
