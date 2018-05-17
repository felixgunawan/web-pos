<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SuppliersTableSeeder extends Seeder {

    public function run()
    {
        // Uncomment the below to wipe the table clean before populating
        DB::table('suppliers')->delete();
        for ($i = 0; $i < 100; $i++){
          DB::table('suppliers')->insert([
                    'code' => str_random(10),
                    'name' => str_random(10),
                    'address' => str_random(10),
                    'city' => str_random(10),
                    'phone' => str_random(10),
                    'deadline' => rand(0,100),
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                 ]);
        }
    }

}
