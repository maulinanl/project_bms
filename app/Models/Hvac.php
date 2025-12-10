<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hvac extends Model
{
    use HasFactory;

    protected $table = 'hvac';

    protected $fillable = [
        'hvac_type',
        'hvac_device_id',
        'hvac_brand',
        'hvac_pk',
        'hvac_status',
        'ruangan_id',
    ];

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class);
    }
}
