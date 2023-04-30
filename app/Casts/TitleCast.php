<?php

namespace App\Casts;

use Illuminate\Support\Str;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class TitleCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes)
    {
        return Str::title($value);
    }

    public function set($model, string $key, $value, array $attributes)
    {
        return Str::lower($value);
    }
}
