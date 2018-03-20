<?php

namespace App\Translation\Listeners;

use App\Translation\Attachments\PostmarkAttachmentFile;
use App\Translation\Events\ReplyReceived;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateAttachmentsForReplyMessage
{

    /**
     * Handle the event.
     *
     * @param  ReplyReceived $event
     * @return void
     */
    public function handle($event)
    {
        $event->message->newAttachments()
                       ->attachmentFiles(PostmarkAttachmentFile::convertArray($event->postmarkRequest->attachments()))
                       ->make();
    }
}