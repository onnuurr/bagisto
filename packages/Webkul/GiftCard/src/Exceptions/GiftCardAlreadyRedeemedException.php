<?php

namespace Webkul\GiftCard\Exceptions;

use RuntimeException;

/**
 * Thrown when a gift card is no longer redeemable (already used, expired, or
 * removed) at the moment an order tries to redeem it.
 */
class GiftCardAlreadyRedeemedException extends RuntimeException {}
