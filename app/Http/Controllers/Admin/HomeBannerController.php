<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\HomeBanners\StoreRequest;
use App\Http\Requests\Admin\HomeBanners\UpdateRequest;
use App\Services\Admin\HomeBannerService;
use App\Services\Front\HomepageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HomeBannerController extends Controller
{
    public function __construct(public HomeBannerService $homepageService){}
    public function index(Request $request) : JsonResponse
    {
        return $this->homepageService->index($request);
    }
    public function store(StoreRequest $request) : JsonResponse
    {
        return $this->homepageService->store($request);
    }
    public function show(int $id) : JsonResponse
    {
        return $this->homepageService->show($id);
    }
    public function update(UpdateRequest $request, int $id) : JsonResponse
    {
        return $this->homepageService->update($request, $id);
    }
    public function destroy(int $id) : JsonResponse
    {
        return $this->homepageService->destroy($id);
    }
}
