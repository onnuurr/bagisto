<?php

namespace Webkul\PayTR\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Repositories\CartRepository;
use Webkul\PayTR\Payment\PayTR;
use Webkul\Sales\Repositories\InvoiceRepository;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Repositories\OrderTransactionRepository;
use Webkul\Sales\Transformers\OrderResource;
use Webkul\Shop\Http\Controllers\Controller;

class PayTRController extends Controller
{
    /**
     * Payment success status constant.
     */
    public const PAYMENT_SUCCESS = 'success';

    /**
     * How many times to poll for the async notification before giving up
     * on the browser return page.
     */
    protected const NOTIFICATION_POLL_ATTEMPTS = 5;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected CartRepository $cartRepository,
        protected OrderRepository $orderRepository,
        protected OrderTransactionRepository $orderTransactionRepository,
        protected InvoiceRepository $invoiceRepository,
        protected PayTR $payTR,
    ) {}

    /**
     * Request an iFrame token from PayTR and render the embedded checkout page.
     */
    public function redirect()
    {
        if (! $this->payTR->hasValidCredentials()) {
            session()->flash('error', trans('paytr::app.response.provide-credentials'));

            return redirect()->route('shop.checkout.cart.index');
        }

        $cart = Cart::getCart();

        if (! $cart) {
            session()->flash('error', trans('paytr::app.response.cart-not-found'));

            return redirect()->route('shop.checkout.cart.index');
        }

        $paymentData = $this->payTR->getPaymentData($cart);

        try {
            $response = Http::asForm()->post($this->payTR->getTokenUrl(), $paymentData)->json();
        } catch (\Exception $e) {
            report($e);

            session()->flash('error', trans('paytr::app.response.token-request-failed'));

            return redirect()->route('shop.checkout.cart.index');
        }

        if (($response['status'] ?? null) !== 'success') {
            report(new \Exception('PayTR get-token failed: '.json_encode($response)));

            session()->flash('error', trans('paytr::app.response.token-request-failed'));

            return redirect()->route('shop.checkout.cart.index');
        }

        return view('paytr::checkout.redirect', [
            'iframeUrl' => 'https://www.paytr.com/odeme/guvenli/'.$response['token'],
        ]);
    }

    /**
     * Handle the server-to-server payment notification (merchant_notify_url).
     *
     * This is the only source of truth for marking a payment successful — it
     * must be configured as the "Notification URL" in the PayTR merchant
     * panel, it carries no user session, and PayTR requires the literal body
     * "OK" in response or it will keep retrying the notification.
     */
    public function notify(Request $request)
    {
        $data = $request->all();

        if (! $this->payTR->verifyNotificationHash($data)) {
            report(new \Exception('PayTR notification hash mismatch: '.json_encode($data)));

            return response('PAYTR notification failed: bad hash', 400);
        }

        $cartId = $this->payTR->getCartIdFromMerchantOid($data['merchant_oid'] ?? '');

        if (
            ! $cartId
            || ($data['status'] ?? null) !== self::PAYMENT_SUCCESS
        ) {
            return response('OK');
        }

        if ($this->orderRepository->findOneWhere(['cart_id' => $cartId])) {
            return response('OK');
        }

        $cart = $this->cartRepository->find($cartId);

        if (! $cart || ! $cart->is_active) {
            return response('OK');
        }

        try {
            Cart::setCart($cart);

            Cart::collectTotals();

            $orderData = (new OrderResource($cart))->jsonSerialize();

            $orderData['payment']['additional'] = [
                'paytr_merchant_oid' => $data['merchant_oid'] ?? '',
                'paytr_total_amount' => $data['total_amount'] ?? '',
                'paytr_payment_type' => $data['payment_type'] ?? '',
            ];

            $order = $this->orderRepository->create($orderData);

            $this->orderRepository->update(['status' => 'processing'], $order->id);

            if ($order->canInvoice()) {
                $invoice = $this->invoiceRepository->create($this->prepareInvoiceData($order));

                $this->orderTransactionRepository->create([
                    'transaction_id' => $data['merchant_oid'] ?? '',
                    'status' => self::PAYMENT_SUCCESS,
                    'type' => $order->payment->method,
                    'payment_method' => $order->payment->method,
                    'order_id' => $order->id,
                    'invoice_id' => $invoice->id,
                    'amount' => $order->base_grand_total,
                    'data' => json_encode($data),
                ]);
            }

            Cart::deActivateCart();
        } catch (\Exception $e) {
            report($e);
        }

        return response('OK');
    }

    /**
     * Handle the browser return (merchant_ok_url). PayTR does not sign this
     * redirect, so it only checks whether the async notify() above already
     * created the order for the current session's cart — it never creates
     * the order itself.
     */
    public function ok()
    {
        $cart = Cart::getCart();

        if (! $cart) {
            return redirect()->route('shop.checkout.cart.index');
        }

        $order = null;

        for ($attempt = 0; $attempt < self::NOTIFICATION_POLL_ATTEMPTS; $attempt++) {
            $order = $this->orderRepository->findOneWhere(['cart_id' => $cart->id]);

            if ($order) {
                break;
            }

            usleep(500000);
        }

        if (! $order) {
            session()->flash('info', trans('paytr::app.response.payment-processing'));

            return redirect()->route('shop.checkout.cart.index');
        }

        session()->flash('order_id', $order->id);

        session()->flash('success', trans('paytr::app.response.payment-success'));

        return redirect()->route('shop.checkout.onepage.success');
    }

    /**
     * Handle the browser return for a failed/cancelled payment (merchant_fail_url).
     */
    public function fail()
    {
        session()->flash('error', trans('paytr::app.response.payment-failed'));

        return redirect()->route('shop.checkout.cart.index');
    }

    /**
     * Prepare invoice data.
     *
     * @param  object  $order
     * @return array
     */
    protected function prepareInvoiceData($order)
    {
        $invoiceData = ['order_id' => $order->id];

        foreach ($order->items as $item) {
            $invoiceData['invoice']['items'][$item->id] = $item->qty_to_invoice;
        }

        return $invoiceData;
    }
}
