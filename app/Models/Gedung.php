<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gedung extends Model
{
    use HasFactory;

    protected $table = 'gedung';

    protected $fillable = [
        'gateway_id',
        'building_name',
        'building_adress',
        'building_longitude',
        'building_latitude',
        'building_daya',
        'gateway_status',
        'foto_building',
    ];

    public function lantai()
    {
        return $this->hasMany(Lantai::class);
    }

    public function mainPower()
    {
        return $this->hasMany(MainPower::class);
    }
}
