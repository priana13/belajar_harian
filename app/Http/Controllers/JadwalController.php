<?php

namespace App\Http\Controllers;

use App\Models\Angkatan;
use App\Models\Belajar;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index(){
        
        return view('jadwal' , [
            'list_angkatan' => Angkatan::aktif()->get(),
        ]);
    }
}
