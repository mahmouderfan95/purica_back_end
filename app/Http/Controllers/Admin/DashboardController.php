<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\DashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(public DashboardService $dashboardService){}
    public function statistics(Request $request) : JsonResponse
    {
        return $this->dashboardService->statistics($request);
    }
}
