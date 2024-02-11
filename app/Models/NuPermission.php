<?php

namespace App\Models;

use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;

class NuPermission extends Permission
{
	use SoftDeletes;
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
}
