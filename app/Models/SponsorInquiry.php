<?php

namespace App\Models;

use App\Models\Concerns\HasEmailHash;
use Illuminate\Database\Eloquent\Model;

class SponsorInquiry extends Model
{
    use HasEmailHash;

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
        'email_hash',
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
        'name' => 'encrypted',
        'email' => 'encrypted',
        'company' => 'encrypted',
        'phone' => 'encrypted',
        'message' => 'encrypted',
    ];
}
