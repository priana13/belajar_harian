<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ListPesertaKegiatanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'user_id' => $this->user_id,
            'nama_user' => $this->user->name,
            'kegiatan_id' => $this->kegiatan_id,
            'nama_kegiatan' => $this->kegiatan->kategori_kegiatan->nama,
            'status_kehadiran' => $this->status_kehadiran,
        ];
    }
}
