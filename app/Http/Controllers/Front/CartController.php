<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\Carts\StoreRequest;
use App\Services\Front\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(public CartService $cartService){}
    public function index(Request $request) : JsonResponse
    {
        return $this->cartService->index($request);
    }
    public function store(StoreRequest $request) : JsonResponse
    {
        return $this->cartService->store($request);
    }
    public function destroy(int $id) : JsonResponse
    {
        return $this->cartService->destroy($id);
    }
}
