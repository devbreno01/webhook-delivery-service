<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryAttempt extends Model
{
    protected $table = 'delivery_attempts'; 
    
    protected $fillable = [
        'event_id', 
        'status',
        'attempt_number',
        'url',
        'response_code',
        'response_message',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }
}
