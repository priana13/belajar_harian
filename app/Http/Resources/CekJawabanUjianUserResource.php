<?php

namespace App\Http\Resources;

use App\Models\SoalUjian;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class CekJawabanUjianUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $jawaban = SoalUjian::where('soal_id', $this->id)
            ->where('user_id', Auth::id());

        if ($jawaban->count()) {
            $jawaban_user = $jawaban->first()->jawaban;
            $istrue = $jawaban->first()->istrue;
        } else {
            $jawaban_user = null;
            $istrue = 0;
        }

        return [
            'id' => $this->id,
            'nomor' => $this->nomor,
            'materi_id' => $this->materi_id,
            'judul' => $this->judul,
            'a' => $this->a,
            'b' => $this->b,
            'c' => $this->c,
            'd' => $this->d,
            'kunci' => $this->kunci,
            'jawaban_user' => $jawaban_user,
            'istrue' => $istrue,
        ];
    }
}
