<?php

namespace App\Http\Resources;

use App\Models\Materi;
use App\Http\Resources\MateriDetailCollection;
use App\Models\MateriDetail;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MateriCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // $materiDetail = MateriDetail::orderby('bab', 'asc');
        // $materi = Materi::where('type', 'Pembinaan');
        // return parent::toArray($materi);
        return parent::toArray($request);
    }
}
