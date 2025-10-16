<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SertifikatUser extends Model
{
    use HasFactory;

    protected $table = 'sertifikat_user';

    protected $guarded = [];

    public function user(){

        return $this->belongsTo(User::class);
    }

    public function sertifikat(){

        return $this->belongsTo(Sertifikat::class);
    }

    public function materi(){

        return $this->belongsTo(Materi::class);
    }
}
