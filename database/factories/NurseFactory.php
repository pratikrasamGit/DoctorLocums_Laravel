<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Nurse;
use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;
use App\Enums\Role;
use App\Enums\Specialty;
use App\Enums\State;
use App\Enums\NursingDegree;
use App\Enums\LeadershipRoles;
use App\Enums\WorkLocation;

$factory->define(Nurse::class, function (Faker $faker) {
    return [
        'id' => Str::uuid(),
        'user_id' => function () {
            factory(User::class)->create([
                'role' => Role::getKey(Role::NURSE)
            ])->id;
        },
        'specialty' => 27,
        'nursing_license_state' => 'Texas',
        'nursing_license_number' => Str::random(10),
        'highest_nursing_degree' => null,
        'serving_preceptor' => true,
        'serving_interim_nurse_leader' => false,
        'leadership_roles' => null,
        'address' => $faker->address,
        'city' => $faker->city,
        'state' => State::getKey(State::GA),
        'postcode' => $faker->postcode,
        'country' => $faker->country,
        'hourly_pay_rate' => '25',
        'experience_as_acute_care_facility' => 2,
        'experience_as_ambulatory_care_facility' => 2,
        'active' => true
    ];
});
