<?php

namespace Webkul\Iyzico\Http\Controllers;

use Illuminate\Http\Request;
use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Repositories\CartRepository;
use Webkul\Iyzico\Payment\Iyzico;
use Webkul\Sales\Repositories\InvoiceRepository;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Repositories\OrderTransactionRepository;
use Webkul\Sales\Transformers\OrderResource;
use Webkul\Shop\Http\Controllers\Controller;

class IyzicoController extends Controller
{
    /**
     * iyzico's own payment status constant for a completed payment.
     */
    public const PAYMENT_SUCCESS = 'SUCCESS';

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
        protected Iyzico $iyzico,
    ) {}

    /**
     * Initialize the iyzico checkout form and render the embedded page.
     */
    public function redirect()
    {
        if (! $this->iyzico->hasValidCredentials()) {
            session()->flash('error', trans('iyzico::app.response.provide-credentials'));

            return redirect()->route('shop.checkout.cart.index');
        }

        $cart = Cart::getCart();

        if (! $cart) {
            session()->flash('error', trans('iyzico::app.response.cart-not-found'));

            return redirect()->route('shop.checkout.cart.index');
        }

        try {
            $checkoutFormInitialize = $this->iyzico->initializeCheckoutForm($cart);
        } catch (\Exception $e) {
            report($e);

            session()->flash('error', trans('iyzico::app.response.initialize-failed'));

            return redirect()->route('shop.checkout.cart.index');
        }

        if (
            $checkoutFormInitialize->getStatus() !== 'success'
            || ! $this->iyzico->verifyInitializeSignature($checkoutFormInitialize)
        ) {
            report(new \Exception('Iyzico checkout form initialize failed: '.json_encode([
                'status' => $checkoutFormInitialize->getStatus(),
                'errorMessage' => $checkoutFormInitialize->getErrorMessage(),
            ])));

            session()->flash('error', $checkoutFormInitialize->getErrorMessage() ?: trans('iyzico::app.response.initialize-failed'));

            return redirect()->route('shop.checkout.cart.index');
        }

        return view('iyzico::checkout.redirect', [
            'checkoutFormContent' => $checkoutFormInitialize->getCheckoutFormContent(),
        ]);
    }

    /**
     * Handle the browser callback (callbackUrl). The token is looked up
     * fresh against iyzico's API — the callback's own POST body is never
     * trusted for the payment result, only for which token to retrieve.
     */
    public function callback(Request $request)
    {
        $token = $request->input('token');

        if (! $token) {
            session()->flash('error', trans('iyzico::app.response.payment-failed'));

            return redirect()->route('shop.checkout.cart.index');
        }

        try {
            $checkoutForm = $this->iyzico->retrieveCheckoutForm($token);
        } catch (\Exception $e) {
            report($e);

            session()->flash('error', trans('iyzico::app.response.payment-failed'));

            return redirect()->route('shop.checkout.cart.index');
        }

        if (
            $checkoutForm->getStatus() !== 'success'
            || ! $this->iyzico->verifyRetrieveSignature($checkoutForm)
        ) {
            report(new \Exception('Iyzico checkout form retrieve hash mismatch or failure: '.json_encode([
                'status' => $checkoutForm->getStatus(),
            ])));

            session()->flash('error', trans('iyzico::app.response.hash-mismatch'));

            return redirect()->route('shop.checkout.cart.index');
        }

        $cartId = $this->iyzico->getCartIdFromBasketId((string) $checkoutForm->getBasketId());

        if (! $cartId) {
            session()->flash('error', trans('iyzico::app.response.invalid-transaction'));

            return redirect()->route('shop.checkout.cart.index');
        }

        if ($checkoutForm->getPaymentStatus() !== self::PAYMENT_SUCCESS) {
            session()->flash('error', trans('iyzico::app.response.payment-failed'));

            return redirect()->route('shop.checkout.cart.index');
        }

        $existingOrder = $this->orderRepository->findOneWhere(['cart_id' => $cartId]);

        if ($existingOrder) {
            session()->flash('order_id', $existingOrder->id);

            session()->flash('success', trans('iyzico::app.response.payment-success'));

            return redirect()->route('shop.checkout.onepage.success');
        }

        $cart = $this->cartRepository->find($cartId);

        if (! $cart || ! $cart->is_active) {
            session()->flash('error', trans('iyzico::app.response.cart-not-found'));

            return redirect()->route('shop.checkout.cart.index');
        }

        try {
            Cart::setCart($cart);

            Cart::collectTotals();

            $orderData = (new OrderResource($cart))->jsonSerialize();

            $orderData['payment']['additional'] = [
                'iyzico_payment_id' => $checkoutForm->getPaymentId(),
                'iyzico_token' => $checkoutForm->getToken(),
            ];

            $order = $this->orderRepository->create($orderData);

            $this->orderRepository->update(['status' => 'processing'], $order->id);

            if ($order->canInvoice()) {
                $invoice = $this->invoiceRepository->create($this->prepareInvoiceData($order));

                $this->orderTransactionRepository->create([
                    'transaction_id' => $checkoutForm->getPaymentId(),
                    'status' => 'success',
                    'type' => $order->payment->method,
                    'payment_method' => $order->payment->method,
                    'order_id' => $order->id,
                    'invoice_id' => $invoice->id,
                    'amount' => $checkoutForm->getPaidPrice(),
                    'data' => json_encode([
                        'paymentId' => $checkoutForm->getPaymentId(),
                        'paymentStatus' => $checkoutForm->getPaymentStatus(),
                        'currency' => $checkoutForm->getCurrency(),
                    ]),
                ]);
            }

            Cart::deActivateCart();
        } catch (\Exception $e) {
            report($e);

            session()->flash('error', trans('iyzico::app.response.order-creation-failed'));

            return redirect()->route('shop.checkout.cart.index');
        }

        session()->flash('order_id', $order->id);

        session()->flash('success', trans('iyzico::app.response.payment-success'));

        return redirect()->route('shop.checkout.onepage.success');
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
