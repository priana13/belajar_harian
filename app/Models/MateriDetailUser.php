<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MateriDetailUser extends Model
{
    use HasFactory;
    protected $table = "materi_detail_user";
    protected $guarded = [];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function materi_detail()
    {
        return $this->belongsTo(MateriDetail::class);
    }
}
