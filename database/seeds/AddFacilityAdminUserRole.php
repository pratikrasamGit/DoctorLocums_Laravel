<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\NuRole;
use App\Models\NuPermission;

class AddFacilityAdminUserRole extends Seeder
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
    }
}
