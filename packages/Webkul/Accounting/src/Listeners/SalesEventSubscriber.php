<?php

namespace Webkul\Accounting\Listeners;

use Illuminate\Events\Dispatcher;
use Webkul\Accounting\Models\JournalEntry;
use Webkul\Accounting\Repositories\JournalEntryRepository;
use Webkul\Accounting\Repositories\SettingRepository;
use Webkul\Sales\Contracts\Invoice;
use Webkul\Sales\Contracts\Refund;

class SalesEventSubscriber
{
    /**
     * Create a new subscriber instance.
     *
     * @return void
     */
    public function __construct(
        protected JournalEntryRepository $journalEntryRepository,
        protected SettingRepository $settingRepository
    ) {}

    /**
     * Register the listeners for the subscriber.
     */
    public function subscribe(Dispatcher $events): void
    {
        $events->listen('sales.invoice.save.after', [self::class, 'handleInvoiceSaved']);

        $events->listen('sales.refund.save.after', [self::class, 'handleRefundSaved']);
    }

    /**
     * Post the revenue recognition (and, when already paid, the payment received) entries for an invoice.
     *
     * Any failure here is swallowed and reported rather than thrown, so that a missing account
     * mapping or accounting bug can never roll back the invoice that triggered it.
     */
    public function handleInvoiceSaved(Invoice $invoice): void
    {
        try {
            $accountsReceivable = (int) $this->settingRepository->get('accounts_receivable_account');

            $salesRevenue = (int) $this->settingRepository->get('sales_revenue_account');

            if (! $accountsReceivable || ! $salesRevenue) {
                return;
            }

            $lines = [
                $this->line($accountsReceivable, (float) $invoice->base_grand_total, 0),
            ];

            $lines[] = $this->line($salesRevenue, 0, (float) $invoice->base_sub_total);

            if ((float) $invoice->base_shipping_amount) {
                if ($shippingRevenue = (int) $this->settingRepository->get('shipping_revenue_account')) {
                    $lines[] = $this->line($shippingRevenue, 0, (float) $invoice->base_shipping_amount);
                }
            }

            if ((float) $invoice->base_tax_amount) {
                if ($taxPayable = (int) $this->settingRepository->get('tax_payable_account')) {
                    $lines[] = $this->line($taxPayable, 0, (float) $invoice->base_tax_amount);
                }
            }

            if ((float) $invoice->base_discount_amount) {
                if ($salesDiscount = (int) $this->settingRepository->get('sales_discount_account')) {
                    $lines[] = $this->line($salesDiscount, (float) $invoice->base_discount_amount, 0);
                }
            }

            $this->journalEntryRepository->create([
                'entry_date'     => $invoice->created_at ?? now(),
                'reference_type' => JournalEntry::REFERENCE_INVOICE,
                'reference_id'   => $invoice->id,
                'description'    => trans('admin::app.accounting.journal-entries.auto-post.invoice', ['invoice' => $invoice->increment_id]),
                'currency_code'  => $invoice->base_currency_code ?: core()->getBaseCurrencyCode(),
                'status'         => JournalEntry::STATUS_POSTED,
                'lines'          => $lines,
            ]);

            if (
                $invoice->state === 'paid'
                && ($cashBank = (int) $this->settingRepository->get('cash_bank_account'))
            ) {
                $this->journalEntryRepository->create([
                    'entry_date'     => $invoice->created_at ?? now(),
                    'reference_type' => JournalEntry::REFERENCE_INVOICE,
                    'reference_id'   => $invoice->id,
                    'description'    => trans('admin::app.accounting.journal-entries.auto-post.payment', ['invoice' => $invoice->increment_id]),
                    'currency_code'  => $invoice->base_currency_code ?: core()->getBaseCurrencyCode(),
                    'status'         => JournalEntry::STATUS_POSTED,
                    'lines'          => [
                        $this->line($cashBank, (float) $invoice->base_grand_total, 0),
                        $this->line($accountsReceivable, 0, (float) $invoice->base_grand_total),
                    ],
                ]);
            }
        } catch (\Exception $e) {
            report($e);
        }
    }

    /**
     * Post the reversal entry for a refund.
     *
     * Failures are swallowed and reported for the same reason as {@see handleInvoiceSaved()}.
     */
    public function handleRefundSaved(Refund $refund): void
    {
        try {
            $cashBank = (int) $this->settingRepository->get('cash_bank_account');

            $salesRevenue = (int) $this->settingRepository->get('sales_revenue_account');

            if (! $cashBank || ! $salesRevenue) {
                return;
            }

            $lines = [
                $this->line($salesRevenue, (float) $refund->base_sub_total, 0),
            ];

            if ((float) $refund->base_shipping_amount) {
                if ($shippingRevenue = (int) $this->settingRepository->get('shipping_revenue_account')) {
                    $lines[] = $this->line($shippingRevenue, (float) $refund->base_shipping_amount, 0);
                }
            }

            if ((float) $refund->base_tax_amount) {
                if ($taxPayable = (int) $this->settingRepository->get('tax_payable_account')) {
                    $lines[] = $this->line($taxPayable, (float) $refund->base_tax_amount, 0);
                }
            }

            if ((float) $refund->base_discount_amount) {
                if ($salesDiscount = (int) $this->settingRepository->get('sales_discount_account')) {
                    $lines[] = $this->line($salesDiscount, 0, (float) $refund->base_discount_amount);
                }
            }

            $lines[] = $this->line($cashBank, 0, (float) $refund->base_grand_total);

            $this->journalEntryRepository->create([
                'entry_date'     => $refund->created_at ?? now(),
                'reference_type' => JournalEntry::REFERENCE_REFUND,
                'reference_id'   => $refund->id,
                'description'    => trans('admin::app.accounting.journal-entries.auto-post.refund', ['refund' => $refund->id]),
                'currency_code'  => $refund->base_currency_code ?: core()->getBaseCurrencyCode(),
                'status'         => JournalEntry::STATUS_POSTED,
                'lines'          => $lines,
            ]);
        } catch (\Exception $e) {
            report($e);
        }
    }

    /**
     * Build a single journal entry line array.
     */
    protected function line(int $accountId, float $debit, float $credit): array
    {
        return [
            'account_id' => $accountId,
            'debit'      => round($debit, 4),
            'credit'     => round($credit, 4),
        ];
    }
}
