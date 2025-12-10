<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Ruangan extends Model
{
    use HasFactory;

    protected $table = 'ruangan';

    protected $fillable = [
        'lantai_id',
        'room_name',
        'room_limit_beban',
        'room_lux_level',
        'room_temperature',
    ];

    public function lantai()
    {
        return $this->belongsTo(Lantai::class);
    }

    public function singlePower()
    {
        return $this->hasMany(SinglePower::class);
    }

    public function hvac()
    {
        return $this->hasMany(Hvac::class);
    }

    public function lighting()
    {
        return $this->hasMany(Lighting::class);
    }
}
