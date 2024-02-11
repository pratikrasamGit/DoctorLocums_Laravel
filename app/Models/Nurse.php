<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Nurse extends Model implements HasMedia
{
    use SoftDeletes;
    use HasMediaTrait;
    use LogsActivity;

    /**
     *
     * @var string
     */
    private $user_id;

    /**
     *
     * @var string
     */
    private $specialty;

    /**
     *
     * @var string
     */
    private $mu_specialty;

    /**
     *
     * @var string
     */
    private $nursing_license_state;

    /**
     *
     * @var string
     */
    private $nursing_license_number;

    /**
     *
     * @var integer
     */
    private $highest_nursing_degree;

    /**
     *
     * @var boolean
     */
    private $serving_preceptor;

    /**
     *
     * @var boolean
     */
    private $serving_interim_nurse_leader;

    /**
     *
     * @var integer
     */
    private $leadership_roles;

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
     * @var string
     */
    private $country;

    /**
     *
     * @var string
     */
    private $hourly_pay_rate;

    /**
     *
     * @var float
     */
    private $experience_as_acute_care_facility;

    /**
     *
     * @var float
     */
    private $experience_as_ambulatory_care_facility;

    /**
     *
     * @var integer
     */
    private $ehr_proficiency_cerner;

    /**
     *
     * @var integer
     */
    private $ehr_proficiency_meditech;

    /**
     *
     * @var integer
     */
    private $ehr_proficiency_epic;

    /**
     *
     * @var string
     */
    private $ehr_proficiency_other;

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
    private $additional_photos;

    /**
     *
     * @var string
     */
    private $languages;

    /**
     *
     * @var boolean
     */
    private $clinical_educator;

    /**
     *
     * @var boolean
     */
    private $is_daisy_award_winner;

    /**
     *
     * @var boolean
     */
    private $employee_of_the_mth_qtr_yr;

    /**
     *
     * @var boolean
     */
    private $other_nursing_awards;

    /**
     *
     * @var boolean
     */
    private $is_professional_practice_council;

    /**
     *
     * @var boolean
     */
    private $is_research_publications;

    /**
     *
     * @var string
     */
    private $additional_files;

    /**
     *
     * @var string
     */
    private $college_uni_name;

    /**
     *
     * @var string
     */
    private $college_uni_city;

    /**
     *
     * @var string
     */
    private $college_uni_state;

    /**
     *
     * @var string
     */
    private $college_uni_country;

    /**
     *
     * @var string
     */
    private $facility_hourly_pay_rate;

    /**
     *
     * @var string
     */
    private $n_lat;

    /**
     *
     * @var string
     */
    private $n_lang;

    /**
     *
     * @var string
     */
    private $nu_video;

    /**
     *
     * @var string
     */
    private $nu_video_embed_url;

    /**
     *
     * @var boolean
     */
    private $is_verified;

    /**
     *
     * @var boolean
     */
    private $is_verified_nli;

    /**
     *
     * @var boolean
     */
    private $is_gig_invite;

    /**
     *
     * @var string
     */
    private $gig_account_id;

    /**
     *
     * @var string
     */
    private $gig_account_create_date;

    /**
     *
     * @var string
     */
    private $gig_account_invite_date;

    protected static function boot()
	{
		parent::boot();

		static::creating(function ($post) {
            $post->{$post->getKeyName()} = (string) Str::uuid();	
            $slug = Str::slug($post->user->getFullNameAttribute().'-'.$post->id);		
            $count = static::whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();
            $post->slug = $count ? "{$slug}-{$count}" : $slug;
        });        
        static::updating(function ($post) {
            $slug = Str::slug($post->user->getFullNameAttribute().'-'.$post->id);
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
    protected $fillable = [
        'user_id',
        'specialty',
        'mu_specialty',
        'nursing_license_state',
        'nursing_license_number',
        'highest_nursing_degree',
        'serving_preceptor',
        'serving_interim_nurse_leader',
        'leadership_roles',
        'address',
        'city',
        'state',
        'postcode',
        'country',
        'hourly_pay_rate',
        'experience_as_acute_care_facility',
        'experience_as_ambulatory_care_facility',
        'ehr_proficiency_cerner',
        'ehr_proficiency_meditech',
        'ehr_proficiency_epic',
        'ehr_proficiency_other',
        'summary',
        'active',
        'is_verified',
        'is_verified_nli',
        'clinical_educator',
        'is_daisy_award_winner',
        'employee_of_the_mth_qtr_yr',
        'other_nursing_awards',
        'is_professional_practice_council',
        'is_research_publications',
        'additional_photos',
        'languages',
        'additional_files',
        'college_uni_name',
        'college_uni_city',
        'college_uni_state',
        'college_uni_country',
        'nu_video'
    ];
    protected static $logName = 'Nurse';
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at','gig_account_create_date','gig_account_invite_date'];

    public function getCityStateAttribute() {
		if ($this->__get('city') && $this->__get('state')) {
			return Str::title($this->__get('city')).', '.$this->__get('state');
		}
	}

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function availability()
	{
		return $this->hasOne(Availability::class)->withTrashed();
    }

    public function certifications()
	{
		return $this->hasMany(Certification::class);
    }
    
    public function experiences()
	{
		return $this->hasMany(Experience::class);
    }
    
    public function notavailabilities()
	{
		return $this->hasMany(Notavailability::class);
    }
    
    public function nurseAssets()
	{
		return $this->hasMany(NurseAsset::class);
    }

    public function offers()
	{
		return $this->hasMany(Offer::class);
    }
}
