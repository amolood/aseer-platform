<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'license_plate',
        'manufacturer',
        'model',
        'year',
        'color',
        'location',
        'missing_date',
        'status',
        'owner_name',
        'owner_contact',
        'description',
        'notes',
        'source',
        'is_approved',
        'user_id'
    ];

    protected $casts = [
        'missing_date' => 'date',
        'is_approved' => 'boolean',
        'year' => 'integer'
    ];

    public function photos()
    {
        return $this->hasMany(CarPhoto::class);
    }

    public function reports()
    {
        return $this->hasMany(CarReport::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', 1);
    }
}
