<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Settings\UpdateRequest;
use App\Services\Admin\SettingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function __construct(public SettingService $settingService){}
    public function index() : JsonResponse
    {
        return $this->settingService->index();
    }
    public function update(UpdateRequest $request) : JsonResponse
    {
        return $this->settingService->update($request);
    }
}
