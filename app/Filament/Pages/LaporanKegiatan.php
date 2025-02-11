<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\GrafikAnggota;
use App\Filament\Widgets\StatsOverview;
use App\Models\User;
use Filament\Pages\Page;
use Illuminate\Contracts\View\View;
use Filament\Resources\Table;
use Filament\Tables;

class LaporanKegiatan extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';   

    protected static ?string $navigationGroup = 'Laporan';

    protected static bool $shouldRegisterNavigation = false;
    
    protected static ?int $navigationSort = 90;

    public function render(): View
    {
        return view('filament.pages.laporan-kegiatan', $this->getViewData())
            ->layout(static::$layout, $this->getLayoutData());
    }


    protected function getViewData(): array
    {

        $user = User::first();        

        return [
            "nama" => $user->name,
        ];
    }


    protected function getHeaderWidgets(): array
    {
        return [
            // GrafikAnggota::class,
            // StatsOverview::class,
        ];
    }



}
