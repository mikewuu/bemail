<?php

namespace App\Contracts\Translation;

use App\Language;
use App\Message;

interface Translator
{
    /**
     * Translate a Message.
     *
     * @param Message $message
     * @return mixed
     */
    public function translate(Message $message);

    /**
     * Gets the cost per word for given language pair.
     *
     * @param Language $sourceLangue
     * @param Language $targetLanguage
     * @return mixed
     */
    public function unitPrice(Language $sourceLangue, Language $targetLanguage);
}