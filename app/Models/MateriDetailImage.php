<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MateriDetailImage extends Model
{
    use HasFactory;

    protected $table = "materi_detail_images";
    protected $guarded = [];

    public function materi_detail()
    {
        return $this->belongsTo(MateriDetail::class, 'materi_detail_id');
    }
}
