<?php

namespace App\InboundMail\Postmark;

use App\Contracts\InboundMail\InboundMailRequest;
use App\Translation\Utilities\EmailReplyParser;
use Illuminate\Http\Request;

class PostmarkInboundMailRequest implements InboundMailRequest
{

    /**
     * @var Request
     */
    private $request;

    /**
     * Create PostmarkInboundMailRequest instance.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * The name of person who sent the email.
     *
     * @return string
     */
    public function fromName()
    {
        return $this->request["FromName"];
    }

    /**
     * The email address that sent the email.
     *
     * @return string
     */
    public function fromAddress()
    {
        return $this->request["From"];
    }

    /**
     * The email subject.
     *
     * @return string
     */
    public function subject()
    {
        return $this->request["Subject"];
    }

    /**
     * Only the reply (without previous emails or headers) in plain text.
     *
     * @return string
     */
    public function strippedReplyBody()
    {
        return EmailReplyParser::parse($this->request["TextBody"]);
    }

    /**
     * Email attachments.
     *
     * @return array
     */
    public function attachments()
    {
        return $this->request["Attachments"];
    }

    /**
     * The recipients for a given field.
     *
     * @param $field
     * @return array
     */
    private function recipientsForField($field)
    {
        $recipients = [];
        $jsonCollection = $this->request[$field];

        foreach ($jsonCollection as $json) {
            $recipient = new PostmarkInboundMailRecipient($json);
            array_push($recipients, $recipient);
        }

        return $recipients;
    }

    /**
     * The emails in standard 'to' field.
     *
     * @return  array
     */
    public function standardRecipients()
    {
        return $this->recipientsForField('ToFull');
    }

    /**
     * The emails in standard 'cc' field.
     *
     * @return  array
     */
    public function ccRecipients()
    {
        return $this->recipientsForField("CcFull");
    }

    /**
     * The emails in standard 'bcc' field.
     *
     * @return  array
     */
    public function bccRecipients()
    {
        return $this->recipientsForField("BccFull");
    }


    /**
     * The email address it was intended for.
     *
     * @return  string
     */
    public function inboundAddress()
    {
        return $this->request["OriginalRecipient"];
    }


    /**
     * Turns the inbound address into an array.
     *
     * Inbound Address Convention:
     * - snake_case for incoming mail address
     * - first part specifies the type of email
     * - ie. reply_s0m3h4$h@in.bemail.io, for replies to a specific Message
     *
     * @return array
     */
    private function inboundAddressArray()
    {
        return explode("_", $this->inboundAddress());
    }

    /**
     * The action that this email is trying to perform.
     *
     * ie. 'reply'
     *
     * @return string
     */
    public function action()
    {
        return $this->inboundAddressArray()[0];
    }

    /**
     * The target that this email is intended for.
     *
     * @return string
     */
    public function target()
    {
        preg_match("/.*(?=@)/", $this->inboundAddressArray()[1], $matches);
        return $matches[0];
    }

}