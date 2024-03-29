<?php

namespace App\Translation\Factories;

use App\Translation\Factories\RecipientFactory\RecipientEmails;
use App\Translation\Message;
use App\User;

/**
 * MessageFactory - Creates Messages (new and replies).
 *
 * @package App\Translation\Factories
 */
class MessageFactory
{

    /**
     * User that this Message belongs to.
     *
     * This User is the same for every Message on the Message
     * chain and will also be the User that is charged
     * for each reply.
     *
     * @var User
     */
    protected $owner;
    /**
     * Compose Message form request.
     *
     * @var
     */
    protected $formRequest;
    /**
     * Original Message ID for a reply Message.
     *
     * @var int|null
     */
    protected $messageId;
    /**
     * Newly created Message model.
     *
     * @var Message
     */
    protected $messageModel;
    /**
     * PostmarkInboundMailRecipient emails
     *
     * @var RecipientEmails
     */
    protected $recipientEmails;
    /**
     * Email Subject
     *
     * @var string
     */
    protected $subject;
    /**
     * Email body
     *
     * @var string
     */
    protected $body;
    /**
     * Whether to allow auto-translated replies.
     *
     * @var boolean
     */
    protected $autoTranslateReply;
    /**
     * Whether to send email back to sender.
     *
     * For when the client wants to review the translated message
     * before manually sending it himself.
     *
     * @var bool
     */
    protected $sendToSelf;
    /**
     * Email address of sender.
     *
     * @var string
     */
    protected $senderEmail;
    /**
     * Name of sender.
     * @var string|null
     */
    protected $senderName;
    /**
     * Source Language ID
     *
     * @var int
     */
    protected $langSrcId;
    /**
     * Target Language ID
     *
     * @var int
     */
    protected $langTgtId;

    /**
     * New MessageFactory for a new Message from given User.
     *
     * @param User $user
     * @return static
     */
    public static function newMessageFromUser(User $user)
    {
        $factory = new static();
        $factory->senderEmail = $user->email;
        $factory->senderName = $user->name;
        $factory->owner = $user;
        return $factory;
    }

    /**
     * New MessageFactory to make a reply Message.
     *
     * @param Message $originalMessage
     * @return static
     */
    public static function newReplyToMessage(Message $originalMessage)
    {
        $factory = new static();
        $factory->messageId = $originalMessage->id;
        // Replies mean that original message had auto-translate 'on'
        // and consequently send-to-self 'off'.
        $factory->autoTranslateReply = 1;
        $factory->sendToSelf = 0;
        // Reply message share same owner as original.
        $factory->owner = $originalMessage->owner;
        // a reply message will have flipped language pairs to the
        // original message.
        $factory->langSrcId = $originalMessage->lang_tgt_id;
        $factory->langTgtId = $originalMessage->lang_src_id;
        return $factory;
    }

    /**
     * The email of the person who sent the Message.
     *
     * This could be the same as owner email. Saved separately
     * to keep consistency from owner sent Message(s) and
     * reply Message(s).
     *
     * @param $email
     * @return string|MessageFactory
     */
    public function senderEmail($email = null)
    {
        if (is_null($email)) {
            return $this->senderEmail;
        }
        $this->senderEmail = $email;
        return $this;
    }

    /**
     * The name of the person who sent the Message.
     *
     *
     * @param $name
     * @return string|MessageFactory
     */
    public function senderName($name = null)
    {
        if (is_null($name)) {
            return $this->senderName;
        }
        $this->senderName = $name;
        return $this;
    }

    /**
     * The message subject.
     *
     * @param null $subject
     * @return string|MessageFactory
     */
    public function subject($subject = null)
    {
        if (is_null($subject)) {
            return $this->subject;
        }
        $this->subject = $subject;
        return $this;
    }

    /**
     * The message body to be translated.
     *
     * @param $body
     * @return string|MessageFactory
     */
    public function body($body = null)
    {
        if (is_null($body)) {
            return $this->body;
        }
        $this->body = $body;
        return $this;
    }

    /**
     * Whether message replies should be translated.
     *
     * @param bool $autoTranslate
     * @return bool|MessageFactory
     */
    public function autoTranslateReply($autoTranslate = null)
    {
        if (is_null($autoTranslate)) {
            return $this->autoTranslateReply;
        }
        $this->autoTranslateReply = $autoTranslate;
        return $this;
    }

    /**
     * Send back to owner and not to PostmarkInboundMailRecipient(s).
     *
     * @param $sendToSelf
     * @return bool|MessageFactory
     */
    public function sendToSelf($sendToSelf = null)
    {
        if (is_null($sendToSelf)) {
            return $this->sendToSelf;
        }
        $this->sendToSelf = $sendToSelf;
        return $this;
    }

    /**
     * The language to translate from.
     *
     * @param $id
     * @return int|MessageFactory
     */
    public function langSrcId($id = null)
    {
        if (is_null($id)) {
            return $this->langSrcId;
        }
        $this->langSrcId = $id;
        return $this;
    }

    /**
     * The language to translate to.
     *
     * @param $id
     * @return int|MessageFactory
     */
    public function langTgtId($id = null)
    {
        if (is_null($id)) {
            return $this->langTgtId;
        }
        $this->langTgtId = $id;
        return $this;
    }

    /**
     * User that owns (will be charged for) the Message.
     *
     * @param User $owner
     * @return User|MessageFactory
     */
    public function owner(User $owner = null)
    {
        if (is_null($owner)) {
            return $this->owner;
        }
        $this->owner = $owner;
        return $this;
    }

    /**
     * The id of the original Message (for replies).
     *
     * @param null $id
     * @return $this|int|null
     */
    public function messageId($id = null)
    {
        if (is_null($id)) {
            return $this->messageId;
        }
        $this->messageId = $id;
        return $this;
    }

    /**
     * Create Message model.
     *
     * @return $this
     */
    protected function createModel()
    {
        $this->messageModel = Message::create([
            'subject' => $this->subject,
            'body' => $this->body,
            'auto_translate_reply' => $this->autoTranslateReply,
            'send_to_self' => $this->sendToSelf,
            'sender_email' => $this->senderEmail,
            'sender_name' => $this->senderName,
            'user_id' => $this->owner->id,
            'message_id' => $this->messageId,
            'lang_src_id' => $this->langSrcId,
            'lang_tgt_id' => $this->langTgtId
        ]);
        return $this;
    }

    /**
     * Make our Message.
     *
     * @return Message
     */
    public function make()
    {
        $this->createModel();
        return $this->messageModel;
    }
}