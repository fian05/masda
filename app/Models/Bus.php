<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    use HasFactory;

    protected $table = 'bus';

    protected $fillable = [
        'plat_nomor',
        'rute_awal',
        'rute_akhir',
        'jumlah_kursi',
    ];

    protected $primaryKey = 'plat_nomor';

    protected $keyType = 'string';

    public $timestamps = false;

    public function monitoringBuses() {
        return $this->hasMany(MonitoringBis::class, 'plat_nomor', 'plat_nomor');
    }
}

/*
    $connection: menentukan koneksi database yang akan digunakan oleh model.
    $keyType: menentukan tipe data dari kunci utama (primary key) model, misalnya int atau string.
    $incrementing: menentukan apakah kunci utama akan diatur secara otomatis oleh database atau tidak. Jika bernilai false, maka kunci utama akan diisi secara manual oleh aplikasi.
    $timestamps: menentukan apakah tabel yang berkaitan dengan model memiliki kolom created_at dan updated_at atau tidak. Jika bernilai false, maka kolom tersebut tidak akan di-generate.
    $hidden: menentukan kolom-kolom yang tidak ingin ditampilkan dalam hasil query.
    $visible: kebalikan dari $hidden, yaitu menentukan kolom-kolom yang ingin ditampilkan secara eksplisit dalam hasil query.
    $casts: menentukan tipe data untuk kolom-kolom pada tabel yang berkaitan dengan model.
    $dates: menentukan kolom-kolom pada tabel yang harus di-casting sebagai tipe data tanggal.
    $appends: menentukan kolom-kolom yang ingin ditambahkan dalam hasil query dengan menghitung nilai dari method yang telah didefinisikan dalam model.
    $with: menentukan relasi yang ingin dimuat secara otomatis ketika model dipanggil.
    $withCount: menentukan jumlah relasi yang ingin dimuat secara otomatis ketika model dipanggil.
    $perPage: menentukan jumlah item yang ingin ditampilkan per halaman pada pagination.
    $orderBy: menentukan default pengurutan data pada query model.
*/
