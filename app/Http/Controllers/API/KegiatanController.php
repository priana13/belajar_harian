<?php

namespace App\Http\Controllers\API;

use PDO;
use App\Models\User;
use App\Models\Kegiatan;
use App\Models\Kelompok;
use Illuminate\Http\Request;
use App\Models\AbsensiKegiatan;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Filament\Resources\KegiatanResource;
use App\Http\Resources\ListKegiatanResource;
use App\Models\KategoriKegiatan;

class KegiatanController extends Controller
{

    public function list_kegiatan(Request $request)
    {
        $sekarang = Kegiatan::select('kategori_kegiatan.nama as nama_kegiatan', 'kegiatan.*')
            ->join('kategori_kegiatan', 'kategori_kegiatan.id', 'kategori_id')
            ->join('absensi_kegiatan', 'absensi_kegiatan.kegiatan_id', 'kegiatan.id')
            ->where('absensi_kegiatan.user_id', Auth::id())
            // ->where('absensi_kegiatan.user_id', 53)
            ->whereDate('waktu', date('Y-m-d'))
            ->get();

        $mendatang = Kegiatan::select('kategori_kegiatan.nama as nama_kegiatan', 'kegiatan.*')
            ->join('kategori_kegiatan', 'kategori_kegiatan.id', 'kategori_id')
            ->where('waktu', '>', date('Y-m-d'))
            ->where('waktu', '<=', date('Y-m-d', strtotime('+1 month')))
            ->get();

        $kemarin = Kegiatan::select('kategori_kegiatan.nama as nama_kegiatan', 'kegiatan.*')
            ->join('kategori_kegiatan', 'kategori_kegiatan.id', 'kategori_id')
            ->join('absensi_kegiatan', 'absensi_kegiatan.kegiatan_id', 'kegiatan.id')
            ->where('absensi_kegiatan.user_id', Auth::id())
            // ->where('absensi_kegiatan.user_id', 53)
            ->where('waktu', '>=', date('Y-m-d', strtotime('-1 month')))
            ->where('waktu', '<', date('Y-m-d'))
            ->groupby('kegiatan.id')
            ->get();

        return response()->json([
            'kegiatan_sekarang' => $sekarang,
            'kegiatan_mendatang' => $mendatang,
            'kegiatan_kemarin' => $kemarin
        ], 200);
    }

    public function tambah_kegiatan()
    {
        $list_kegiatan = KategoriKegiatan::all();

        return response()->json([
            $list_kegiatan
        ], 200);
    }

    public function submit_kegiatan(Request $request)
    {
        $kegiatan = Kegiatan::create([
            'type' => 'Umum',
            'tempat' => $request->tempat,
            'link_lokasi' => $request->link_lokasi,
            'waktu' => $request->waktu,
            'keterangan' => $request->keterangan,
            'kategori_id' => $request->kategori_id,
        ]);

        return response()->json([
            'message' => 'berhasil diinput',
            'kegiatan_id' => $kegiatan,
        ], 200);
    }
}
