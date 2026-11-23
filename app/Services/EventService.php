<?php 

namespace App\Services;

use App\Models\Event;
use App\Jobs\SendWebhookRequestJob;

class EventService 
{ 
    public function createEvent(string $type, ?float $amount = null, $occurredAt = null) 
    { 

        $event = Event::create([ 
            'type' => $type, 
            'amount' => $amount, 
            'occurred_at' => $occurredAt ?? now(), 
            'delivery_status' => 'pending', 
        ]); 

        SendWebhookRequestJob::dispatch($event->id);
        return $event;
        
    } 

    public function getEventById(int $id): Event
    { 
        return Event::with('deliveryAttempts')->findOrFail($id); 
    } 
}