<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Services\Admin\InfluencerEvaluationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InfluencerEvaluationController extends Controller
{
    public function __construct(public InfluencerEvaluationService $influencerEvaluationService){}

    public function index(Request $request) : JsonResponse
    {
        return $this->influencerEvaluationService->getDataFromUser($request);
    }
}
