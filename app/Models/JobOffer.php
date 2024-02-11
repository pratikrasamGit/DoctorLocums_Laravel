<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class JobOffer extends UuidModel
{
    use SoftDeletes;
    /**
     * table to store teachers records.
     */
    // protected $table = 'follows';

    protected $fillable = [
        'job_id',
        'offer_id',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($post) {
            $post->{$post->getKeyName()} = (string) Str::uuid();
        });
    }
}
