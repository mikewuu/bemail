<?php

namespace App\Translation\Order;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Translation\Order\OrderStatus
 *
 * @property int $id
 * @property string $description
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Order\OrderStatus whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\Order\OrderStatus whereId($value)
 * @mixin \Eloquent
 */
class OrderStatus extends Model
{

    /**
     * No created_at/updated_at columns.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Available.
     *
     * Waiting for translator to take the order.
     *
     * @return OrderStatus
     */
    public static function available()
    {
        return static::where('description', 'available')->first();
    }

    /**
     * Pending.
     *
     * Work has begun on translating the Message.
     *
     * @return mixed
     */
    public static function pending()
    {
        return static::where('description', 'pending')->first();
    }

    /**
     * Complete.
     *
     * Translation is complete and approved.
     *
     * @return mixed
     */
    public static function complete()
    {
        return static::where('description', 'complete')->first();
    }

    /**
     * Cancelled.
     *
     * Translation is cancelled before being completed.
     *
     * @return mixed
     */
    public static function cancelled()
    {
        return static::where('description', 'cancelled')->first();
    }

    /**
     * Error
     *
     * Something very bad happened and the order is not: in
     * the queue, being currently translated, ever going
     * to be completed, and able to be cancelled.
     *
     * @return mixed
     */
    public static function error()
    {
        return static::where('description', 'error')->first();
    }

    /**
     * Whether Order is open for translators to start.
     *
     * @return bool
     */
    public function isAvailable()
    {
        return $this->id == OrderStatus::available()->id;
    }

    /**
     * Whether a translator has started working on order.
     *
     * @return bool
     */
    public function isPending()
    {
        return $this->id == OrderStatus::pending()->id;
    }

    /**
     * Whether translation is complete.
     *
     * @return bool
     */
    public function isComplete()
    {
        return $this->id == OrderStatus::complete()->id;
    }

    /**
     * Whether order has been cancelled.
     *
     * @return bool
     */
    public function isCancelled()
    {
        return $this->id == OrderStatus::cancelled()->id;
    }

    /**
     * Whether Order has an error.
     *
     * @return bool
     */
    public function isError()
    {
        return $this->id == OrderStatus::error()->id;
    }

}
