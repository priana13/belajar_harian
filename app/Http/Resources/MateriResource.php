<?php

namespace App\Http\Resources;

use App\Models\Ujian;
use App\Models\Materi;
use App\Models\MateriDetail;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\MateriDetailResource;
use App\Http\Resources\MateriDetailCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class MateriResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    private static $user_id;

    public function toArray($request)
    {
        $jumlah_bab = $this->materi_detail->count();

        if ($this->ujian->count() > 0) {
            $status_ujian = $this->ujian()->first()->statusUjian($this->id, Auth::id());
            if ($status_ujian->count()) {
                $status_ujian = $status_ujian->first()->keterangan;
            } else $status_ujian = 'belum ujian';
        } else $status_ujian = 'belum ujian';

        return [
            'materi_id' => $this->id,
            'nama_materi' => $this->nama_materi,
            'type' => $this->type,
            'sinopsis' => $this->sinopsis,
            'jenis_materi' => $this->jenis_materi,
            'image' => $this->image,
            'jumlah_bab' => $jumlah_bab,
            'status_ujian' => $status_ujian
        ];
    }
}
