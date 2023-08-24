<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'admin';

    protected $fillable = [
        'nama',
        'role',
        'email',
        'password',
        'password_reset'
    ];

    protected $hidden = 'password';

    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();

        static::deleted(function ($admin) {
            $sekolah = Sekolah::where('email_admin_sekolah', $admin->email)->first();
            if ($sekolah) {
                $sekolah->delete();
            }
        });
    }
}
