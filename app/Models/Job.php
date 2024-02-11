<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Job extends Model implements HasMedia
{
    use SoftDeletes;
    use HasMediaTrait;
    use LogsActivity;

    /**
     *
     * @var string
     */
    private $facility_id;

    /**
     *
     * @var integer
     */
    private $preferred_specialty;

    /**
     *
     * @var integer
     */
    private $preferred_assignment_duration;

    /**
     *
     * @var integer
     */
    private $preferred_shift;

    /**
     *
     * @var integer
     */
    private $preferred_shift_duration;

    /**
     *
     * @var integer
     */
    private $preferred_work_location;

    /**
     *
     * @var integer
     */
    private $preferred_work_area;

    /**
     *
     * @var string
     */
    private $preferred_days_of_the_week;

    /**
     *
     * @var string
     */
    private $preferred_hourly_pay_rate;

    /**
     *
     * @var string
     */
    private $preferred_experience;

    /**
     *
     * @var string
     */
    private $description;

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
    private $created_by;

    /**
     *
     * @var boolean
     */
    private $active;

    /**
     *
     * @var string
     */
    private $slug;

    /**
     *
     * @var string
     */
    private $job_video;

    /**
     *
     * @var integer
     */
    private $seniority_level;

    /**
     *
     * @var integer
     */
    private $job_function;

    /**
     *
     * @var string
     */
    private $qualifications;

    /**
     *
     * @var string
     */
    private $responsibilities;

    /**
     *
     * @var integer
     */
    private $job_cerner_exp;

    /**
     *
     * @var integer
     */
    private $job_meditech_exp;

    /**
     *
     * @var integer
     */
    private $job_epic_exp;

    /**
     *
     * @var string
     */
    private $job_other_exp;

    /**
     *
     * @var string
     */
    private $job_photos;

    /**
     *
     * @var string
     */
    private $video_embed_url;

    /**
     *
     * @var boolean
     */
    private $is_open;


    protected static function boot()
	{
		parent::boot();

		static::creating(function ($post) {
            $post->{$post->getKeyName()} = (string) Str::uuid();			
        });
    }
        
    public function getIncrementing()
	{
		return false;
	}

	public function getKeyType()
	{
		return 'string';
	}

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'facility_id',
        'preferred_specialty',
        'preferred_assignment_duration',
        'preferred_shift',
        'preferred_shift_duration',
        'preferred_work_location',
        'preferred_work_area',
        'preferred_days_of_the_week',
        'preferred_hourly_pay_rate',
        'preferred_experience',
        'description',
        'start_date',
        'end_date',
        'job_video',
        'job_photos',
        'seniority_level',
        'job_function',
        'responsibilities',
        'qualifications',
        'job_cerner_exp',
        'job_meditech_exp',
        'job_epic_exp',
        'job_other_exp',
        'created_by',
        'slug',
        'active',
        'is_open'
    ];

    protected static $logName = 'Job';
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'job_photos' => 'array',
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

    public function creator()
	{
		return $this->belongsTo(User::class, 'created_by');
    }

    public function jobAssets()
	{
		return $this->hasMany(JobAsset::class);
    }

    public function offers()
	{
		return $this->hasMany(Offer::class);
	}
}
