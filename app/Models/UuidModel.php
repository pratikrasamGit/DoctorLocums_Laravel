<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UuidModel extends Model
{
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
