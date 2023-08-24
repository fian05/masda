<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonitoringBis extends Model
{
    use HasFactory;

    protected $table = 'monitoring_bis';

    protected $fillable = [
        'plat_nomor',
        'latitude',
        'longitude',
        'sisa_pnp',
    ];
}
