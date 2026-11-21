<?php 

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\EventService;
use Illuminate\Http\Request;

class EventController extends Controller
{
    private EventService $eventService;

    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService;
    }

    public function createEvent(Request $request)
    {
        $validatedData = $request->validate([
            'type' => 'required|string',
            'amount' => 'nullable|numeric',
            'occurred_at' => 'nullable|date',
        ]);

        $event = $this->eventService->createEvent(
            $validatedData['type'],
            $validatedData['amount'] ?? null,
            $validatedData['occurred_at'] ?? null
        );

        return response()->json($event, 201);
    }

    public function getEventById($id)
    {
        $event = $this->eventService->getEventById($id);
        return response()->json($event);
    }
}