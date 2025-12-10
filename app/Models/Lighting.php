<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lighting extends Model
{
    use HasFactory;

    protected $table = 'lighting';

    protected $fillable = [
        'lighting_device_id',
        'lighting_type',
        'lighting_brand',
        'ruangan_id',
    ];

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class);
    }
}
