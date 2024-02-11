<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class JobAsset extends UuidModel
{
    use SoftDeletes;

    /**
     *
     * @var string
     */
    private $job_id;

    /**
     *
     * @var string
     */
    private $name;

    /**
     *
     * @var string
     */
    private $filter;

    /**
     *
     * @var boolean
     */
    private $active;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'job_id',
        'name',
        'filter',
        'active'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function job()
    {
        return $this->belongsTo(Job::class);
    }
}
