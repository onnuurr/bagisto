<?php

namespace Webkul\GiftCard\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\GiftCard\Contracts\GiftCardHistory as GiftCardHistoryContract;

class GiftCardHistory extends Model implements GiftCardHistoryContract
{
    /**
     * Action constants.
     */
    const ACTION_CREATED = 'created';

    const ACTION_APPLIED = 'applied';

    const ACTION_REFUNDED = 'refunded';

    const ACTION_EXPIRED = 'expired';

    /**
     * The attributes that are mass assignable.
     *
     * @var string
     */
    protected $table = 'gift_card_histories';

    /**
     * Fillable property for the model.
     *
     * @var array
     */
    protected $fillable = [
        'gift_card_id',
        'action',
        'order_id',
        'note',
    ];

    /**
     * Get the gift card that owns the history.
     */
    public function gift_card(): BelongsTo
    {
        return $this->belongsTo(GiftCardProxy::modelClass());
    }
}
