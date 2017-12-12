<?php

namespace App\Http\Controllers;

use App\Translation\Mail\MessageSent;
use App\Translation\Mail\RecipientTranslatedMessage;
use App\Translation\Mail\SenderTranslatedMessage;
use App\Translation\Message;
use App\Translation\TranslationStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;


class GengoController extends Controller
{
    /**
     * Pick up the callback from Gengo.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function postPickUp(Request $request)
    {
        // Store our response, in case we need to return early. If
        // we don't return a 200 - Gengo will keep trying.
        $response = response("Got it", 200);
        // Ignore if call-back not for job (ie. comments are ignored at the moment)
        if(! array_key_exists("job", $request->all())) return $response;
        // Gengo posts the response inside a 'job' parameter
        $body = json_decode($request->all()["job"], true);
        // Parse out our message identifier that we originally sent over
        $messageHash = json_decode($body["custom_data"], true)["message_id"];
        // Get the status
        $status = $body["status"];
        // What Message is this callback for?
        $message = Message::findByHash($messageHash);
        // Switch on status - what was the callback for?
        switch ($status) {
            // Pending: Translator has begun work.
            case "pending":
                // Update message status
                $message->updateStatus(TranslationStatus::pending());
                break;
            // Approved: Job (completed translation)
            case "approved":
                // Store translated Body
                $message->update(['translated_body' => $body["body_tgt"]]);
                // Update message status
                $message->updateStatus(TranslationStatus::approved());
                // Send notification emails
                if($message->send_to_self) {
                    // Send translated message back to sender
                    Mail::to($message->user)->send(new SenderTranslatedMessage($message));
                } else {
                    // Send translated Message to Recipient(s)
                    foreach ($message->recipients as $recipient) {
                        Mail::to($recipient->email)->send(new RecipientTranslatedMessage($message));
                        // TODO ::: Send different mail for a reply
                    }
                    // Send translation complete notification to sender
                    Mail::to($message->user)->send(new MessageSent($message));
                }
                break;
            default:
                break;
        }
        // Much success!
        return $response;
    }
}
