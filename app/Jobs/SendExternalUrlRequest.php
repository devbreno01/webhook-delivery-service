<?php

namespace App\Jobs;

use App\Models\DeliveryAttempt;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Throwable;

class SendWebhookRequestJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3; 
    public int $backoff = 5; 

    /**
     * Create a new job instance.
     */
    public function __construct(public int $eventId)
    {
       
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $responseMessage = null; 
        $responseCode = null; 

        try{
            $response = Http::post(env('APP_URL'). '/api/mock/webhooks/payments',[]); 
            $response->throw(); 

            $responseCode = $response->status();
            $responseMessage = $response->body();
            $attempts = DeliveryAttempt::where('event_id', $this->eventId)->count() + 1;
            $url = env('APP_URL'). '/api/mock/webhooks/payments';

            DeliveryAttempt::create([
                'event_id' => $this->eventId,
                'status' => 'delivered',
                'attempt_number' => $attempts,
                'url' => $url,
                'response_code' => $responseCode,
                'response_message' => $responseMessage,
            ]);
        }catch(Throwable $e){
            $responseCode = $e->getCode();
            $responseMessage = $e->getMessage();
            $attempts = DeliveryAttempt::where('event_id', $this->eventId)->count() + 1;
            $url = env('APP_URL'). '/api/mock/webhooks/payments';

            DeliveryAttempt::create([
                'event_id' => $this->eventId,
                'status' => 'failed',
                'attempt_number' => $attempts,
                'url' => $url,
                'response_code' => $responseCode,
                'response_message' => $responseMessage,
            ]);
        }
    }
}
