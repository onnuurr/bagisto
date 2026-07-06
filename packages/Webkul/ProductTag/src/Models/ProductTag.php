<?php

namespace Webkul\ProductTag\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Webkul\Core\Eloquent\TranslatableModel;
use Webkul\Product\Models\ProductProxy;
use Webkul\ProductTag\Contracts\ProductTag as ProductTagContract;
use Webkul\ProductTag\Database\Factories\ProductTagFactory;

class ProductTag extends TranslatableModel implements ProductTagContract
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product_tags';

    /**
     * Translated attributes.
     *
     * @var array
     */
    public $translatedAttributes = [
        'name',
    ];

    /**
     * Fillable.
     *
     * @var array
     */
    protected $fillable = [
        'image',
    ];

    /**
     * Eager loading.
     *
     * @var array
     */
    protected $with = ['translations'];

    /**
     * Appends.
     *
     * @var array
     */
    protected $appends = ['image_url'];

    /**
     * The products that belong to the tag.
     */
    public function products(): HasMany
    {
        return $this->hasMany(ProductProxy::modelClass(), 'product_tag_id');
    }

    /**
     * Get image url attribute.
     *
     * @return string|null
     */
    public function getImageUrlAttribute()
    {
        if (! $this->image) {
            return null;
        }

        return Storage::url($this->image);
    }

    /**
     * Use fallback for product tag.
     */
    protected function useFallback(): bool
    {
        return true;
    }

    /**
     * Get fallback locale for product tag.
     */
    protected function getFallbackLocale(?string $locale = null): ?string
    {
        if ($fallback = core()->getDefaultLocaleCodeFromDefaultChannel()) {
            return $fallback;
        }

        return parent::getFallbackLocale();
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): Factory
    {
        return ProductTagFactory::new();
    }
}
