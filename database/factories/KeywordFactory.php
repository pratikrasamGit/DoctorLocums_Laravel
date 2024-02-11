<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Keyword;
use App\Models\User;
use App\Enums\KeywordEnum;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(Keyword::class, function (Faker $faker) {
    return [
        'created_by' => function () {
            factory(User::class)->create()->id;
        },
        'filter' => KeywordEnum::getKey(KeywordEnum::Certification),
        'title' => 'Advanced HIV/AIDS Certified Registered Nurse'.Str::random(4),
        'description' => null,
        'dateTime' => null,
        'amount' => null,
        'count' => null,
        'active' => true,
    ];
});
