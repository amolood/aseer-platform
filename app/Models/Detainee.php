<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Detainee extends Model
{
    use HasFactory;

    public function photos()
    {
        return $this->hasMany(DetaineePhoto::class);
    }

    protected $fillable = [
        'name',
        'gender',
        'birth_date',
        'location',
        'detention_date',
        'status',
        'detaining_authority',
        'prison_name',
        'is_forced_disappearance',
        'family_contact_name',
        'family_contact_phone',
        'notes',
        'source',
        'is_approved',
        'martyr_date',
        'martyr_place',
        'martyr_reason',
        'martyr_notes',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }


    public function followers()
    {
        return $this->belongsToMany(User::class, 'detainee_followers')->withTimestamps();
    }

}
