<?php

namespace App\Http\Resources;

use App\Models\Materi;
use App\Http\Resources\MateriResource;
use App\Http\Resources\MateriCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class KategoriMateriResource extends JsonResource
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
            'kategori_id' => $this->id,
            'nama_kategori' => $this->nama_kategori,
            'materi' => new MateriCollection($this->resource->materi
                ->where('type', $this->type))
        ];
    }
}
