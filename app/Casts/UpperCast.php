<?php

namespace App\Casts;

use Illuminate\Support\Str;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class UpperCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes)
    {
        return Str::upper($value);
    }

    public function set($model, string $key, $value, array $attributes)
    {
        return Str::lower($value);
    }
}
