<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    protected $fillable = [
        'application_id', 'last_name', 'first_name', 'patronymic',
        'birth_date', 'gender', 'participant_status', 'status_detail',
        'phone', 'email', 'clothing_size', 'is_minor', 'is_captain',
        'age', 'extra_data'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'is_minor' => 'boolean',
        'is_captain' => 'boolean',
        'extra_data' => 'array',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function legalRepresentative()
    {
        return $this->hasOne(LegalRepresentative::class);
    }
}
