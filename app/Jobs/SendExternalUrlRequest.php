<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Throwable;

class SendWebhookRequestJob implements ShouldQueue
{
    use Queueable;

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

        }catch(Throwable $e){
            //log error
        }
    }
}
