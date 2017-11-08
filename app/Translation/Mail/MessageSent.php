<?php

namespace App\Mail\Translation\Mail;

use App\Translation\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MessageSent extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * @var Message
     */
    public $translatedMessage;

    /**
     * Create a new message instance.
     *
     * @param Message $message
     */
    public function __construct(Message $message)
    {

        $this->translatedMessage = $message->load(['recipients', 'sourceLanguage', 'targetLanguage']);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = $this->translatedMessage->subject ? 'Translated and Sent: ' . $this->translatedMessage->subject : "Translated and Sent Message";
        return $this->subject($subject)
                    ->view('emails.translation.message-sent');
    }
}
