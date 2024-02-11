<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;
use App\Enums\Role;
use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'id' => Str::uuid(),
        'role' => Role::getKey(Role::FULLADMIN),
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'image' => null,
        'email' => $faker->unique()->safeEmail,
        'user_name' => $faker->userName,
        'password' => Hash::make('password'), // password
        'date_of_birth' => $faker->date($format = 'Y-m-d', $max = 'now'),
        'mobile' => $faker->phoneNumber,
        'email_notification' => true,
        'sms_notification' => true,
        'active' => true,
        'email_verified_at' => now(),
        'remember_token' => Str::random(10),
    ];
});
