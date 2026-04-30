<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LegalRepresentative extends Model
{
    protected $fillable = [
        'participant_id', 'last_name', 'first_name', 'patronymic',
        'status', 'document', 'phone'
    ];

    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }
}
