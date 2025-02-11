<?php

namespace App\Http\Resources;

use App\Models\MateriDetailUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Resources\Json\JsonResource;

class CekStatusBacaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        $cek_status_baca = MateriDetailUser::where('materi_detail_id', $this->id)
            ->where('user_id', Auth::id())
            ->groupby('materi_detail_id', 'user_id')
            ->count();

        if ($cek_status_baca) {
            $status_baca = 1;
        } else $status_baca = 0;

        return [
            'id' => $this->id,
            'materi_id' => $this->materi_id,
            'pertemuan' => $this->pertemuan,
            'status_baca' => $status_baca
        ];
    }
}
