<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::create([
            "nama" => "Super Admin",
            "role" => "super",
            "email" => "amal.super@gmail.com",
            "password" => bcrypt("12345678"),
        ]);

        Admin::create([
            "nama" => "Admin",
            "role" => "admin",
            "email" => "amal.admin@gmail.com",
            "password" => bcrypt("12345678"),
        ]);
    }
}
