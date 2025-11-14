<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CfgActivitiesSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'type'       => 'DEV',
                'name'       => 'Lunch',
                'isVisible'  => 'no',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null,
            ],
            [
                'type'       => 'DEV',
                'name'       => 'Meeting',
                'isVisible'  => 'no',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null,
            ],
            [
                'type'       => 'DEV',
                'name'       => 'Break',
                'isVisible'  => 'no',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null,
            ],
        ];

        DB::table('cfg_activities')->insert($data);
    }
}
