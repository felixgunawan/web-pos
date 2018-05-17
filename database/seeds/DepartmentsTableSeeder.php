<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DepartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('departments')->delete();
        for ($i = 0; $i < 100; $i++){
          DB::table('departments')->insert([
                    'code' => str_random(10),
                    'name' => str_random(10),
                    'details' => str_random(20),
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                 ]);
        }
    }
}
