<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;

class InvoicePaid extends Notification
{
    const CHAT_ID = -375670260;
    const MAX_CONTENT_LENGTH =4000;

    private $content;

    public function __construct(string  $content)
    {
        $this->content = $content;
    }



    public function via()
    {
        return [TelegramChannel::class];
    }


    public function toTelegram()
    {
        $content = $this->content;
        $maxContentLength = self::MAX_CONTENT_LENGTH;
        $contentLength = strlen($content);

        // Current maximum length is 4096 UTF8 characters in telegram
        if($contentLength > $maxContentLength)
            $content = substr($content, 0, $maxContentLength);

        return TelegramMessage::create()
            ->to(self::CHAT_ID)
            ->options(['parse_mode' => 'HTML'])
            ->content($content);
    }


    public function setContent(string $content): void {
        $this->content = $content;
    }
}
