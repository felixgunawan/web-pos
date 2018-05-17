<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('items')->delete();
        DB::table('item_prices')->delete();
        DB::table('stocks')->delete();
        for ($i = 1; $i < 1001; $i++){
            $stock = rand(0,100);
            $supplier_id = rand(1,100);
            $department_id = rand(1,100);
            DB::table('items')->insert([
      			    'supplier_id' => $supplier_id,
      			    'department_id' => $department_id,
                    'item_code' => str_random(10),
                    'item_name' => str_random(10),
                    'unit' => str_random(3),
                    'first_stock' => $stock,
                    'now_stock' => $stock,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                 ]);
            DB::table('stocks')->insert([
                    'item_id' => $i,
                    'location_id' => 1,
                    'first_stock' => $stock,
                    'now_stock' => $stock,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                 ]);
            DB::table('item_prices')->insert([
                   'item_id' => $i,
                   'sell_price' => rand(100,200),
                   'buy_price' => rand(10,100),
                   'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                 ]);
        }
    }
}
