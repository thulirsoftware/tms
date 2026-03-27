<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call([
        //     AdminsTableSeeder::class,
        // ]);
        // $this->call(StatusSeeder::class);
        // $this->call(CfgActivitiesSeeder::class);
        $this->call(PermissionSeeder::class);
    }
}
