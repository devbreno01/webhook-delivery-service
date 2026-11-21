<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'events';

    protected $fillable = [
        'type',
        'amount',
        'occurred_at',
        'delivery_status'
    ];

    public function deliveryAttempts()
    {
        return $this->hasMany(DeliveryAttempt::class, 'event_id');
    }
}
