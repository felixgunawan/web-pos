<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Item;

class PurchasesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('purchases')->delete();
        DB::table('purchases_invoices')->delete();
        for ($i = 1; $i < 501; $i++){
            $supplier_id = rand(1,99);
            $date = Carbon::create(2015, 5, 28, 0, 0, 0);
            $date = $date->addDays(rand(1, 350))->format('Y-m-d H:i:s');
            DB::table('purchases_invoices')->insert([
                'user_id' => 2,
                'supplier_id' => $supplier_id,
                'purchase_invoice_no' => $i,
                'total' => 0,
                'paid' => 0,
                'change' => 0,
                'created_at' => $date,
                'updated_at' => $date,
            ]);
            $item = Item::where('supplier_id','=',$supplier_id)->first();
            DB::table('purchases')->insert([
                'location_id' => 1,
                'purchasesinvoice_id' => $i,
                'item_id' => $item->id,
                'itemprice_id' => $item->id,
                'qty' => rand(1,5),
                'total_purchase' => 0,
                'created_at' => $date,
                'updated_at' => $date,
            ]);
        }
    }
}
