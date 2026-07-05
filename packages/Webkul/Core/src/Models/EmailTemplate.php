<?php

namespace Webkul\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Core\Contracts\EmailTemplate as EmailTemplateContract;

class EmailTemplate extends Model implements EmailTemplateContract
{
    /**
     * Define the table name for the Model
     *
     * @var string
     */
    protected $table = 'core_email_templates';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'name',
        'subject',
        'content',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'status' => 'boolean',
    ];
}
