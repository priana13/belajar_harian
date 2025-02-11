<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gelombang extends Model
{
    use HasFactory;

    protected $table = 'gelombang';

    protected $guarded = [];

    public function angkatan(){

        return $this->hasMany(Angkatan::class, 'gelombang_id');
    }
}
