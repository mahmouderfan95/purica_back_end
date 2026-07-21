<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\ReportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct(public ReportService $reportService){}
    public function admins(Request $request) : JsonResponse
    {
        return $this->reportService->getAdminsReports($request);
    }
    public function products(Request $request) : JsonResponse
    {
        return $this->reportService->getProductsReports($request);
    }
    public function users(Request $request) : JsonResponse
    {
        return $this->reportService->getUsersReports($request);
    }
    public function categoriesProductCharts(Request $request) : JsonResponse
    {
        return $this->reportService->categoriesProductCharts($request);
    }
    public function categoriesSalesRatio(Request $request) : JsonResponse
    {
        return $this->reportService->categoriesSalesRatio($request);
    }
    public function usersStatusReport(Request $request) : JsonResponse
    {
        return $this->reportService->usersStatusReport($request);
    }
}
