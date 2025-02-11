<?php

namespace App\Http\Resources;

use App\Models\Materi;
use App\Models\Soal;
use App\Models\SoalUjian;
use Illuminate\Http\Resources\Json\JsonResource;

class CekNilaiUjianResource extends JsonResource
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
        $jawaban_benar = SoalUjian::where('ujian_id', $this->id)
            ->where('istrue', 1)->count();
        $total_soal = Soal::where('materi_id', $this->materi_id)->count();
        $jawaban_salah = $total_soal - $jawaban_benar;

        return [
            'id' => $this->id,
            'nama_ujian' => $this->nama_ujian,
            'user_id' => $this->user_id,
            'materi_id' => $this->materi_id,
            'nama_materi' => Materi::find($this->materi_id)->nama_materi,
            'tanggal_ujian' => $this->created_at,
            'nilai' => $this->nilai,
            'total_soal' => $total_soal,
            'jawaban_benar' => $jawaban_benar,
            'jawaban_salah' =>  $jawaban_salah,
            'keterangan' => $this->keterangan
        ];
    }
}
