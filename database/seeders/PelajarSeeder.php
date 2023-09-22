<?php

namespace Database\Seeders;

use App\Models\Pelajar;
use Illuminate\Database\Seeder;

class PelajarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Pelajar::create([
            "nisn" => "203307064",
            "uid" => "1851417870",
            "nama" => "Arfian Dwiki Rosyadi",
            "kode_sekolah" => "SMP3MADIUN",
            "jk" => "l",
            "nohp" => "081234567890",
            "alamat" => "Jl. Soekarno Hatta Kota Madiun",
            "password" => bcrypt("12345678"),
        ]);

        Pelajar::create([
            "nisn" => "213307046",
            "uid" => "1851071486",
            "nama" => "Lintang Diah Puspaningrum",
            "kode_sekolah" => "SDN01PANDE",
            "jk" => "p",
            "nohp" => "080987654321",
            "alamat" => "Jl. Serayu Kota Madiun",
            "password" => bcrypt("12345678"),
        ]);

        Pelajar::create([
            "nisn" => "213307015",
            "uid" => "1850850558",
            "nama" => "Masdika Ilhan Mansiz",
            "kode_sekolah" => "SMK3MADIUN",
            "jk" => "l",
            "nohp" => "088888888888",
            "alamat" => "Jl. Kerto Manis 2, Manisrejo, Kec. Taman, Kota Madiun, Jawa Timur 63138",
            "password" => bcrypt("12345678"),
        ]);

        Pelajar::create([
            "nisn" => "223307070",
            "uid" => "1851498350",
            "nama" => "Aisyah Putri",
            "kode_sekolah" => "SMP1MADIUN",
            "jk" => "p",
            "nohp" => "081914845439",
            "alamat" => "Kota Madiun",
            "password" => bcrypt("12345678"),
        ]);
    }
}