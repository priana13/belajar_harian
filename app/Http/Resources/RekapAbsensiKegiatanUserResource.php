<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RekapAbsensiKegiatanUserResource extends JsonResource
{
    /**
     * Transform the resource inpto an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'kegiatan_id' => $this->kegiatan_id,
            'nama_kegiatan' => $this->kegiatan->kategori_kegiatan->nama,
            'waktu' => $this->kegiatan->waktu,
            'tempat' => $this->kegiatan->tempat,
            'keterangan' => $this->kegiatan->keterangan
        ];
    }
}
