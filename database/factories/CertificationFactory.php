<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Certification;
use App\Models\Nurse;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(Certification::class, function (Faker $faker) {
    $startingDate = $faker->dateTimeBetween('this week', '+6 days');
    $endingDate   = $faker->dateTimeBetween($startingDate, strtotime('+359 days'));
    $id = Str::uuid();
    return [
        'id' => $id,
        'nurse_id' => function () {
            factory(Nurse::class)->create()->id;
        },
        'type' => 'ANOM',
        'license_number' => null,
        'effective_date' => $startingDate,
        'expiration_date' => $endingDate,
        'certificate_image' => $id,
        'active' => true
    ];
});
