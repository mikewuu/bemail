<?php

namespace App\Payments;

use App\Translation\Message;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Payments\CreditTransaction
 *
 * @mixin \Eloquent
 * @property int $id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property int $add
 * @property int|null $message_receipt_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payments\CreditTransaction whereAdd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payments\CreditTransaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payments\CreditTransaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payments\CreditTransaction whereMessageReceiptId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payments\CreditTransaction whereUpdatedAt($value)
 * @property-read \App\Payments\MessageReceipt|null $messageReceipt
 * @property-read \App\Payments\CreditTransactionType $type
 * @property int $amount
 * @property int $credit_transaction_type_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payments\CreditTransaction whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payments\CreditTransaction whereCreditTransactionTypeId($value)
 */
class CreditTransaction extends Model
{

    /**
     * Mass fillable fields.
     *
     * @var array
     */
    protected $fillable = [
        'amount',
        'credit_transaction_type_id',
        'message_receipt_id'
    ];

    /**
     * Type of transaction.
     * What was the transaction for? These are pre-defined and seeded
     * to minimize potential errors and make changes easier in
     * the future.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {
        return $this->belongsTo(CreditTransactionType::class, 'credit_transaction_type_id', 'id');
    }

    /**
     * MessageReceipt this transaction was for.
     * Could be null, when the transaction wasn't for the
     * payment of a message.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function messageReceipt()
    {
        return $this->belongsTo(MessageReceipt::class, 'message_receipt_id');
    }
}