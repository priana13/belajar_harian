<?php

namespace App\Models;

use App\Observers\JadwalBelajarObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([JadwalBelajarObserver::class])]
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

    public function roadmap(){

        return $this->belongsTo(Roadmap::class, 'roadmap_id');
    }

    public function gelombang(){

        return $this->belongsTo(Gelombang::class, 'gelombang_id');
    }

    public function scopeAktif($query){

        return $query->whereIn('status',["Berikutnya" , "Sekarang" , "Selesai"]);
    }

    public function scopeHariIni($query){
        

        return $query->whereDate('tanggal' , date('Y-m-d'));
    }

    public function scopeTanggal($query , $tanggal){
        

        return $query->whereDate('tanggal' , $tanggal);
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
