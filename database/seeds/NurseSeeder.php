<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Enums\Role;
use App\Models\User;
use App\Models\Nurse;
use App\Models\Availability;
use Illuminate\Support\Str;

class NurseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = factory(User::class)->create([
			'first_name' => 'Michael',
            'last_name' => 'Nicolas',
            'email' => 'info@nurseify.io',
            'user_name' => 'info@nurseify.io',
			'password' => Hash::make('password'),
			'role' => Role::getKey(Role::NURSE),
			'mobile' => '9879510798'
        ]);
        $user->assignRole('Nurse');
        
        $nurse = factory(Nurse::class)->create([
            'user_id' => $user->id,
            'slug' => Str::slug($user->first_name.' '.$user->last_name.' '.Str::uuid())        
        ]);

        $availability = Availability::create([
            'nurse_id' => $nurse->id,
            'work_location' => 38,
        ]);
    }
}
