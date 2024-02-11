<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\Activitylog\Traits\LogsActivity;
use App\Notifications\NurseifyRestPassword as ResetPasswordNotification;
use Illuminate\Support\Facades\Mail;

class User extends Authenticatable implements HasMedia
{
    use Notifiable;
    use SoftDeletes;
    use HasRoles;
    use HasMediaTrait;
    use LogsActivity;

    protected $appends = ['fullName'];

    /**
     *
     * @var string
     */
    private $role;

    /**
     *
     * @var string
     */
    private $image;

    /**
     *
     * @var string
     */
    private $first_name;

    /**
     *
     * @var string
     */
    private $last_name;

    /**
     *
     * @var string
     */
    private $email;

    /**
     *
     * @var string
     */
    private $user_name;

    /**
     *
     * @var string
     */
    private $password;

    /**
     *
     * @var string
     */
    private $email_verified_at;

    /**
     *
     * @var string
     */
    private $date_of_birth;

    /**
     *
     * @var string
     */
    private $mobile;

    /**
     *
     * @var boolean
     */
    private $email_notification;

    /**
     *
     * @var boolean
     */
    private $sms_notification;

    /**
     *
     * @var boolean
     */
    private $active;

    /**
     *
     * @var string
     */
    private $remember_token;

    /**
     *
     * @var string
     */
    private $banned_until;

    /**
     *
     * @var string
     */
    private $last_login_at;

    /**
     *
     * @var string
     */
    private $last_login_ip;

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
        'role',
        'image',
        'first_name',
        'last_name',
        'email',
        'user_name',
        'password',
        'date_of_birth',
        'mobile',
        'email_notification',
        'sms_notification',
        'active',
        'banned_until',
        'last_login_at',
        'last_login_ip',
        'fcm_token'
    ];

    /**
     * These are Activity Log Attributes. Its used to create the log entries for any changes made on the fields.
     *
     * @var array
     */
    protected static $logName = 'User';
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at', 'banned_until'];

    public function getFullNameAttribute()
    {
        if ($this->__get('first_name') && $this->__get('last_name')) {
            return Str::title($this->__get('first_name') . ' ' . $this->__get('last_name'));
        }
    }

    public function nurse()
    {
        return $this->hasOne(Nurse::class);
    }

    public function departments()
    {
        return $this->belongsToMany(Department::class, 'department_users');
    }

    public function facilities()
    {
        return $this->belongsToMany(Facility::class, 'facility_users');
    }

    public function createdFacilities()
    {
        return $this->hasMany(Facility::class, 'created_by');
    }

    public function createdJobs()
    {
        return $this->hasMany(Job::class, 'created_by');
    }

    public function createdDepartments()
    {
        return $this->hasMany(Department::class, 'created_by');
    }

    public function createdOffers()
    {
        return $this->hasMany(Offer::class, 'created_by');
    }

    public function sendPasswordResetNotification($token)
    {
        $notification = new ResetPasswordNotification($token);
        Mail::to($this)->send($notification->toMail($this));
    }

    public function invite()
    {
        return $this->hasOne(Invite::class);
    }
}
