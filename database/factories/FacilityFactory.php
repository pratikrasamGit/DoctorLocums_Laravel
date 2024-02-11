<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Facility;
use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;
use App\Enums\State;
use App\Enums\FacilityType;

$factory->define(Facility::class, function (Faker $faker) {
    return [
        'id' => Str::uuid(),
        'created_by' => function () {
            factory(User::class)->create()->id;
        },
        'name' => $faker->company,
        'address' => $faker->address,
        'city' => $faker->city,
        'state' => State::getKey(State::GA),
        'postcode' => $faker->postcode,
        'type' => 50,
        'active' => true
    ];
});
