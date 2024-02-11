<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Department extends UuidModel
{
    use SoftDeletes;
    use LogsActivity;

    /**
     *
     * @var string
     */
    private $facility_id;

    /**
     *
     * @var string
     */
    private $created_by;

    /**
     *
     * @var string
     */
    private $department_name;

    /**
     *
     * @var string
     */
    private $department_specialties;

    /**
     *
     * @var string
     */
    private $department_numbers;

    /**
     *
     * @var string
     */
    private $department_phone;

    /**
     *
     * @var string
     */
    private $department_mobile;

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
        'department_name',
        'department_specialties',
        'department_numbers',
        'department_phone',
        'department_mobile',
        'active'
    ];

    protected static $logName = 'Department';
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

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

    public function creator()
	{
		return $this->belongsTo(User::class, 'created_by');
    }

    public function users()
	{
		return $this->belongsToMany(User::class, 'department_users');
	}
}
