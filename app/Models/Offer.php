<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Offer extends UuidModel
{
    use SoftDeletes;

    /**
     *
     * @var string
     */
    private $nurse_id;

    /**
     *
     * @var string
     */
    private $created_by;

    /**
     *
     * @var string
     */
    private $status;

    /**
     *
     * @var boolean
     */
    private $active;

    /**
     *
     * @var string
     */
    private $expiration;

    /**
     *
     * @var string
     */
    private $is_view_date;

    /**
     *
     * @var boolean
     */
    private $is_view;

    /**
     *
     * @var string
     */
    private $job_id;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nurse_id',
        'created_by',
        'job_id',
        'status',
        'active',
        'expiration',
        'is_view',
        'is_view_date'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'expiration' => 'datetime',
        'is_view_date' => 'datetime',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function nurse()
    {
        return $this->belongsTo(Nurse::class);
    }

    public function job()
    {
        return $this->belongsTo(Job::class);
    }
}
