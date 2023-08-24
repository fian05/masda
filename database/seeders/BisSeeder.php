<?php

namespace Database\Seeders;

use App\Models\Bis;
use Illuminate\Database\Seeder;

class BisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Bis::create([
            "plat_nomor" => "AE7032BP",
            "rute_awal" => "Terminal Purbaya",
            "rute_akhir" => "Te'an",
            "jumlah_kursi" => 30,
        ]);
    }
}
