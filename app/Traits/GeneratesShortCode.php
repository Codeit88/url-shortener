<?php

namespace App\Traits;

use Illuminate\Support\Str;
use App\Models\ShortUrl;

trait GeneratesShortCode
{
    public function generateUniqueCode($length = 6)
    {
        do {
            $code = Str::random($length);
        } while (ShortUrl::where('short_code', $code)->exists());

        return $code;
    }
}