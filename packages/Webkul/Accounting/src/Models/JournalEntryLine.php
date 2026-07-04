<?php

namespace Webkul\Accounting\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\Accounting\Contracts\JournalEntryLine as JournalEntryLineContract;
use Webkul\Accounting\Database\Factories\JournalEntryLineFactory;

class JournalEntryLine extends Model implements JournalEntryLineContract
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'accounting_journal_entry_lines';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'journal_entry_id',
        'account_id',
        'debit',
        'credit',
        'base_debit',
        'base_credit',
        'description',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'debit'       => 'decimal:4',
        'credit'      => 'decimal:4',
        'base_debit'  => 'decimal:4',
        'base_credit' => 'decimal:4',
    ];

    /**
     * Get the journal entry the line belongs to.
     */
    public function journalEntry(): BelongsTo
    {
        return $this->belongsTo(JournalEntryProxy::modelClass(), 'journal_entry_id');
    }

    /**
     * Get the account the line is posted against.
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(AccountProxy::modelClass(), 'account_id');
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): Factory
    {
        return JournalEntryLineFactory::new();
    }
}
