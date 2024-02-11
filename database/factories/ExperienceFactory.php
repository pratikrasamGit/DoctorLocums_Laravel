<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Experience;
use App\Models\Nurse;
use Illuminate\Support\Str;
use Faker\Generator as Faker;
use App\Enums\State;
use App\Enums\FacilityType;

$factory->define(Experience::class, function (Faker $faker) {
    $startingDate = $faker->dateTimeBetween('this week', '+6 days');
    $endingDate   = $faker->dateTimeBetween($startingDate, strtotime('+359 days'));
    return [
        'id' => Str::uuid(),
        'nurse_id' => function () {
            factory(Nurse::class)->create()->id;
        },
        'organization_name' => $faker->company,
        'exp_city' => $faker->city,
        'exp_state' => State::getKey(State::GA),
        'facility_type' => 40,
        'start_date' => $startingDate,
        'end_date' => $endingDate        
    ];
});
