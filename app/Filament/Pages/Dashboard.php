<?php
 
namespace App\Filament\Pages;
 
use Filament\Pages\Dashboard as BasePage;
 
class Dashboard extends BasePage
{

    public function mount(){

       if(auth()->user()->jenis_user->nama_jenis != 'Admin'){

            abort(403);

       }
    }
    
    protected function getColumns(): int | array
    {
        return 2;
    }
}