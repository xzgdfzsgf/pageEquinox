<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

trait HasEmailHash
{
    /**
     * Deterministic HMAC of the email, used for lookups/uniqueness now that
     * the `email` column itself holds non-deterministic encrypted ciphertext.
     */
    public static function hashEmail(string $email): string
    {
        return hash_hmac('sha256', Str::lower(trim($email)), config('app.key'));
    }
}
