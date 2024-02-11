<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;

class NuRole extends Role
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
