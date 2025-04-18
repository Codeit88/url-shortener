<?php

namespace App;

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
