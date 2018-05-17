<?php

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
        $this->call(LocationsTableSeeder::class);
		$this->call(UsersTableSeeder::class);
        $this->call(SuppliersTableSeeder::class);
        $this->call(DepartmentsTableSeeder::class);
        $this->call(ItemsTableSeeder::class);
        $this->call(SalesTableSeeder::class);
        $this->call(PurchasesTableSeeder::class);
    }
}
