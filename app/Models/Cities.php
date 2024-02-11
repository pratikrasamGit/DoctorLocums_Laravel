<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Cities extends UuidModel
{
    use SoftDeletes;
    /**
     * table to store teachers records.
     */
    // protected $table = 'follows';

    protected $fillable = [
        'id',
        'name',
        'state_id',
        'state_code',
        'country_id',
        'country_code',
        'latitude',
        'longitude',
        'created_at',
        'updated_at',
        'deleted_at',
        'flag',
        'wikiDataId',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($post) {
            $post->{$post->getKeyName()} = (string) Str::uuid();
        });
    }
}
