<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lantai extends Model
{
    use HasFactory;

    protected $table = 'lantai';

    protected $fillable = [
        'gedung_id',
        'floor_number',
        'floor_limit_beban',
        'floor_temperature',
        'floor_lux_level',
    ];

    public function gedung()
    {
        return $this->belongsTo(Gedung::class);
    }

    public function ruangan()
    {
        return $this->hasMany(Ruangan::class);
    }
}
