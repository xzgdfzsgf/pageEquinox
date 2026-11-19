<?php

namespace App\Models;

use App\Models\Concerns\HasEmailHash;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasEmailHash;

    /** Audience profiles offered in the "I am..." select. */
    public const PROFILES = [
        'industry' => 'Space industry / company',
        'government' => 'Government or space agency',
        'investor' => 'Investor / business leader',
        'academia' => 'Academia / researcher',
        'student' => 'Student / emerging talent',
        'media' => 'Media',
        'other' => 'Other',
    ];

    protected $fillable = [
        'name',
        'email',
        'email_hash',
        'organization',
        'country',
        'profile',
        'consent',
        'source',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'consent' => 'boolean',
        'name' => 'encrypted',
        'email' => 'encrypted',
        'organization' => 'encrypted',
    ];
}
