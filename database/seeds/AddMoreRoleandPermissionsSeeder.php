<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\NuRole;
use App\Models\NuPermission;

class AddMoreRoleandPermissionsSeeder extends Seeder
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
            'name' => 'admin-edit']);
        NuPermission::create([
            'id'=>Str::uuid(),
            'name' => 'admin-delete']);
        NuPermission::create([
            'id'=>Str::uuid(),
            'name' => 'admin-show']);
        NuPermission::create([
            'id'=>Str::uuid(),
            'name' => 'admin-create']);

        $role = NuRole::create([
            'id'=>Str::uuid(),
            'name' => 'Admin']);
        $role->givePermissionTo('admin-show');
        $role->givePermissionTo('admin-create');
        $role->givePermissionTo('admin-edit');
    }
}
