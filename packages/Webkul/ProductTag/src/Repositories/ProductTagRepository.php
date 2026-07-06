<?php

namespace Webkul\ProductTag\Repositories;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Webkul\Core\Eloquent\Repository;
use Webkul\ProductTag\Contracts\ProductTag;

class ProductTagRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return ProductTag::class;
    }

    /**
     * Create product tag.
     *
     * @return ProductTag
     */
    public function create(array $data)
    {
        if (
            isset($data['locale'])
            && $data['locale'] == 'all'
        ) {
            $model = app()->make($this->model());

            foreach (core()->getAllLocales() as $locale) {
                foreach ($model->translatedAttributes as $attribute) {
                    if (isset($data[$attribute])) {
                        $data[$locale->code][$attribute] = $data[$attribute];
                    }
                }
            }
        }

        $productTag = $this->model->create($data);

        $this->uploadImage($data, $productTag);

        return $productTag;
    }

    /**
     * Update product tag.
     *
     * @param  int  $id
     * @return ProductTag
     */
    public function update(array $data, $id)
    {
        $productTag = $this->find($id);

        $productTag->update($data);

        $this->uploadImage($data, $productTag);

        return $productTag;
    }

    /**
     * Upload the product tag's image.
     *
     * @return void
     */
    public function uploadImage(array $data, $productTag)
    {
        if (isset($data['image'])) {
            foreach ($data['image'] as $imageId => $image) {
                $file = 'image.'.$imageId;

                if (request()->hasFile($file)) {
                    if ($productTag->image) {
                        Storage::delete($productTag->image);
                    }

                    $encoded = image_manager()->read(request()->file($file))->encodeByExtension('webp');

                    $productTag->image = 'product-tag/'.$productTag->id.'/'.Str::random(40).'.webp';

                    Storage::put($productTag->image, (string) $encoded);

                    $productTag->save();
                }
            }
        } else {
            if ($productTag->image) {
                Storage::delete($productTag->image);
            }

            $productTag->image = null;

            $productTag->save();
        }
    }
}
