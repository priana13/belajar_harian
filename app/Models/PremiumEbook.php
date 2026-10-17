<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PremiumEbook extends Model
{
    protected $guarded = ['id'];

    protected $casts = ['is_active' => 'boolean'];

    public function materi()
    {
        return $this->belongsTo(Materi::class);
    }
}
