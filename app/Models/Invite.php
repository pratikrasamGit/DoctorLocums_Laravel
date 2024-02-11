<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Invite extends UuidModel
{
    use SoftDeletes;

    public const VALID_FOR_HOURS = 48;
    
    /**
	 *
	 * @var string
	 */
    private $user_id;

    /**
	 *
	 * @var string
	 */
    private $token;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'token',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function user()
	{
		return $this->belongsTo(User::class);
	}

}
