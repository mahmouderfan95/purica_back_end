<?php
namespace App\Repositories\Front;
use App\Models\ProductVariants;

class ProductVariantRepository
{
    public function getVariantPrice($request)
    {
        return $this->getModel()::query()
            ->where('product_id', $request->product_id)
            ->where('sku', $request->sku)
            ->first();
    }
    private function getModel() : ProductVariants
    {
        return new ProductVariants();
    }
}
