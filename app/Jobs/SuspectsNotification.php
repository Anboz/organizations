<?php

namespace App\Jobs;

use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SuspectsNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $collection;
    protected $operationType;
    protected  $initials;
    public function __construct(array $collection, Request $request)
    {
        $this->collection = $collection;
        $this->operationType = $request->get("operation_type");
        $this->initials = $request->get("initials");
    }

    public function handle()
    {
        $text = "Requested query: ".$this->initials."\n"
            . "Operation Type: ".$this->operationType."\n";

            foreach ($this->collection['suspect_list'] as $col) {
               $text.=('--------------------------------------'."\n");
               $text .= ('Suspect: ' . $col->first_name . ' ' . $col->second_name . ' ' . $col->third_name . ' ' . $col->fourth_name."\n");
               $text .=('Organization: ' . $col->organization."\n");
            }
        $client = new Client();
        $client->post('https://api.telegram.org/bot1042817534:AAGAjeto2LWJV3uKbRf8fujXrZO9NB2ApHs/sendMessage',
            [
                'form_params' => [
                    'chat_id' => -375670260,
                    'text' => $text
                ]
            ]
        );
    }
}
