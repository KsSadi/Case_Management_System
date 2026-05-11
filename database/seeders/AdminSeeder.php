<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (is_null(Admin::where('email', 'ino@prathomik.com')->first())) {
            $admin = new Admin();
            $admin->name = "Golam Kibria";
            $admin->email = "ino@prathomik.com";
            $admin->username = "kibria";
            $admin->password = Hash::make('kibria@123');
            $admin->save();
            $admin->assignRole('god');
        }
    }
}
