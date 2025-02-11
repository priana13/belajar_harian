<?php

namespace App\Http\Controllers;

use App\Filament\Resources\BelajarResource\Pages\Rekap;
use Illuminate\Http\Request;

class CobaController extends Controller
{
    public function store(Request $request){

        return $request->all();
    }
}
