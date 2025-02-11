<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kelas extends Model
{
    use HasFactory;
    
    protected $table = "kelas";

    protected $guarded = ['id'];

    public function angkatan(){

        return $this->belongsTo(Angkatan::class);
    }

    public function user(){

        return $this->belongsToMany(User::class, 'angkatan_users', 'angkatan_id' , 'user_id');
    }

    public function admin_satu(){

        return $this->belongsTo(User::class , 'admin1');
    }

    public function admin_dua(){

        return $this->belongsTo(User::class , 'admin2');
    }

    public function ujian(){

        return $this->hasMany(Ujian::class);
    }

}
