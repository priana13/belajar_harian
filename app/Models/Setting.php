<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Setting extends Pivot
{
    protected $table = 'setting';

    protected $guarded = [];

    public static function getValue($key)
    {

        $setting = self::where('key', $key)->first();

        if(!$setting){

            self::create([
                'key' => $key,
                'value' => null
            ]);

            $setting = self::where('key', $key)->first();
        }

        return $setting ? $setting : null;
    }
}
