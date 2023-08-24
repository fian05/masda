<?php

namespace Database\Seeders;

use App\Models\Sekolah;
use Illuminate\Database\Seeder;

class SekolahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Sekolah::create([
            "kode_sekolah" => "SMP3MADIUN",
            "nama_sekolah" => "SMP Negeri 3 Madiun",
            "latlong_sekolah" => "-7.6218704,111.520299",
            "alamat_sekolah" => "Jl. RA.Kartini No.6, Madiun Lor, Kec. Manguharjo, Kota Madiun, Jawa Timur 63122",
            "nama_admin_sekolah" => "Pak Admin",
            "email_admin_sekolah" => "pak.admin@gmail.com",
        ]);

        Sekolah::create([
            "kode_sekolah" => "SDN01PANDE",
            "nama_sekolah" => "SDN 01 Pandean",
            "latlong_sekolah" => "-7.6394019,111.5168966",
            "alamat_sekolah" => "Jl. Cokroaminoto No.152, Kejuron, Kec. Taman, Kota Madiun, Jawa Timur 63133",
            "nama_admin_sekolah" => "Bu Admin",
            "email_admin_sekolah" => "bu.admin@gmail.com",
        ]);

        Sekolah::create([
            "kode_sekolah" => "SMK3MADIUN",
            "nama_sekolah" => "SMK Negeri 3 Madiun",
            "latlong_sekolah" => "-7.6517513,111.5201035",
            "alamat_sekolah" => "Jalan Mayjen Panjaitan No.20A, Banjarejo, Taman, Banjarejo, Kec. Taman, Kabupaten Madiun, Jawa Timur 63137",
            "nama_admin_sekolah" => "Mas Admin",
            "email_admin_sekolah" => "mas.admin@gmail.com",
        ]);

        Sekolah::create([
            "kode_sekolah" => "SMP1MADIUN",
            "nama_sekolah" => "SMP Negeri 1 Madiun",
            "latlong_sekolah" => "-7.6221626,111.5197979",
            "alamat_sekolah" => "Jl. RA.Kartini No.4, Madiun Lor, Kec. Manguharjo, Kota Madiun, Jawa Timur 63122",
            "nama_admin_sekolah" => "Mbak Admin",
            "email_admin_sekolah" => "mbak.admin@gmail.com",
        ]);
    }
}
