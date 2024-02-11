<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Facility extends Model implements HasMedia
{
    use SoftDeletes;
    use HasMediaTrait;
    use LogsActivity;

    /**
     *
     * @var string
     */
    private $created_by;

    /**
     *
     * @var string
     */
    private $name;

    /**
     *
     * @var string
     */
    private $address;

    /**
     *
     * @var string
     */
    private $city;

    /**
     *
     * @var string
     */
    private $state;

    /**
     *
     * @var string
     */
    private $postcode;

    /**
     *
     * @var integer
     */
    private $type;

    /**
     *
     * @var boolean
     */
    private $active;

    /**
     *
     * @var string
     */
    private $facility_logo;

    /**
     *
     * @var string
     */
    private $facility_email;

    /**
     *
     * @var string
     */
    private $facility_phone;

    /**
     *
     * @var string
     */
    private $specialty_need;

    /**
     *
     * @var string
     */
    private $slug;

    /**
     *
     * @var string
     */
    private $cno_message;

    /**
     *
     * @var string
     */
    private $cno_image;

    /**
     *
     * @var string
     */
    private $gallary_images;

    /**
     *
     * @var string
     */
    private $video;

    /**
     *
     * @var string
     */
    private $facebook;

    /**
     *
     * @var string
     */
    private $twitter;

    /**
     *
     * @var string
     */
    private $linkedin;

    /**
     *
     * @var string
     */
    private $instagram;

    /**
     *
     * @var string
     */
    private $pinterest;

    /**
     *
     * @var string
     */
    private $tiktok;

    /**
     *
     * @var string
     */
    private $sanpchat;

    /**
     *
     * @var string
     */
    private $youtube;

    /**
     *
     * @var string
     */
    private $about_facility;

    /**
     *
     * @var string
     */
    private $facility_website;

    /**
     *
     * @var string
     */
    private $video_embed_url;

    /**
     *
     * @var string
     */
    private $f_lat;

    /**
     *
     * @var string
     */
    private $f_lang;

    /**
     *
     * @var integer
     */
    private $f_emr;

    /**
     *
     * @var string
     */
    private $f_emr_other;

    /**
     *
     * @var integer
     */
    private $f_bcheck_provider;

    /**
     *
     * @var string
     */
    private $f_bcheck_provider_other;

    /**
     *
     * @var integer
     */
    private $nurse_cred_soft;

    /**
     *
     * @var string
     */
    private $nurse_cred_soft_other;

    /**
     *
     * @var integer
     */
    private $nurse_scheduling_sys;

    /**
     *
     * @var string
     */
    private $nurse_scheduling_sys_other;

    /**
     *
     * @var integer
     */
    private $time_attend_sys;

    /**
     *
     * @var string
     */
    private $time_attend_sys_other;

    /**
     *
     * @var string
     */
    private $licensed_beds;

    /**
     *
     * @var integer
     */
    private $trauma_designation;


    protected static function boot()
	{
		parent::boot();

		static::creating(function ($post) {
            $post->{$post->getKeyName()} = (string) Str::uuid();
            $slug = Str::slug($post->id);		
            $count = static::whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();
            $post->slug = $count ? "{$slug}-{$count}" : $slug;			
        });
        static::updating(function ($post) {
            $slug = Str::slug($post->id);
            $post->__set('slug', $slug);
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
    public $fillable = [
        'name',
        'address',
        'city',
        'state',
        'postcode',
        'type',
        'active',
        'facility_logo',
        'facility_email',
        'facility_phone',
        'specialty_need',
        'cno_message',
        'cno_image',
        'gallary_images',
        'video',
        'facebook',
        'twitter',
        'linkedin',
        'instagram',
        'pinterest',
        'tiktok',
        'sanpchat',
        'youtube',
        'about_facility',
        'facility_website',
        'f_emr',
        'f_emr_other',
        'f_bcheck_provider',
        'f_bcheck_provider_other',
        'nurse_cred_soft',
        'nurse_cred_soft_other',
        'nurse_scheduling_sys',
        'nurse_scheduling_sys_other',
        'time_attend_sys',
        'time_attend_sys_other',
        'licensed_beds',
        'trauma_designation'
    ];
    protected static $logName = 'Facility';
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

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
    
    public function users()
	{
        return $this->belongsToMany(User::class, 'facility_users');
    }  
    
    public function jobs()
	{
		return $this->hasMany(Job::class);
    }

    public function departments()
	{
		return $this->hasMany(Department::class);
    }

    public function facilityAssets()
	{
		return $this->hasMany(FacilityAsset::class);
    }
}
