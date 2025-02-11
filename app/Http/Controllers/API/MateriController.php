<?php

namespace App\Http\Controllers\API;

use App\Models\Soal;
use App\Models\User;
use App\Models\MateriDetail;
use Illuminate\Http\Request;
use App\Models\KategoriMateri;
use App\Http\Controllers\Controller;
use App\Http\Resources\CekStatusBacaResource;
use App\Http\Resources\MateriResource;
use App\Http\Resources\KategoriMateriResource;
use App\Http\Resources\StatusBacaResource;
use App\Models\Materi;
use App\Models\MateriDetailUser;
use App\Models\SoalUjian;
use App\Models\Ujian;
use Illuminate\Support\Facades\Auth;

class MateriController extends Controller
{
    function materi_pembinaan()
    {
        $kategori = KategoriMateri::all();
        $kategoriResource = KategoriMateriResource::collection($kategori);
        $kategoriResource->map(function ($i) {
            $i->type = 'Pembinaan';
        });
        return $kategoriResource;
    }

    function materi_diklat()
    {
        $kategori = KategoriMateri::all();
        $kategoriResource = KategoriMateriResource::collection($kategori);
        $kategoriResource->map(function ($i) {
            $i->type = 'Diklat';
        });
        return $kategoriResource;
    }

    function materi_detail($id)
    {
        $materi_detail = MateriDetail::where('materi_id', $id)->get();
        return response()->json([
            $materi_detail
        ], 200);
    }

    function update_status_baca($materi_detail_id)
    {
        MateriDetailUser::create([
            'user_id' => Auth::id(),
            'materi_detail_id' => $materi_detail_id,
            'status' => 'sudah baca'
        ]);

        return response()->json([
            'message' => 'Berhasil'
        ], 200);
    }

    function cek_status_baca($materi_id)
    {
        $materi_detail = MateriDetail::where('materi_id', $materi_id)->get();
        return CekStatusBacaResource::collection($materi_detail);
    }
}
