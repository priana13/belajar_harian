<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PremiumSubscription extends Model
{
    protected $guarded = ['id'];

    protected $casts = ['starts_at' => 'datetime', 'ends_at' => 'datetime', 'approved_at' => 'datetime'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(PremiumPlan::class, 'premium_plan_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'paid')->where('starts_at', '<=', now())->where('ends_at', '>', now());
    }
}
