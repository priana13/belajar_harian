<?php

namespace App\Http\Resources;

use App\Models\Kegiatan;
use App\Models\AbsensiKegiatan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AbsensiUserTahunanResource extends JsonResource
{
    private static $user_id, $tahun;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'nama_kegiatan' => $this->kategori_kegiatan->nama,
            'jumlah_kegiatan' => Kegiatan::where('kategori_id', $this->kategori_id)
                ->tahun(self::$tahun)->count(),
            'jumlah_kehadiran' => $this->absensi_kegiatan
                ->where('user_id', self::$user_id)
                ->count(),
        ];
    }

    public static function customCollection($resource, $user_id, $tahun): AnonymousResourceCollection
    {
        self::$user_id = $user_id;
        self::$tahun = $tahun;
        return parent::collection($resource);
    }
}
