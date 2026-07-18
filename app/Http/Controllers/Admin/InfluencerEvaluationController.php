<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\InfluencerEvaluations\StoreRequest;
use App\Http\Requests\Admin\InfluencerEvaluations\UpdateRequest;
use App\Services\Admin\InfluencerEvaluationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InfluencerEvaluationController extends Controller
{
    public function __construct(public InfluencerEvaluationService $reviewService){}
    public function index(Request $request) : JsonResponse
    {
        return $this->reviewService->index($request);
    }
    public function store(StoreRequest $request) : JsonResponse
    {
        return $this->reviewService->store($request);
    }
    public function show(int $id) : JsonResponse
    {
        return $this->reviewService->show($id);
    }
    public function update(UpdateRequest $request, int $id) : JsonResponse
    {
        return $this->reviewService->update($request, $id);
    }
    public function destroy(int $id) : JsonResponse
    {
        return $this->reviewService->destroy($id);
    }
}
