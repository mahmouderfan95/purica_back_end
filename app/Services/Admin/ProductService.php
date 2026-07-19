<?php
namespace App\Services\Admin;
use App\Helper\FileUpload;
use App\Http\Resources\Admin\Products\ProductCollection;
use App\Http\Resources\Admin\Products\ProductResource;
use App\Models\ProductVariants;
use App\Repositories\Admin\ProductRepository;
use App\Traits\ApiResponseAble;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProductService
{
    use ApiResponseAble,FileUpload;
    public function __construct(public ProductRepository $productRepository){}
    public function index($request) : JsonResponse
    {
        try{
            $products = $this->productRepository->getProducts($request);
            $result = $request->is_paginate == true ? ProductCollection::make($products) : ProductResource::collection($products);
            if(!$products->isEmpty()){
                return $this->ApiSuccessResponse($result);
            }
            return $this->listResponse([]);
        }catch (\Exception $exception){
            Log::error('error of get all products' . $exception->getMessage());
            return $this->ApiErrorResponse([],'something went wrong');
        }
    }
    public function store($request) : JsonResponse
    {
        try{
            DB::beginTransaction();
            $data = $request->validated();
            // Handle image upload if provided
            if ($request->hasFile('image')) {
                $data['image'] = $this->save_file($request->file('image'),'products'); // Store in 'storage/app/public/packages'
            }
            // Create the package
            $product = $this->productRepository->store([
                'name' => $data['name'],
                'image' => $data['image'],
                'description' => $data['description'],
                'slug' => Str::slug($data['name']['en']),
                'status' => $data['status'],
                'price' => $data['price'],
                'price_after_discount' => $data['price_after_discount'] ?? null,
                'available_quantity' => $data['available_quantity'],
                'category_id' => $data['category_id'],
                'brand_id' => $data['brand_id'],
                'discount_end_at' => $data['discount_end_at'],
            ]);
            if ($request->hasFile('attachments')) {
                $uploads = $this->uploadAttachments('attachments', 'uploads/products/media');

                if (!empty($uploads)) {
                    foreach ($uploads as &$upload) {
                        $upload['status'] = 'active';
                    }
                    $product->media()->createMany($uploads);
                }
            }
            if (!empty($data['attribute_option_ids'])) {
                $product->attributeOptions()->attach($data['attribute_option_ids']);
            }
            if (!empty($data['variants'])) {
                foreach ($data['variants'] as $variant) {
                    $sku = $variant['sku'] ?? null;
                    if (empty($sku)) {
                        $sku = $this->generateUniqueSku($product->id);
                    } else {
                        $exists = ProductVariants::query()->where('sku', $sku)->exists();
                        if ($exists) {
                            throw new \Exception("SKU '{$sku}' already exists.");
                        }
                    }
                    $product->variants()->create([
                        'sku'                 => $sku,
                        'price'               => $variant['price'],
                        'available_quantity'  => $variant['available_quantity'],
                        'selected_options'    => $variant['selected_options'],
                    ]);
                }
            }
            DB::commit();
            return $this->ApiSuccessResponse(new ProductResource($product),'success message');
        }catch (\Exception $exception){
            DB::rollBack();
            Log::error('error of store product' . $exception->getMessage());
            return $this->ApiErrorResponse([], 'something went wrong');
        }
    }
    public function show($id) : JsonResponse
    {
        try{
            $product = $this->productRepository->getModelById($id);
            if(!$product)
                return $this->notFoundResponse();
            return $this->ApiSuccessResponse(ProductResource::make($product),'success message');
        }catch (\Exception $exception){
            Log::error('error of get product' . $exception->getMessage());
            return $this->ApiErrorResponse([], 'something went wrong');
        }
    }
    public function update($request,$id) : JsonResponse
    {
        try{
            // Validate and prepare data
            $data = $request->validated();

            // Find the package by ID
            $product = $this->productRepository->getModelById($id);
            if (!$product) {
                return $this->notFoundResponse();
            }

            // Handle image upload if provided
            if ($request->hasFile('image')) {
                // Delete the old image if it exists
                if ($product->image) {
                    $this->remove_file('products', $product->image);
                }
                // Save the new image
                $data['image'] = $this->save_file($request->file('image'), 'products');
            }
            $updateData = [
                'name' => $data['name'] ?? $product->name,
                'description' => $data['description'] ?? $product->description,
                'status' => $data['status'] ?? $product->status,
                'price' => $data['price'] ?? $product->price,
                'price_after_discount' => $data['price_after_discount'] ?? $product->price_after_discount,
                'available_quantity' => $data['available_quantity'] ?? $product->available_quantity,
                'category_id' => $data['category_id'] ?? $product->category_id,
                'brand_id' => $data['brand_id'] ?? $product->brand_id,
                'image' => $data['image'] ?? $product->image,
                'discount_end_at' => $data['discount_end_at'] ?? $product->discount_end_at,
            ];
            // Update the package
            $product->update($updateData);
            if ($request->hasFile('attachments')) {

                $uploads = $this->uploadAttachments('attachments', 'products/media');

                foreach ($uploads as $upload) {
                    $product->media()->create([
                        'original_path'  => $upload['original_path'],
                        'media_path'     => $upload['media_path'],
                        'file_name'      => $upload['file_name'],
                        'file_extension' => $upload['file_extension'],
                        'is_main'        => false,
                        'status'         => 'active',
                    ]);
                }
            }

            if (!empty($data['attribute_option_ids'])) {
                $product->attributeOptions()->sync($data['attribute_option_ids']);
            }

            if (!empty($data['variants'])) {
                $product->variants()->delete();
                foreach ($data['variants'] as $variant) {
                    $product->variants()->create([
                        'sku' => $variant['sku'] ?? $this->generateUniqueSku($product->id),
                        'price' => $variant['price'],
                        'available_quantity' => $variant['available_quantity'],
                        'selected_options' => $variant['selected_options'],
                    ]);
                }
            }
            return $this->ApiSuccessResponse(new ProductResource($product), 'product updated successfully.');
        }catch (\Exception $exception){
            Log::error('error of update product ' . $exception->getMessage());
            return $this->ApiErrorResponse([],'something went wrong');
        }
    }
    public function destroy($id) : JsonResponse
    {
        try{
            $category = $this->productRepository->getModelById($id);
            if ($category) {
                // Check if an image exists and delete it
                if ($category->image) {
                    $this->remove_file('products', $category->image);
                }
                // Delete the package
                $category->delete();
                return $this->ApiSuccessResponse([],'product deleted successfully');
            }
            return $this->notFoundResponse();
        }catch (\Exception $exception){
            return $this->ApiErrorResponse($exception->getMessage());
        }
    }
    private function generateUniqueSku(int $productId): string
    {
        do {
            $sku = 'P' . $productId . '-V' . strtoupper(Str::random(6));
        } while (ProductVariants::query()->where('sku', $sku)->exists());

        return $sku;
    }
}
