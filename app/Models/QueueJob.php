<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QueueJob extends Model
{
    protected $table = 'jobs';

    public $timestamps = false;

    protected $guarded = [];

    public function getJobNameAttribute(): ?string
    {
        $payload = json_decode($this->payload, true);

        return $payload['displayName'] ?? null;
    }
}
