<?php

namespace App\Http\Controllers\api;

use App\Models\User;
use App\Models\Kegiatan;
use App\Models\Kelompok;
use Illuminate\Http\Request;
use App\Models\AbsensiKegiatan;
use App\Models\KategoriKegiatan;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
// use App\Http\Resources\AbsensiJenisKelompokTahunanResource;
use App\Http\Resources\AbsensiKelompokTahunanResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ListPesertaKegiatanResource;
use App\Http\Resources\RekapAbsensiKegiatanUserResource;
use App\Http\Resources\AbsensiUserTahunanResource;
use App\Http\Resources\RekapAbsensiKegiatanKelompokResource;
use App\Models\JenisKelompok;
use App\Models\JenisUser;

class AbsensiKegiatanController extends Controller
{
    // public function list_anggota($kelompok_id)
    // {
    //     $user = User::select('id', 'name')->where('kelompok_id', $kelompok_id);
    //     return response()->json([
    //         $user->get()
    //     ], 200);
    // }

    public function absensi_kegiatan(Request $request, $id)
    {
        $user = User::find($id);
        $absensi_user = AbsensiKegiatan::select(
            [
                'kegiatan.nama',
                DB::raw('count(*) as jumlah_kehadiran')
            ]
        )
            ->join('kegiatan', 'kegiatan.id', 'kegiatan_id')
            ->join('users', 'users.id', 'user_id')
            ->groupby('kegiatan.nama')
            ->where('user_id', $id)->get();

        return response()->json([
            'user' => $user,
            'absensi_user' => $absensi_user
        ], 200);
    }

    // DAFTAR PESERTA YANG TERDAFTAR DI KEGIATAN TERTENTU
    public function list_peserta_kegiatan($kegiatan_id)
    {
        $peserta = AbsensiKegiatan::select('user_id', 'kegiatan_id', 'status_kehadiran')
            ->where('kegiatan_id', $kegiatan_id)
            ->where('siap_hadir', 1)->groupby('user_id')->get();

        return ListPesertaKegiatanResource::collection($peserta);
    }

    // public function list_kelompok($jenis_kelompok_id)
    // {
    //     return response()->json([
    //         Kelompok::where('jenis_kelompok_id', $jenis_kelompok_id)->get()
    //     ]);
    // }

    // public function list_jenis_kelompok()
    // {
    //     return response()->json([
    //         JenisKelompok::all()
    //     ]);
    // }

    // public function kegiatan_user_mendatang()
    // {
    //     $absensi = AbsensiKegiatan::select('user_id', 'kegiatan_id')
    //         ->join('kegiatan', 'kegiatan_id', 'kegiatan.id')
    //         ->where(function ($query) {
    //             $query->whereDate('waktu', '>=', date('Y-m-d'));
    //         })->where(function ($query) {
    //             $kelompok_id = User::find(Auth::id())->kelompok_id;
    //             $query->where('user_id', Auth::id())
    //                 ->where('kelompok_id', $kelompok_id);
    //         })
    //         ->groupby('kegiatan_id')->orderby('waktu', 'asc')->get();

    //     return RekapAbsensiKegiatanUserResource::collection($absensi);
    // }

    // UPDATE KEHADIRAN PESERTA YANG TERDAFTAR DI KEGIATAN TERTENTU
    public function update_absensi(Request $request)
    {
        $cek = AbsensiKegiatan::where('kegiatan_id', $request->kegiatan_id)
            ->where('user_id', $request->user_id);

        // if ($cek->count()) {
        //     $cek->update(['status_kehadiran' => $request->status_kehadiran]);
        // } else AbsensiKegiatan::create([
        //     'status_kehadiran' => $request->status_kehadiran,
        //     'user_id' => $request->user_id,
        //     'kegiatan_id' => $request->kegiatan_id,
        //     'kelompok_id' => User::find($request->user_id)->kelompok_id
        // ]);

        return response()->json([
            'message' => 'Berhasil'
        ], 200);
    }

    // HALAMAN REKAP JUMLAH KEHADIRAN PESERTA DI KEGIATAN MASA LALU DI KELOMPOK TERTENTU
    // public function absensi_kelompok_sepekan($kelompok_id)
    // {
    //     $kegiatan_kelompok = AbsensiKegiatan::select('kegiatan_id', 'kelompok_id')
    //         ->join('kegiatan', 'kegiatan_id', 'kegiatan.id')
    //         ->where('waktu', '<=', date('Y-m-d'))
    //         // ->whereBetween('waktu', [date("Y-m-d", strtotime("-7 day")), date('Y-m-d')])
    //         ->where('kelompok_id', $kelompok_id)
    //         ->groupby('kegiatan_id')->orderby('waktu', 'desc')->get();

    //     return RekapAbsensiKegiatanKelompokResource::collection($kegiatan_kelompok);
    // }

    // HALAMAN REKAP JUMLAH KEHADIRAN PESERTA DI KEGIATAN MASA LALU DI JENIS KELOMPOK TERTENTU
    // public function absensi_jenis_kelompok_sepekan($jenis_kelompok_id)
    // {
    //     $jenis_kelompok = Kelompok::select('id')
    //         ->where('jenis_kelompok_id', $jenis_kelompok_id)
    //         ->get();

    //     $kelompok_id = [];
    //     foreach ($jenis_kelompok as $key => $value) {
    //         array_push($kelompok_id, $value->id);
    //     }

    //     $kegiatan_jenis_kelompok = AbsensiKegiatan::select('kegiatan_id', 'kelompok_id')
    //         ->join('kegiatan', 'kegiatan_id', 'kegiatan.id')
    //         ->whereBetween('waktu', [date("Y-m-d", strtotime("-7 day")), date('Y-m-d')])
    //         ->whereIn('kelompok_id', $kelompok_id)
    //         ->groupby('kegiatan_id')->orderby('waktu', 'desc')->get();

    //     return RekapAbsensiKegiatanKelompokResource::collection($kegiatan_jenis_kelompok);
    // }

    // HALAMAN REKAP JUMLAH KEHADIRAN KEGIATAN UNTUK PESERTA TERTENTU
    public function absensi_user_sepekan()
    {
        $absensi = AbsensiKegiatan::select('user_id', 'kegiatan_id')
            ->join('kegiatan', 'kegiatan_id', 'kegiatan.id')
            ->whereBetween('waktu', [date("Y-m-d", strtotime("-7 day")), date('Y-m-d')])
            ->where('user_id', Auth::id())
            ->groupby('kegiatan_id')->orderby('waktu', 'desc')->get();

        return RekapAbsensiKegiatanUserResource::collection($absensi);
    }

    public function mendaftar_kegiatan($kegiatan_id)
    {
        AbsensiKegiatan::create([
            'kegiatan_id' => $kegiatan_id,
            'user_id' => Auth::id(),
            'siap_hadir' => 1,
            // 'kelompok_id' => User::find(Auth::id())->kelompok_id
        ]);

        return response()->json([
            'message' => 'Berhasil'
        ], 200);
    }

    public function absensi_user_tahunan($user_id, $tahun)
    {
        $kegiatan = Kegiatan::tahun($tahun)->groupby('kategori_id')->get();
        $user = User::find($user_id);

        return [
            // 'nama_user' => $user->name,
            // 'nama_kelompok' => ($user->kelompok_id) ? $user->kelompok->nama_kel : null,
            // 'nama_jenis_kelompok' => ($user->kelompok_id) ? $user->kelompok->jenis_kelompok->nama_jenis : null,
            // 'anggota_kelompok' => ($user->kelompok_id) ? $user->kelompok->count() : null,
            // 'rekap_kegiatan' => AbsensiUserTahunanResource::customCollection($kegiatan, $user_id, $tahun)
        ];
    }

    // public function absensi_kelompok_tahunan($kelompok_id, $tahun)
    // {
    //     $kegiatan = Kegiatan::tahun($tahun)
    //         ->groupby('kategori_id')->get();
    //     $nama_kelompok = Kelompok::find($kelompok_id);
    //     $anggota_jenis_kelompok = JenisKelompok::find(Kelompok::find($kelompok_id)->jenis_kelompok_id);

    //     return [
    //         'nama_kelompok' => $nama_kelompok->nama_kel,
    //         'nama_jenis_kelompok' => $nama_kelompok->jenis_kelompok->nama_jenis,
    //         'anggota_kelompok' => $nama_kelompok->anggota->count(),
    //         'anggota_jenis_kelompok' => $anggota_jenis_kelompok->anggota->count(),
    //         'rekap_absensi' => AbsensiKelompokTahunanResource::customCollection($kegiatan, $kelompok_id, $tahun)
    //     ];
    // }
}
