<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PremiumBook extends Model
{
    protected $guarded = ['id'];

    protected $casts = ['is_available' => 'boolean'];

    public function materi()
    {
        return $this->belongsTo(Materi::class);
    }
}
