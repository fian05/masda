<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sekolah extends Model
{
    use HasFactory;

    protected $table = 'sekolah';

    protected $fillable = [
        'kode_sekolah',
        'nama_sekolah',
        'latlong_sekolah',
        'alamat_sekolah',
        'nama_admin_sekolah',
        'email_admin_sekolah',
    ];

    protected $primaryKey = 'kode_sekolah';

    protected $keyType = 'string';

    protected $hidden = 'password_admin_sekolah';

    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();

        static::created(function ($sekolah) {
            $admin = new Admin;
            $admin->nama = $sekolah->nama_admin_sekolah;
            $admin->role = 'admin_sekolah';
            $admin->email = $sekolah->email_admin_sekolah;
            $admin->password = bcrypt("12345678");
            $admin->save();
        });

        static::updated(function ($sekolah) {
            $admin = Admin::where('email', $sekolah->email_admin_sekolah)->first();
            if($admin) {
                $admin->nama = $sekolah->nama_admin_sekolah;
                $admin->save();
            }
        });

        static::deleted(function ($sekolah) {
            $admin = Admin::where('email', $sekolah->email_admin_sekolah)->first();
            if ($admin) {
                $admin->delete();
            }
        });
    }
}
