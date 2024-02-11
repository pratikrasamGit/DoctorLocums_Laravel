<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class FacilityAsset extends UuidModel
{
    use SoftDeletes;

    /**
     *
     * @var string
     */
    private $facility_id;

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
        'facility_id',
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

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }
}
