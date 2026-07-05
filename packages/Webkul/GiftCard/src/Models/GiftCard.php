<?php

namespace Webkul\GiftCard\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Webkul\GiftCard\Contracts\GiftCard as GiftCardContract;
use Webkul\GiftCard\Database\Factories\GiftCardFactory;

class GiftCard extends Model implements GiftCardContract
{
    use HasFactory;

    /**
     * Status constants.
     */
    const STATUS_UNUSED = 'unused';

    const STATUS_USED = 'used';

    const STATUS_EXPIRED = 'expired';

    /**
     * The attributes that are mass assignable.
     *
     * @var string
     */
    protected $table = 'gift_cards';

    /**
     * Fillable property for the model.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'amount',
        'currency',
        'status',
        'expires_at',
        'customer_email',
        'cart_id',
        'order_id',
        'used_at',
        'created_by',
    ];

    /**
     * Cast the attributes.
     *
     * @var array
     */
    protected $casts = [
        'expires_at' => 'datetime',
        'used_at'    => 'datetime',
    ];

    /**
     * Get the histories for the gift card.
     */
    public function histories(): HasMany
    {
        return $this->hasMany(GiftCardHistoryProxy::modelClass());
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): Factory
    {
        return GiftCardFactory::new();
    }
}
