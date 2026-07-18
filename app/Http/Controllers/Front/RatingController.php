<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\Ratings\StoreRequest;
use App\Services\Front\RatingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function __construct(public RatingService $ratingService){}
    public function store(StoreRequest $request) :JsonResponse
    {
        return $this->ratingService->store($request);
    }
}
