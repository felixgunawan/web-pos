<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SalesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sales')->delete();
        DB::table('sales_invoices')->delete();
        for ($i = 1; $i < 501; $i++){
            $date = Carbon::create(2015, 5, 28, 0, 0, 0);
            $date = $date->addDays(rand(1, 350))->format('Y-m-d H:i:s');
            DB::table('sales_invoices')->insert([
                'user_id' => 2,
                'sale_invoice_no' => $i,
                'total' => 0,
                'paid' => 0,
                'change' => 0,
                'created_at' => $date,
                'updated_at' => $date,
            ]);
            $j_total = rand(1,10);
            for ($j = 0; $j < $j_total; $j++){
                $price = rand(1,500);
                DB::table('sales')->insert([
                    'location_id' => 1,
                    'salesinvoice_id' => $i,
                    'item_id' => $price,
                    'itemprice_id' => $price,
                    'qty' => rand(1,5),
                    'total_sale' => 0,
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
            }
        }
    }
}
