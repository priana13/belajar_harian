<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Belajar extends Model
{
    use HasFactory;
    protected $table = "jadwal_belajar";

    protected $guarded = ['id'];

    public function user(){

        return $this->belongsTo(User::class);
    }

    public function materi_detail(){

        return $this->belongsTo(MateriDetail::class);
    }

    public function angkatan(){

        return $this->belongsTo(Angkatan::class, 'angkatan_id');
    }

    public function scopeAktif($query){

        return $query->whereIn('status',["Berikutnya" , "Sekarang" , "Selesai"]);
    }

    public static function getOptions(){

        return [
            'Berikutnya' => "Berikutnya", 
            "Sekarang" => "Sekarang", 
            "Selesai" => "Selesai", 
            "Expired" => "Expired"
        ];
    }


}
