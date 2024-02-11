<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;

class Certification extends UuidModel
{
    use SoftDeletes;

    /**
	 *
	 * @var string
	 */
    private $nurse_id;

    /**
	 *
	 * @var integer
	 */
    private $type;

    /**
	 *
	 * @var string
	 */
    private $license_number;

    /**
	 *
	 * @var string
	 */
    private $effective_date;

    /**
	 *
	 * @var string
	 */
    private $expiration_date;

    /**
	 *
	 * @var string
	 */
    private $certificate_image;

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
    public $fillable = [
        'nurse_id',
        'type',
        'license_number',
        'effective_date',
        'expiration_date',
        'certificate_image',
        'active'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'effective_date' => 'date',
        'expiration_date' => 'date',
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
