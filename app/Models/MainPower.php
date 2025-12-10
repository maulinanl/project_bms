<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainPower extends Model
{
    use HasFactory;

    protected $table = 'main_power';

    protected $fillable = [
        'main_power_device_id',
        'main_power_status',
        'gedung_id',
    ];

    public function gedung()
    {
        return $this->belongsTo(Gedung::class);
    }
}
