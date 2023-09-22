<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Monitoring extends Model
{
    use HasFactory;

    protected $table = 'monitoring';

    protected $fillable = [
        'nisn',
        'plat_nomor',
        'status',
        'latitude',
        'longitude',
        'created_at',
        'updated_at',
    ];
}
