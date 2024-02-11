<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;


class Keyword extends Model
{
    // km-2020-04-41: developer note: this model was not added to the uuid as the keyword id needs to remain an integer as it is
    // referenced by other models.  This effectively replaces the ENUM - hard coded definitions and is primarily accessible by the nursify team only
    
    use LogsActivity;
    use SoftDeletes;

    protected $fillable=[
        'filter',
        'title',
        'description',
        'dateTime',
        'amount',
        'count'
    ];

    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['dateTime', 'deleted_at'];

}
