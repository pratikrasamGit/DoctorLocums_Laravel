<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Experience extends UuidModel
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
    private $organization_name;

    /**
     *
     * @var string
     */
    private $organization_department_name;

    /**
     *
     * @var string
     */
    private $position_title;

    /**
     *
     * @var string
     */
    private $exp_city;

    /**
     *
     * @var integer
     */
    private $exp_state;

    /**
     *
     * @var integer
     */
    private $facility_type;

    /**
     *
     * @var string
     */
    private $start_date;

    /**
     *
     * @var string
     */
    private $end_date;    

    /**
     *
     * @var string
     */
    private $description_job_duties;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $fillable = [
        'nurse_id',
        'organization_name',
        'organization_department_name',
        'position_title',
        'exp_city',
        'exp_state',
        'facility_type',
        'start_date',
        'end_date',
        'description_job_duties'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function nurse()
	{
		return $this->belongsTo(Nurse::class);
	}
}
