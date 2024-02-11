<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\NuRole;
use App\Models\NuPermission;

class AdjustRoleandPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = NuRole::findByName('Administrator');
        $role->givePermissionTo('admin-delete');
        $role->givePermissionTo('nurse-show');
        $role->givePermissionTo('nurse-create');
        $role->givePermissionTo('nurse-delete');
        $role->givePermissionTo('nurse-edit');

        $facility = NuRole::findByName('Facility');
        $facility->revokePermissionTo('admin-create');
        $facility->revokePermissionTo('admin-edit');
        $facility->revokePermissionTo('admin-show');
        $facility->revokePermissionTo('delete');
        $facility->revokePermissionTo('create');
        $facility->revokePermissionTo('edit');

        $admin = NuRole::findByName('Admin');
        $admin->revokePermissionTo('admin-delete');
        $admin->revokePermissionTo('delete');
        $admin->givePermissionTo('nurse-show');
        $admin->givePermissionTo('nurse-create');
        $admin->givePermissionTo('nurse-delete');
        $admin->givePermissionTo('nurse-edit');

        $supervisor = NuRole::findByName('Supervisor');
        $supervisor->revokePermissionTo('admin-create');
        $supervisor->revokePermissionTo('admin-edit');
        $supervisor->revokePermissionTo('admin-show');
        $supervisor->revokePermissionTo('create');
        $supervisor->revokePermissionTo('delete');
        $supervisor->revokePermissionTo('edit');
        $supervisor->givePermissionTo('nurse-show');
        $supervisor->givePermissionTo('nurse-create');
        $supervisor->givePermissionTo('nurse-delete');
        $supervisor->givePermissionTo('nurse-edit');
    }
}
