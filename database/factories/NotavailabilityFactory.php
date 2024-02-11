<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Notavailability;
use App\Models\Nurse;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(Notavailability::class, function (Faker $faker) {
    return [
        'id' => Str::uuid(),
        'nurse_id' => function () {
            factory(Nurse::class)->create()->id;
        },
        'specific_dates' => $faker->date($format = 'Y-m-d', $max = 'now')
    ];
});
