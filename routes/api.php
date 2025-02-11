<?php

use Illuminate\Http\Request;
use App\Models\AbsensiKegiatan;
use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UjianController;
use App\Http\Controllers\API\MateriController;
use App\Http\Controllers\API\KegiatanController;
use App\Http\Controllers\API\AbsensiKegiatanController;
use App\Http\Controllers\Auth\ForgotPasswordController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'auth:sanctum'], function () {
    // Route::get('/logout', [AuthController::class, 'logout']);
    // Route::get('/user_detail/{user_id}', [AuthController::class, 'user_detail']);
    // Route::post('/user_edit', [AuthController::class, 'update']);

    Route::get('/materi_pembinaan', [MateriController::class, 'materi_pembinaan']);
    Route::get('/materi_diklat', [MateriController::class, 'materi_diklat']);
    Route::get('/update_status_baca/{materi_detail_id}', [MateriController::class, 'update_status_baca']);
    Route::get('/cek_status_baca/{materi_id}', [MateriController::class, 'cek_status_baca']);

    Route::get('/list_kegiatan', [KegiatanController::class, 'list_kegiatan']);
    Route::get('/tambah_kegiatan', [KegiatanController::class, 'tambah_kegiatan']);
    Route::post('/submit_kegiatan', [KegiatanController::class, 'submit_kegiatan']);

    Route::get('/list_soal_materi/{materi_id}', [UjianController::class, 'list_soal_materi']);
    Route::get('/ikut_ujian/{materi_id}', [UjianController::class, 'ikut_ujian']);
    Route::get('/insert_jawaban/{soal_id}/{ujian_id}/{jawaban}', [UjianController::class, 'insert_jawaban']);
    Route::get('/lihat_nilai_ujian/{materi_id}', [UjianController::class, 'lihat_nilai_ujian']);
    Route::get('/update_nilai_ujian/{ujian_id}', [UjianController::class, 'update_nilai_ujian']);
    Route::get('/cek_jawaban_ujian/{ujian_id}', [UjianController::class, 'cek_jawaban_ujian']);

    Route::get('/list_anggota/{kelompok_id}', [AbsensiKegiatanController::class, 'list_anggota']);
    Route::get('/absensi_kegiatan/{id}', [AbsensiKegiatanController::class, 'absensi_kegiatan']);
    Route::get('/list_peserta_kegiatan/{kegiatan_id}', [AbsensiKegiatanController::class, 'list_peserta_kegiatan']);
    Route::get('/list_kelompok/{jenis_kelompok_id}', [AbsensiKegiatanController::class, 'list_kelompok']);
    Route::get('/list_jenis_kelompok', [AbsensiKegiatanController::class, 'list_jenis_kelompok']);
    Route::get('/mendaftar_kegiatan/{kegiatan_id}', [AbsensiKegiatanController::class, 'mendaftar_kegiatan']);
    Route::get('/kegiatan_user_mendatang', [AbsensiKegiatanController::class, 'kegiatan_user_mendatang']);
    Route::get(
        '/absensi_kelompok_sepekan/{kelompok_id}',
        [AbsensiKegiatanController::class, 'absensi_kelompok_sepekan']
    );
    Route::get(
        '/absensi_jenis_kelompok_sepekan/{jenis_kelompok_id}',
        [AbsensiKegiatanController::class, 'absensi_jenis_kelompok_sepekan']
    );
    Route::get(
        '/absensi_user_sepekan',
        [AbsensiKegiatanController::class, 'absensi_user_sepekan']
    );
    Route::post('/update_absensi', [AbsensiKegiatanController::class, 'update_absensi']);
    Route::get(
        '/absensi_user_tahunan/{user_id}/{tahun}',
        [AbsensiKegiatanController::class, 'absensi_user_tahunan']
    );
    Route::get(
        '/absensi_kelompok_tahunan/{user_id}/{tahun}',
        [AbsensiKegiatanController::class, 'absensi_kelompok_tahunan']
    );
    Route::get(
        '/absensi_jenis_kelompok_tahunan/{jenis_kelompok_id}/{tahun}',
        [AbsensiKegiatanController::class, 'absensi_jenis_kelompok_tahunan']
    );
});

Route::get('/materi_detail/{id}', [MateriController::class, 'materi_detail']);
// Route::post('/login', [AuthController::class, 'login']);
// Route::post('/register', [AuthController::class, 'register']);

Route::post('forgot_password', [ForgotPasswordController::class, 'forgot_password']);
