<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'car_id',
        'user_id',
        'location',
        'details',
        'contact_info',
        'report_type'
    ];

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault();
    }
}
