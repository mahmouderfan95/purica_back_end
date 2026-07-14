<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\Favorites\StoreRequest;
use App\Services\Front\FavoriteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function __construct(public FavoriteService $favoriteService){}
    public function index(Request $request) : JsonResponse
    {
        return $this->favoriteService->list($request);
    }
    public function store(StoreRequest $request) : JsonResponse
    {
        return $this->favoriteService->toggle($request);
    }
}
