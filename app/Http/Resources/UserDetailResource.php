<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // if ($this->kelompok_id) {
        //     $nama_kelompok = $this->kelompok->nama_kel;
        //     $jenis_kelompok_id = $this->kelompok->jenis_kelompok_id;
        //     $nama_jenis_kelompok = $this->kelompok->jenis_kelompok->nama_jenis;
        // } else {
        //     $nama_kelompok = null;
        //     $jenis_kelompok_id = null;
        //     $nama_jenis_kelompok = null;
        // }

        return [
            'user_id' => $this->id,
            'nama' => $this->name,
            'email' => $this->email,
            'hp' => $this->no_hp,
            'alamat' => $this->alamat,
            'foto_profil' => $this->foto_profil,
            'kelompok_id' => $this->kelompok_id,
            'jenis_user' => $this->jenis_user->nama_jenis,
            'nama_kelompok' => $nama_kelompok,
            'jenis_kelompok_id' => $jenis_kelompok_id,
            'nama_jenis_kelompok' => $nama_jenis_kelompok
        ];
    }
}
