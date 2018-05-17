<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class LocationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('locations')->delete();
        for ($i = 0; $i < 5; $i++){
          DB::table('locations')->insert([
                    'code' => str_random(10),
                    'name' => str_random(10),
                    'address' => str_random(10),
                    'city' => str_random(10),
                    'phone' => str_random(10),
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                 ]);
        }
    }
}
