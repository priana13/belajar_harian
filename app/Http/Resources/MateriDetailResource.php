<?php

namespace App\Http\Resources;

use App\Models\MateriDetail;
use Illuminate\Http\Resources\Json\JsonResource;

class MateriDetailResource extends JsonResource
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
            'materi_id' => $this->id_materi,
            'nama_materi' => $this->nama_materi,
            'jumlah_bab' => MateriDetail::where('materi_id', $this->id_materi)->count(),
        ];
    }
}
