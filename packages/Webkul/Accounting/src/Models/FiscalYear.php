<?php

namespace Webkul\Accounting\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Webkul\Accounting\Contracts\FiscalYear as FiscalYearContract;
use Webkul\Accounting\Database\Factories\FiscalYearFactory;

class FiscalYear extends Model implements FiscalYearContract
{
    use HasFactory;

    /**
     * Fiscal year statuses.
     */
    public const STATUS_OPEN = 'open';

    public const STATUS_CLOSED = 'closed';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'accounting_fiscal_years';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'start_date',
        'end_date',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Get the journal entries booked in this fiscal year.
     */
    public function journalEntries(): HasMany
    {
        return $this->hasMany(JournalEntryProxy::modelClass(), 'fiscal_year_id');
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): Factory
    {
        return FiscalYearFactory::new();
    }
}
