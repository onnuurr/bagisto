<?php

namespace Webkul\Accounting\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Webkul\Accounting\Contracts\JournalEntry as JournalEntryContract;
use Webkul\Accounting\Database\Factories\JournalEntryFactory;

class JournalEntry extends Model implements JournalEntryContract
{
    use HasFactory;

    /**
     * Journal entry statuses.
     */
    public const STATUS_DRAFT = 'draft';

    public const STATUS_POSTED = 'posted';

    public const STATUS_VOID = 'void';

    /**
     * Reference types.
     */
    public const REFERENCE_MANUAL = 'manual';

    public const REFERENCE_INVOICE = 'invoice';

    public const REFERENCE_REFUND = 'refund';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'accounting_journal_entries';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fiscal_year_id',
        'entry_number',
        'entry_date',
        'reference_type',
        'reference_id',
        'description',
        'currency_code',
        'exchange_rate',
        'status',
        'posted_at',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'entry_date'    => 'date',
        'exchange_rate' => 'decimal:6',
        'posted_at'     => 'datetime',
    ];

    /**
     * Get the fiscal year the entry belongs to.
     */
    public function fiscalYear(): BelongsTo
    {
        return $this->belongsTo(FiscalYearProxy::modelClass(), 'fiscal_year_id');
    }

    /**
     * Get the lines belonging to the journal entry.
     */
    public function lines(): HasMany
    {
        return $this->hasMany(JournalEntryLineProxy::modelClass(), 'journal_entry_id');
    }

    /**
     * Get the total debit amount, in base currency, for the entry.
     */
    public function getTotalBaseDebitAttribute(): float
    {
        return (float) $this->lines->sum('base_debit');
    }

    /**
     * Get the total credit amount, in base currency, for the entry.
     */
    public function getTotalBaseCreditAttribute(): float
    {
        return (float) $this->lines->sum('base_credit');
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): Factory
    {
        return JournalEntryFactory::new();
    }
}
