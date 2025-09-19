<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gelombang extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = "gelombang";

    public function angkatan(){

        return $this->hasMany(Angkatan::class , 'gelombang_id');
    }

    public function peserta(){

        return $this->hasMany(User::class , 'gelombang_id');
    }

    public function roadmaps(): BelongsToMany
    {
        return $this->belongsToMany(Roadmap::class, 'gelombang_roadmap', 'gelombang_id', 'roadmap_id');
    }
}
