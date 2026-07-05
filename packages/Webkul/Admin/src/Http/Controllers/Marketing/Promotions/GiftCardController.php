<?php

namespace Webkul\Admin\Http\Controllers\Marketing\Promotions;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Webkul\Admin\DataGrids\Marketing\Promotions\GiftCardDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Admin\Http\Requests\MassDestroyRequest;
use Webkul\GiftCard\Models\GiftCard;
use Webkul\GiftCard\Models\GiftCardHistory;
use Webkul\GiftCard\Repositories\GiftCardHistoryRepository;
use Webkul\GiftCard\Repositories\GiftCardRepository;

class GiftCardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected GiftCardRepository $giftCardRepository,
        protected GiftCardHistoryRepository $giftCardHistoryRepository
    ) {}

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        if (request()->ajax()) {
            return datagrid(GiftCardDataGrid::class)->process();
        }

        return view('admin::marketing.promotions.gift-cards.index');
    }

    /**
     * Store one or many newly generated gift cards in storage.
     */
    public function store(): JsonResponse
    {
        $data = $this->validate(request(), [
            'code'           => ['nullable', 'string', 'max:255', 'unique:gift_cards,code'],
            'amount'         => ['required', 'numeric', 'min:0.01'],
            'currency'       => ['required', 'string', 'exists:currencies,code'],
            'quantity'       => ['nullable', 'integer', 'min:1', 'max:100'],
            'customer_email' => ['nullable', 'email'],
            'expires_at'     => ['nullable', 'date'],
        ]);

        $quantity = $data['quantity'] ?? 1;

        Event::dispatch('marketing.promotions.gift_cards.create.before');

        for ($i = 0; $i < $quantity; $i++) {
            $giftCard = $this->giftCardRepository->create([
                'code'           => $quantity > 1 || empty($data['code']) ? $this->generateUniqueCode() : $data['code'],
                'amount'         => $data['amount'],
                'currency'       => $data['currency'],
                'status'         => GiftCard::STATUS_UNUSED,
                'customer_email' => $data['customer_email'] ?? null,
                'expires_at'     => $data['expires_at'] ?? null,
                'created_by'     => auth()->guard('admin')->id(),
            ]);

            $this->giftCardHistoryRepository->create([
                'gift_card_id' => $giftCard->id,
                'action'       => GiftCardHistory::ACTION_CREATED,
            ]);
        }

        Event::dispatch('marketing.promotions.gift_cards.create.after');

        return new JsonResponse([
            'message' => trans('admin::app.marketing.promotions.gift-cards.create.success'),
        ]);
    }

    /**
     * Generate a code that isn't already in use.
     */
    protected function generateUniqueCode(): string
    {
        do {
            $code = strtoupper(implode('-', [
                Str::random(4),
                Str::random(4),
                Str::random(4),
            ]));
        } while ($this->giftCardRepository->findOneWhere(['code' => $code]));

        return $code;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(): JsonResponse
    {
        $id = request()->id;

        $data = $this->validate(request(), [
            'code'           => ['required', 'string', 'max:255', 'unique:gift_cards,code,'.$id],
            'amount'         => ['required', 'numeric', 'min:0.01'],
            'currency'       => ['required', 'string', 'exists:currencies,code'],
            'status'         => ['required', 'in:'.implode(',', [GiftCard::STATUS_UNUSED, GiftCard::STATUS_USED, GiftCard::STATUS_EXPIRED])],
            'customer_email' => ['nullable', 'email'],
            'expires_at'     => ['nullable', 'date'],
        ]);

        Event::dispatch('marketing.promotions.gift_cards.update.before', $id);

        $giftCard = $this->giftCardRepository->update($data, $id);

        Event::dispatch('marketing.promotions.gift_cards.update.after', $giftCard);

        return new JsonResponse([
            'message' => trans('admin::app.marketing.promotions.gift-cards.edit.success'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $giftCard = $this->giftCardRepository->findOrFail($id);

        if ($giftCard->status == GiftCard::STATUS_USED) {
            return new JsonResponse([
                'message' => trans('admin::app.marketing.promotions.gift-cards.edit.delete-failed'),
            ], 400);
        }

        Event::dispatch('marketing.promotions.gift_cards.delete.before', $id);

        $this->giftCardRepository->delete($id);

        Event::dispatch('marketing.promotions.gift_cards.delete.after', $id);

        return new JsonResponse([
            'message' => trans('admin::app.marketing.promotions.gift-cards.edit.delete-success'),
        ]);
    }

    /**
     * Mass delete the gift cards.
     */
    public function massDestroy(MassDestroyRequest $massDestroyRequest): JsonResponse
    {
        foreach ($massDestroyRequest->input('indices') as $id) {
            $giftCard = $this->giftCardRepository->find($id);

            if (! $giftCard || $giftCard->status == GiftCard::STATUS_USED) {
                continue;
            }

            Event::dispatch('marketing.promotions.gift_cards.delete.before', $id);

            $this->giftCardRepository->delete($id);

            Event::dispatch('marketing.promotions.gift_cards.delete.after', $id);
        }

        return new JsonResponse([
            'message' => trans('admin::app.marketing.promotions.gift-cards.index.datagrid.mass-delete-success'),
        ]);
    }
}
