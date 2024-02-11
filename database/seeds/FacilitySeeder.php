<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Enums\Role;
use App\Models\User;
use App\Models\NuRole;
use App\Models\NuPermission;
use App\Models\Facility;
use Illuminate\Support\Str;

class FacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $mainSuperUserId = User::where([
			'email' => 'fulladmin@nurseify.io'
        ])->get()->first()->id;
        
        $facility = factory(Facility::class)->create([
            'created_by' => $mainSuperUserId,
            'slug' => Str::slug(Str::uuid())
        ]);

        $user = factory(User::class)->create([
			'first_name' => 'Facility',
            'last_name' => 'User',
            'email' => 'facility@nursify.net',
            'user_name' => 'facility@nursify.net',
			'password' => Hash::make('password'),
			'role' => Role::getKey(Role::FACILITYADMIN),
			'mobile' => '7510452010'
        ]);

        // create specific user NuPermissions
        NuPermission::create([
            'id'=>Str::uuid(),
            'name' => 'facilityadmin-edit']);
        NuPermission::create([
            'id'=>Str::uuid(),
            'name' => 'facilityadmin-delete']);
        NuPermission::create([
            'id'=>Str::uuid(),
            'name' => 'facilityadmin-show']);
        NuPermission::create([
            'id'=>Str::uuid(),
            'name' => 'facilityadmin-create']);    

        $role = NuRole::create([
            'id'=>Str::uuid(),
            'name' => 'FacilityAdmin']);
        $role->givePermissionTo('facilityadmin-show');
        $role->givePermissionTo('facilityadmin-create');
        $role->givePermissionTo('facilityadmin-delete');
        $role->givePermissionTo('facilityadmin-edit');  

        $user->assignRole('FacilityAdmin');

    }
}
