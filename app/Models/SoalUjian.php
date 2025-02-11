<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoalUjian extends Model
{
    use HasFactory;

    protected $table = 'soal_ujian';

    protected $guarded = [];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function soal()
    {
        return $this->belongsTo(Soal::class);
    }

    public function scopeUserId($query, $user_id){
       
        return $query->where('user_id', $user_id);
    }

    public function scopeBenar($query){
       
        return $query->where('istrue', 1);
    }

    public function scopeSalah($query){
       
        return $query->where('istrue', 0);
    }

    public function ujian(){

        return $this->belongsTo(Ujian::class);
    }
}
