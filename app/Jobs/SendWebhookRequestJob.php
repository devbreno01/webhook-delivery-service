<?php

namespace App\Jobs;

use App\Enums\StatusEnum;
use App\Models\DeliveryAttempt;
use App\Models\Event;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Throwable;

class SendWebhookRequestJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 5; 
    public int $backoff = 3; 

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
            $url = config('app.url') . '/api/mock/webhooks/payments?simulate=error';
            $response = Http::post($url, []);
            $response->throw(); 

            $responseCode = $response->status();
            $responseMessage = $response->body();
            $attempts = DeliveryAttempt::where('event_id', $this->eventId)->count() + 1;
            $url = env('APP_URL'). '/api/mock/webhooks/payments';

            DeliveryAttempt::create([
                'event_id' => $this->eventId,
                'status' => StatusEnum::DELIVERED->value,
                'attempt_number' => $attempts,
                'url' => $url,
                'response_code' => $responseCode,
                'response_message' => $responseMessage,
            ]);

            Event::where('id', $this->eventId)->update(['delivery_status' => StatusEnum::DELIVERED->value]);
        }catch(Throwable $e){
            $responseCode = $e->getCode();
            $responseMessage = $e->getMessage();
            $attempts = DeliveryAttempt::where('event_id', $this->eventId)->count() + 1;
            $url = env('APP_URL'). '/api/mock/webhooks/payments';

            DeliveryAttempt::create([
                'event_id' => $this->eventId,
                'status' => StatusEnum::FAILED->value,
                'attempt_number' => $attempts,
                'url' => $url,
                'response_code' => $responseCode,
                'response_message' => $responseMessage,
            ]);

            Event::where('id', $this->eventId)->update(['delivery_status' => StatusEnum::FAILED->value]);

        }
    }
}
