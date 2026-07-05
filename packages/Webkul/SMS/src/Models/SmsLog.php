<?php

namespace Webkul\SMS\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webkul\SMS\Contracts\SmsLog as SmsLogContract;
use Webkul\SMS\Database\Factories\SmsLogFactory;

class SmsLog extends Model implements SmsLogContract
{
    use HasFactory;

    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'sms_logs';

    /**
     * Fillable.
     *
     * @var array
     */
    protected $fillable = [
        'gateway',
        'event',
        'recipient',
        'message',
        'status',
        'response',
    ];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): Factory
    {
        return SmsLogFactory::new();
    }
}
