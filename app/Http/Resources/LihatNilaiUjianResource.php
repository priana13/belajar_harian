<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LihatNilaiUjianResource extends JsonResource
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
            'ujian_id' => $this->id,
            'nama_ujian' => $this->nama_ujian,
            'user_id' => $this->user->id,
            'nilai' => $this->nilai,
            'materi_id' => $this->materi_id,
            'nama_materi' => $this->materi->nama_materi,
            'keterangan' => $this->keterangan
        ];
    }
}
