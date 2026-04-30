<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $fillable = [
        'type', 'subject_rf', 'participation_level', 'municipality',
        'event_date', 'discipline', 'email', 'data', 'status',
        'confirmation_token', 'sent_to_crm'
    ];

    protected $casts = [
        'data' => 'array',
        'event_date' => 'date',
        'sent_to_crm' => 'boolean',
    ];

    public function participants()
    {
        return $this->hasMany(Participant::class);
    }
}
