<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Services\Front\ProductService;
use App\Traits\ApiResponseAble;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use ApiResponseAble;
    public function __construct(public ProductService $productService){}
    public function offers(Request $request) : JsonResponse
    {
        return $this->productService->offers($request);
    }
    public function show($slug) : JsonResponse
    {
        return $this->productService->show($slug);
    }
}
