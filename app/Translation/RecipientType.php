<?php

namespace App\Translation;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Translation\RecipientType
 *
 * @property int $id
 * @property string $name
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\RecipientType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Translation\RecipientType whereName($value)
 * @mixin \Eloquent
 */
class RecipientType extends Model
{
    /**
     * No timestamp fields.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Mass-fillable fields.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];
    
    /**
     * Retrieves the model for 'standard' type.
     *
     * @return RecipientType
     */
    public static function standard()
    {
        return static::where('name', 'standard')->first();
    }

    /**
     * Retrieves the model for 'cc' type.
     *
     * @return RecipientType
     */
    public static function cc()
    {
        return static::where('name', 'cc')->first();
    }

    /**
     * Retrieves the model for 'bcc' type.
     *
     * @return RecipientType
     */
    public static function bcc()
    {
        return static::where('name', 'bcc')->first();
    }
}
