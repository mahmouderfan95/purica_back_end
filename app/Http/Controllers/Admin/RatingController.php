<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Ratings\UpdateRequest;
use App\Services\Admin\RatingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function __construct(public RatingService $ratingService){}
    public function index(Request $request) : JsonResponse
    {
        return $this->ratingService->index($request);
    }
    public function update($id,UpdateRequest $request) : JsonResponse
    {
        return $this->ratingService->update($id,$request);
    }
    public function destroy($id) : JsonResponse
    {
        return $this->ratingService->destroy($id);
    }
}
