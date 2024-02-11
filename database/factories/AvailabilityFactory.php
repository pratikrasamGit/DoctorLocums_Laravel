<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Availability;
use App\Models\Nurse;
use Illuminate\Support\Str;
use Faker\Generator as Faker;
use App\Enums\AssignmentDuration;
use App\Enums\WeekDays;

$factory->define(Availability::class, function (Faker $faker) {
    return [
        'id' => Str::uuid(),
        'nurse_id' => function () {
            factory(Nurse::class)->create()->id;
        },
        'assignment_duration' => 30,
        'shift_duration' => 50,
        'days_of_the_week' => [WeekDays::getKey(WeekDays::Sunday),WeekDays::getKey(WeekDays::Monday),WeekDays::getKey(WeekDays::Tuesday)],
        'work_location' => 37,
        'preferred_shift' => null,        
        'earliest_start_date' => null        
    ];
});
