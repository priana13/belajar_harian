<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PremiumBookClaim extends Model
{
    protected $guarded = ['id'];

    protected $casts = ['shipped_at' => 'datetime'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(PremiumBookClaimItem::class);
    }
}
