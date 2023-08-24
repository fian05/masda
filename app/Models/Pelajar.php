<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelajar extends Model
{
    use HasFactory;

    protected $table = 'pelajar';

    protected $fillable = [
        'nisn',
        'uid',
        'nama',
        'kode_sekolah',
        'jk',
        'nohp',
        'alamat',
        'password',
        'password_reset'
    ];

    protected $primaryKey = 'nisn';

    protected $keyType = 'string';

    protected $hidden = 'password';

    public $timestamps = false;
}
