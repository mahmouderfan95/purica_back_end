<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Sliders\StoreRequest;
use App\Http\Requests\Admin\Sliders\UpdateRequest;
use App\Services\Admin\SliderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    public function __construct(public SliderService $sliderService){}
    public function index(Request $request) : JsonResponse
    {
        return $this->sliderService->index($request);
    }
    public function store(StoreRequest $request) : JsonResponse
    {
        return $this->sliderService->store($request);
    }
    public function show(int $id) : JsonResponse
    {
        return $this->sliderService->show($id);
    }
    public function update(UpdateRequest $request, int $id) : JsonResponse
    {
        return $this->sliderService->update($request, $id);
    }
    public function destroy(int $id) : JsonResponse
    {
        return $this->sliderService->destroy($id);
    }
    public function changeStatus(int $id,Request $request) : JsonResponse
    {
        return $this->sliderService->changeStatus($id,$request, );
    }
}
