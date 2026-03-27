<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Admin',
            'type' => 'admin',
            'empId'=> 'admin',
            'email' => 'admin@thulirsoft.com',
            'password' => Hash::make('admin@123#'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

    }
}
