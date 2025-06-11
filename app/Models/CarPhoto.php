<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'car_id',
        'path',
        'is_featured',
        'caption'
    ];

    protected $casts = [
        'is_featured' => 'boolean'
    ];

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function getUrlAttribute(): string
    {
        return asset('storage/public/' . $this->path);
    }
}
