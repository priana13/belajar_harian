<?php

use App\Models\Kota;
use App\Models\Angkatan;
use App\Models\Provinsi;
use Livewire\Redirector;
use App\Http\Livewire\Pendaftaran;
use App\Http\Livewire\User\Profile;
use App\Http\Livewire\Homepage\Home;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CobaController;
use App\Http\Controllers\PageController;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\API\UjianController;



use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\auth\ForgotPasswordController;
use App\Http\Controllers\Auth\RegisteredUserController;


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

    Route::get('/evaluasi/{materi_id}/{jadwal_id}',App\Http\Livewire\Kuis\HalamanKuis::class)->name('kuis');
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

    Route::view('login','login')->name('login');
    
    Route::get('info-pendaftaran',function(){

        return view('info-pendaftaran');

    })->name('info-pendaftaran');

    // agar route sebelumnya tidak not found
    Route::get('/daftar/user', function(){
        return redirect()->route('register');
    });

    Route::get('/daftar', Pendaftaran::class)->name('register');

    Route::get('/daftar/{kode_daftar}', Pendaftaran::class)->name('daftar_angkatan');     

});

 
Route::get('/auth/redirect', [SocialiteController::class, 'redirect']); 
Route::get('/auth/callback', [SocialiteController::class, 'callback']);

Route::get('/jadwal', [JadwalController::Class , 'index'] );



require __DIR__ . '/auth.php';


Route::get('page/{page:slug}', [PageController::class, 'show'])->name('page.show');
