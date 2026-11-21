<?php 

namespace App\Services;

use App\Models\Event;

class EventService 
{ 
    public function createEvent(string $type, ?float $amount = null, $occurredAt = null) 
    { 
        return Event::create([ 
            'type' => $type, 
            'amount' => $amount, 
            'occurred_at' => $occurredAt ?? now(), 
            'delivery_status' => 'pending', 
        ]); 
        //dispach to the queue
    } 

    public function getEventById(int $id): Event
    { 
        return Event::with('deliveryAttempts')->findOrFail($id); 
    } 
}