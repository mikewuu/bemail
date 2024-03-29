<?php

namespace App;

use App\Payment\Credit\CreditTransaction;
use App\Payment\Plan;
use App\Translation\Factories\MessageFactory;
use App\Translation\Message;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Cashier\Billable;

/**
 * App\User
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Payment\Credit\CreditTransaction[] $creditTransactions
 * @property-read \App\Language $defaultLanguage
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Error[] $errors
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Translation\Message[] $messages
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Translation\Recipient[] $recipients
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Cashier\Subscription[] $subscriptions
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use Notifiable, Billable;

    /**
     * Mass-assignable Fields
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'credits',
        'language_id'
    ];

    /**
     * Hidden Fields
     *
     * These properties don't show up when you
     * retrieve the model's array. ie. When
     * you call User::all().
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * User's default language
     *
     * We assume this is the language that
     * the User would want received mail
     * to be translated into.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function defaultLanguage()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }

    /**
     * Message(s) sent by User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    /**
     * PostmarkInboundMailRecipient(s) that User has sent Message(s) to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function recipients()
    {
        return $this->hasManyThrough('App\Translation\Recipient', 'App\Translation\Message', 'user_id', 'message_id');
    }

    /**
     * Every credit transaction that adjusted User credits.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function creditTransactions()
    {
        return $this->hasMany(CreditTransaction::class);
    }

    /**
     * User errors.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function errors()
    {
        return $this->morphMany(Error::class, 'errorable');
    }

    /**
     * Payment plan.
     *
     * @return Plan
     */
    public function plan()
    {
        return Plan::forUser($this);
    }

    /**
     * Credits that can be used for payment.
     *
     * @param null $amount
     * @return int
     */
    public function credits($amount = null)
    {
        if (is_null($amount)) {
            return $this->credits;
        }
        $this->credits = $amount;
        $this->save();
    }

    /**
     * Make a new Message.
     *
     * @return MessageFactory
     */
    public function newMessage()
    {
        return MessageFactory::newMessageFromUser($this);
    }

    /**
     * Record a new transaction.
     *
     * @return CreditTransaction
     */
    public function recordNewCreditTransaction()
    {
        return CreditTransaction::newForUser($this);
    }

    /**
     * Record new error.
     *
     * @return Error
     */
    public function newError()
    {
        return Error::newForUser($this);
    }


}
