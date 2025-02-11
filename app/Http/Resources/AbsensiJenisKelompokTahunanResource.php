<?php

namespace App\Http\Resources;

use App\Models\Kelompok;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AbsensiJenisKelompokTahunanResource extends JsonResource
{
    private static $jenis_kelompok_id;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // $jenis_kelompok_id = self::$jenis_kelompok_id;
        // $kelompok_id = Kelompok::where('jenis_kelompok_id', $jenis_kelompok_id)
        //     ->pluck('id')->toArray();

        // $ids = '';
        // foreach ($kelompok_id as $key => $value) {
        //     $ids = $value . ', ' . $ids;
        // }

        // dd(substr($ids, 0, -2));

        // return [
        //     substr($ids, 0, -2),
        //     'nama_kegiatan' => $this->nama,
        //     'jumlah_kegiatan' => $this->kegiatan->count(),
        //     'jumlah_kehadiran' => $this->absensi_kegiatan
        //         // ->whereIn('kelompok_id', [substr($ids, 0, -2)])
        //         ->whereIn('kelompok_id', [6, 3, 1])
        //         ->groupby('kegiatan_id', 'user_id')
        //         ->count()
        // ];
    }

    // public static function customCollection($resource, $jenis_kelompok_id): AnonymousResourceCollection
    // {
    //     self::$jenis_kelompok_id = $jenis_kelompok_id;
    //     return parent::collection($resource);
    // }
}
