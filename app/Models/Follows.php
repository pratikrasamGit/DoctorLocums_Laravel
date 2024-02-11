<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Follows extends UuidModel
{
    use SoftDeletes;
    /**
     * table to store teachers records.
     */
    // protected $table = 'follows';

    protected $fillable = [
        'user_id',
        'job_id',
        'applied_status',
        'like_status'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($post) {
            $post->{$post->getKeyName()} = (string) Str::uuid();
        });
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function nurse()
    {
        return $this->hasOne(Nurse::class, 'user_id');
    }
}
