<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RekapAbsensiKegiatanKelompokResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

    public function toArray($request)
    {
        // return [
        //     'kegiatan_id' => $this->kegiatan_id,
        //     'nama_kegiatan' => $this->kegiatan->kategori_kegiatan->nama,
        //     'waktu' => $this->kegiatan->waktu,
        //     'tempat' => $this->kegiatan->tempat,
        //     'keterangan' => $this->kegiatan->keterangan,
        //     'terdaftar' => $this->kelompokTerdaftarKegiatan($this->kegiatan_id, $this->kelompok_id)->count(),
        //     'hadir' => $this->absensiKegiatanKelompok($this->kegiatan_id, $this->kelompok_id, 'hadir')->count(),
        //     'izin' => $this->absensiKegiatanKelompok($this->kegiatan_id, $this->kelompok_id, 'izin')->count(),
        //     'sakit' => $this->absensiKegiatanKelompok($this->kegiatan_id, $this->kelompok_id, 'sakit')->count(),
        //     'alfa' => $this->absensiKegiatanKelompok($this->kegiatan_id, $this->kelompok_id, 'alfa')->count(),
        // ];
    }
}
