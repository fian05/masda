<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonitoringBus extends Model
{
    use HasFactory;

    protected $table = 'monitoring_bus';

    protected $fillable = [
        'plat_nomor',
        'latitude',
        'longitude',
        'sisa_pnp',
        'created_at',
        'updated_at',
    ];
}
