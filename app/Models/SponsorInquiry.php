<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SponsorInquiry extends Model
{
    /** Partnership types offered in the sponsor form. */
    public const INTERESTS = [
        'sponsorship' => 'Sponsorship package',
        'exhibitor' => 'Exhibitor / showcase',
        'institutional' => 'Institutional partner',
        'media' => 'Media partner',
        'other' => 'Other / not sure yet',
    ];

    protected $fillable = [
        'name',
        'email',
        'company',
        'job_title',
        'phone',
        'country',
        'website',
        'interest',
        'message',
        'consent',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'consent' => 'boolean',
    ];
}
