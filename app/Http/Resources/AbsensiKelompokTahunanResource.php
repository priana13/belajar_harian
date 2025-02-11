<?php

namespace App\Http\Resources;

use App\Models\Kegiatan;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AbsensiKelompokTahunanResource extends JsonResource
{
    private static $kelompok_id, $tahun;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // $kelompok_id = self::$kelompok_id;

        // return [
        //     'nama_kegiatan' => $this->kategori_kegiatan->nama,
        //     'jumlah_kegiatan' => Kegiatan::where('kategori_id', $this->kategori_id)
        //         ->tahun(self::$tahun)->count(),
        //     'jumlah_kehadiran' => $this->absensi_kegiatan
        //         ->where('kelompok_id', $kelompok_id)
        //         // ->where('kelompok_id', 2)
        //         ->groupby('kegiatan_id', 'user_id')
        //         ->count()
        // ];
    }

    // public static function customCollection($resource, $kelompok_id, $tahun): AnonymousResourceCollection
    // {
    //     self::$kelompok_id = $kelompok_id;
    //     self::$tahun = $tahun;
    //     return parent::collection($resource);
    // }
}
