<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		DB::table('users')->delete();
        
        DB::table('users')->insert([
            'location_id' => 1, 
            'username' => 'admin_utama',
            'name' => 'Admin Utama',
            'email' => 'admin_utama@example.com',
            'password' => bcrypt('admin_utama'),
        ]);

        DB::table('users')->insert([
            'location_id' => 1,
            'username' => 'kasir_utama',
            'name' => 'Kasir Utama',
            'email' => 'kasir_utama@example.com',
            'password' => bcrypt('kasir_utama'),
        ]);

        $admin = new Role();
        $admin->name         = 'admin';
        $admin->display_name = 'User Administrator'; // optional
        $admin->description  = 'User is allowed to manage and edit other users'; // optional
        $admin->save();

        $user = User::where('username', '=', 'admin_utama')->first();
        $user->attachRole($admin);
        
        $cashier = new Role();
        $cashier->name         = 'cashier';
        $cashier->display_name = 'Cashier'; // optional
        $cashier->description  = 'User is only allowed to create sales'; // optional
        $cashier->save();

        $user = User::where('username', '=', 'kasir_utama')->first();
        $user->attachRole($cashier);
    }
}
