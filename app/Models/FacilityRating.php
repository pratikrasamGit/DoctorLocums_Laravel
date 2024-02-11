<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class FacilityRating extends UuidModel
{
    use SoftDeletes;
    /**
     * table to store teachers records.
     */
    // protected $table = 'follows';

    protected $fillable = [
        'nurse_id',
        'facility_id',
        'overall',
        'on_board',
        'nurse_team_work',
        'leadership_support',
        'tools_todo_my_job',
        'experience',
        'status',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($post) {
            $post->{$post->getKeyName()} = (string) Str::uuid();
        });
    }
}
