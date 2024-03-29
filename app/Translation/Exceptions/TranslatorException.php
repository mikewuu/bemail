<?php


namespace App\Translation\Exceptions;


class TranslatorException extends \Exception
{
    public function report()
    {
        \Log::error('TRANSLATOR_EXCEPTION', [
            'code' => $this->code,
            'msg' => $this->message,
            'exception' => $this
        ]);
    }
}