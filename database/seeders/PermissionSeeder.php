<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'Dashboard',
            'Roles',
            'Employees',
            'Tasks',
            'Leave',
            'Links',
            'Report',
            'SnapShot',
            'Config Tables',
        ];

        foreach ($permissions as $permission) {
            DB::table('permissions')->insert([
                'name' => $permission,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
