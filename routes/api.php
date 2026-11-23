<?php

use App\Http\Controllers\Api\EventController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/events', [EventController::class, 'createEvent']);

Route::post('/mock/webhooks/payments', function (Request $request) {
    if ($request->query('simulate') === 'error') {
        return response()->json([
            'message' => 'Simulated failure'
        ], 500);
    }

    return response()->json([
        'message' => 'Webhook received'
    ], 200);
});

Route::get('/events/{id}', [EventController::class, 'getEventById']);