<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\NuRole;
use App\Models\NuPermission;
use App\Models\User;
use App\Models\Nurse;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');

        // create permissions
        NuPermission::create([
            'id'=>Str::uuid(),
            'name' => 'edit'
            ]);
        NuPermission::create([
            'id'=>Str::uuid(),
            'name' => 'delete']);
        NuPermission::create([
            'id'=>Str::uuid(),
            'name' => 'show']);
        NuPermission::create([
            'id'=>Str::uuid(),
            'name' => 'create']);

        // create specific user NuPermissions
        NuPermission::create([
            'id'=>Str::uuid(),
            'name' => 'nurse-edit']);
        NuPermission::create([
            'id'=>Str::uuid(),
            'name' => 'nurse-delete']);
        NuPermission::create([
            'id'=>Str::uuid(),
            'name' => 'nurse-show']);
        NuPermission::create([
            'id'=>Str::uuid(),
            'name' => 'nurse-create']);
        
        // create specific user NuPermissions
        NuPermission::create([
            'id'=>Str::uuid(),
            'name' => 'facility-edit']);
        NuPermission::create([
            'id'=>Str::uuid(),
            'name' => 'facility-delete']);
        NuPermission::create([
            'id'=>Str::uuid(),
            'name' => 'facility-show']);
        NuPermission::create([
            'id'=>Str::uuid(),
            'name' => 'facility-create']);        

        // create roles and assign created NuPermissions

        $role = NuRole::create([
            'id'=>Str::uuid(),
            'name' => 'Supervisor']);
        $role->givePermissionTo('edit');
        $role->givePermissionTo('create');
        $role->givePermissionTo('delete');
        $role->givePermissionTo('show');

        $role = NuRole::create([
            'id'=>Str::uuid(),
            'name' => 'Nurse']);
        $role->givePermissionTo('nurse-show');
        $role->givePermissionTo('nurse-create');
        $role->givePermissionTo('nurse-delete');
        $role->givePermissionTo('nurse-edit');

        $role = NuRole::create([
            'id'=>Str::uuid(),
            'name' => 'Facility']);
        $role->givePermissionTo('facility-show');
        $role->givePermissionTo('facility-create');
        $role->givePermissionTo('facility-delete');
        $role->givePermissionTo('facility-edit');        

        $role = NuRole::create([
            'id'=>Str::uuid(),
            'name' => 'Administrator']);
        $role->givePermissionTo(NuPermission::all());             
    }
}
