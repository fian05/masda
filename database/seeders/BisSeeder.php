<?php

namespace Database\Seeders;

use App\Models\Bus;
use Illuminate\Database\Seeder;

class BusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Bus::create([
            "plat_nomor" => "AE7032BP",
            "rute_awal" => "Terminal Purbaya",
            "rute_akhir" => "Te'an",
            "jumlah_kursi" => 30,
        ]);
    }
}
