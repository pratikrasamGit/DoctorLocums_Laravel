<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(AddMoreRoleandPermissionsSeeder::class);
        $this->call(AdjustRoleandPermissionsSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(KeywordSeeder::class);
        $this->call(NurseSeeder::class);
        $this->call(FacilitySeeder::class);        
    }
 }
