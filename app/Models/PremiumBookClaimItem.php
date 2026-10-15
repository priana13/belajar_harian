<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PremiumBookClaimItem extends Model
{
    protected $guarded = ['id'];

    public function book()
    {
        return $this->belongsTo(PremiumBook::class, 'premium_book_id');
    }
}
