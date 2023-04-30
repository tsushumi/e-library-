<?php

namespace App\Casts;

use Illuminate\Support\Str;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class LowerCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes)
    {
        return Str::lower($value);
    }

    public function set($model, string $key, $value, array $attributes)
    {
        return Str::lower($value);
    }
}
