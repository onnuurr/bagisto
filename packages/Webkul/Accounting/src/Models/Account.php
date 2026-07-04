<?php

namespace Webkul\Accounting\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Webkul\Accounting\Contracts\Account as AccountContract;
use Webkul\Accounting\Database\Factories\AccountFactory;

class Account extends Model implements AccountContract
{
    use HasFactory;

    /**
     * Account types.
     */
    public const TYPE_ASSET = 'asset';

    public const TYPE_LIABILITY = 'liability';

    public const TYPE_EQUITY = 'equity';

    public const TYPE_REVENUE = 'revenue';

    public const TYPE_EXPENSE = 'expense';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'accounting_accounts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_id',
        'code',
        'name',
        'type',
        'description',
        'opening_balance',
        'is_system',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'opening_balance' => 'decimal:4',
        'is_system' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Account types that carry a normal debit balance.
     *
     * @var string[]
     */
    public static array $debitBalanceTypes = [
        self::TYPE_ASSET,
        self::TYPE_EXPENSE,
    ];

    /**
     * Get the parent account.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(AccountProxy::modelClass(), 'parent_id');
    }

    /**
     * Get the child accounts.
     */
    public function children(): HasMany
    {
        return $this->hasMany(AccountProxy::modelClass(), 'parent_id');
    }

    /**
     * Get the journal entry lines posted against this account.
     */
    public function journalEntryLines(): HasMany
    {
        return $this->hasMany(JournalEntryLineProxy::modelClass(), 'account_id');
    }

    /**
     * Determine whether the account's normal balance is a debit balance.
     */
    public function hasDebitNormalBalance(): bool
    {
        return in_array($this->type, self::$debitBalanceTypes);
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): Factory
    {
        return AccountFactory::new();
    }
}
