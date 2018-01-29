<?php

namespace App\Translation\Mail;

use App\Translation\Message;
use App\Translation\Utilities\MessageThreadBuilder;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ErrorSendingReply extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Original Message that the sender was trying to reply to.
     *
     * @var Message
     */
    public $originalMessage;

    /**
     * Subject of the email that failed to send.
     *
     * @var string
     */
    public $subject;

    /**
     * Body of the email that failed to send.
     *
     * @var string
     */
    public $body;

    /**
     * Create a new message instance.
     *
     * @param Message $originalMessage
     * @param $subject
     * @param $body
     */
    public function __construct(Message $originalMessage, $subject, $body)
    {

        // Reason we're passing the subject and body manually (instead of a
        // Message model, is because creating the model might have
        // potentially failed.

        // Irrespective of the reason of failure, we still need to notify
        // sender that their reply will not be sent.

        $this->subject = $subject;
        $this->body = $body;

        $this->originalMessage = $originalMessage;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = $this->subject ? 'Error Sending Reply: ' . $this->subject : "Error Sending Reply";
        $messages = MessageThreadBuilder::startingFrom($this->originalMessage);

        return $this->subject($subject)
            ->view('emails.messages.html.error-sending-reply', compact('messages'))
            ->text('emails.messages.text.error-sending-reply', compact('messages'));
    }
}
