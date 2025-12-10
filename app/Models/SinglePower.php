<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SinglePower extends Model
{
    use HasFactory;

    protected $table = 'single_power';

    protected $fillable = [
        'single_power_device_id',
        'single_power_limit_beban',
        'single_power_brand',
        'single_power_status',
        'ruangan_id',
    ];

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class);
    }
}
