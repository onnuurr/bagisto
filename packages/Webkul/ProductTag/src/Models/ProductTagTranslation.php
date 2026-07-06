<?php

namespace Webkul\ProductTag\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webkul\ProductTag\Contracts\ProductTagTranslation as ProductTagTranslationContract;
use Webkul\ProductTag\Database\Factories\ProductTagTranslationFactory;

class ProductTagTranslation extends Model implements ProductTagTranslationContract
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product_tag_translations';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'locale',
        'product_tag_id',
    ];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): Factory
    {
        return ProductTagTranslationFactory::new();
    }
}
