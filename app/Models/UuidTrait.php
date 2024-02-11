<?php
    namespace App;

    use Facades\Str;

    trait UuidTrait
    {
       // public $incrementing = false;
        protected $keyType = 'string';

        protected static function boot()
        {
            parent::boot();

            static::creating(function ($model) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            });
        }
    }