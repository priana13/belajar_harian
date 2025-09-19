<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GelombangRoadmap extends Model
{
    use HasFactory;

    protected $table = 'gelombang_roadmap';

    protected $fillable = [
        'roadmap_id',
        'gelombang_id'
    ];

    public function roadmap(): BelongsTo
    {
        return $this->belongsTo(Roadmap::class);
    }

    public function gelombang(): BelongsTo
    {
        return $this->belongsTo(Gelombang::class);
    }
    
}
