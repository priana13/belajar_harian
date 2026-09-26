<?php

namespace App\Admin;

use Illuminate\Database\Eloquent\Model;

class Resources
{
    public static function get(string $key): array
    {
        $resource = config("admin2.{$key}");
        abort_unless(is_array($resource), 404);

        return $resource;
    }

    public static function model(string $name): Model
    {
        $class = 'App\\Models\\'.$name;

        return new $class;
    }

    public static function navigation(): array
    {
        return collect(config('admin2'))->map(fn ($r, $key) => [
            'key' => $key, 'title' => $r['title'], 'group' => $r['group'],
            'url' => self::url($key),
        ])->values()->all();
    }

    public static function url(string $key): string
    {
        return self::get($key)['group'] === 'Konfigurasi'
            ? '/admin2/konfigurasi/'.$key : '/admin2/'.$key;
    }
}
