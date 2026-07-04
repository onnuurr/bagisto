<?php

namespace Webkul\Accounting\Repositories;

use Illuminate\Container\Container;
use Illuminate\Support\Facades\DB;
use Webkul\Accounting\Models\JournalEntry;
use Webkul\Core\Eloquent\Repository;

class JournalEntryRepository extends Repository
{
    /**
     * Create a new repository instance.
     *
     * @return void
     */
    public function __construct(
        protected FiscalYearRepository $fiscalYearRepository,
        Container $container
    ) {
        parent::__construct($container);
    }

    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return 'Webkul\Accounting\Contracts\JournalEntry';
    }

    /**
     * Create a balanced journal entry along with its lines.
     *
     * The `lines` key of `$data` must be an array of entries shaped like:
     * ['account_id' => int, 'debit' => float, 'credit' => float, 'description' => ?string].
     *
     * @throws \Exception when the debit and credit totals of the lines don't balance
     */
    public function create(array $data): JournalEntry
    {
        $lines = $data['lines'] ?? [];

        $exchangeRate = (float) ($data['exchange_rate'] ?? 1);

        $totalDebit = round(array_sum(array_column($lines, 'debit')), 4);

        $totalCredit = round(array_sum(array_column($lines, 'credit')), 4);

        if (! count($lines) || abs($totalDebit - $totalCredit) > 0.0001) {
            throw new \Exception(trans('admin::app.accounting.journal-entries.errors.unbalanced-entry'));
        }

        return DB::transaction(function () use ($data, $lines, $exchangeRate) {
            $fiscalYearId = $data['fiscal_year_id']
                ?? optional($this->fiscalYearRepository->findForDate($data['entry_date']))->id;

            $journalEntry = $this->model->create([
                'fiscal_year_id' => $fiscalYearId,
                'entry_number' => $this->generateEntryNumber(),
                'entry_date' => $data['entry_date'],
                'reference_type' => $data['reference_type'] ?? JournalEntry::REFERENCE_MANUAL,
                'reference_id' => $data['reference_id'] ?? null,
                'description' => $data['description'] ?? null,
                'currency_code' => $data['currency_code'] ?? core()->getBaseCurrencyCode(),
                'exchange_rate' => $exchangeRate,
                'status' => $data['status'] ?? JournalEntry::STATUS_DRAFT,
                'posted_at' => ($data['status'] ?? null) === JournalEntry::STATUS_POSTED ? now() : null,
                'created_by' => $data['created_by'] ?? null,
            ]);

            foreach ($lines as $line) {
                $debit = round((float) ($line['debit'] ?? 0), 4);

                $credit = round((float) ($line['credit'] ?? 0), 4);

                $journalEntry->lines()->create([
                    'account_id' => $line['account_id'],
                    'debit' => $debit,
                    'credit' => $credit,
                    'base_debit' => round($debit * $exchangeRate, 4),
                    'base_credit' => round($credit * $exchangeRate, 4),
                    'description' => $line['description'] ?? null,
                ]);
            }

            return $journalEntry->load('lines');
        });
    }

    /**
     * Mark a draft journal entry as posted.
     */
    public function post(int $id): JournalEntry
    {
        $journalEntry = $this->findOrFail($id);

        if ($journalEntry->status !== JournalEntry::STATUS_DRAFT) {
            throw new \Exception(trans('admin::app.accounting.journal-entries.errors.only-draft-can-be-posted'));
        }

        $journalEntry->update([
            'status' => JournalEntry::STATUS_POSTED,
            'posted_at' => now(),
        ]);

        return $journalEntry;
    }

    /**
     * Void a previously posted journal entry.
     */
    public function void(int $id): JournalEntry
    {
        $journalEntry = $this->findOrFail($id);

        if ($journalEntry->status !== JournalEntry::STATUS_POSTED) {
            throw new \Exception(trans('admin::app.accounting.journal-entries.errors.only-posted-can-be-voided'));
        }

        $journalEntry->update([
            'status' => JournalEntry::STATUS_VOID,
        ]);

        return $journalEntry;
    }

    /**
     * Generate the next sequential entry number, e.g. JE-000001.
     */
    protected function generateEntryNumber(): string
    {
        $lastId = (int) $this->model->query()->max('id');

        return 'JE-'.str_pad((string) ($lastId + 1), 6, '0', STR_PAD_LEFT);
    }
}
