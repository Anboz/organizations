<?php

namespace App\Modules\Customers\Jobs;

use GuzzleHttp\Client as Guzzle;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SuspiciousClientNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function handle()
    {
        $guzzle = new Guzzle();
        $guzzle->post('https://api.telegram.org/bot1042817534:AAGAjeto2LWJV3uKbRf8fujXrZO9NB2ApHs/sendMessage',
            [
                'form_params' => [
                    'chat_id' => -375670260,
                    'text' => $this->message,
                ]
            ]
        );
    }
}
