<?php

use App\Http\Controllers\API\UjianController;
use App\Http\Controllers\auth\ForgotPasswordController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\CobaController;
use App\Http\Controllers\PageController;
use App\Http\Livewire\Homepage\Home;
use App\Http\Livewire\User\Profile;
use App\Models\Angkatan;
use Illuminate\Support\Facades\Route;



use Laravel\Socialite\Facades\Socialite;
use Livewire\Redirector;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/',Home::class)->name('home');

Route::middleware(['auth'])->group(function(){

    Route::get('/kuis/{materi_id}/{jadwal_id}',App\Http\Livewire\Kuis\HalamanKuis::class)->name('kuis');
    Route::get('/evaluasi-kuis/{materi_id}/{ujian_id}',App\Http\Livewire\Kuis\EvaluasiKuis::class)->name('evaluasi_kuis');
    Route::get('/peringkat',App\Http\Livewire\Kuis\Peringkat::class)->name('peringkat');

    Route::get('/materiku',App\Http\Livewire\Materi\MateriSaya::class)->name('materi_saya');
    Route::get('/history-belajar',App\Http\Livewire\HistoryBelajar\HistoryBelajar::class)->name('history_belajar');

    Route::get('/sertifikat/{ujian:kode_ujian}',App\Http\Livewire\Kuis\Sertifikat::class)->name('sertifikat');
    Route::get('/daftar-nilai/{ujian:kode_ujian}',App\Http\Livewire\Kuis\DaftarNilai::class)->name('daftar_nilai');

    Route::get('profile/user',  Profile::class)->name('profile');

    // koreksi nilai ujian
    Route::get('/koreksi-nilai', [UjianController::class, 'koreksiNilai']);



});


Route::get('kebijakan-privasi', function () {
    return view('privasi');
});



Route::middleware(['guest'])->group(function(){

    Route::get('login', function () {
        return view('login');
    })->name('login');
    

    
    Route::get('daftar/user', function () {  
        
        $angkatan = null;
        
        if(request()->kodeangkatan){

           $angkatan = Angkatan::pendaftaran()->where('kode_daftar', request()->kodeangkatan)->first();

           if(!$angkatan){

            return view('info-pendaftaran');

           }
        }

        return view('daftar' , compact('angkatan'));

    })->name('register');

    Route::get('/daftar/{angkatan:kode_daftar}', [RegisteredUserController::class, 'create'])->name('daftar_angkatan');
    

});

 
Route::get('/auth/redirect', [SocialiteController::class, 'redirect']); 
Route::get('/auth/callback', [SocialiteController::class, 'callback']);

require __DIR__ . '/auth.php';


Route::get('page/{page:slug}', [PageController::class, 'show'])->name('page.show');
