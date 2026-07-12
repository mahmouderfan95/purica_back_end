<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Services\Front\HomepageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HomepageController extends Controller
{
    public function __construct(public HomepageService $homepageService){}
    public function index(): JsonResponse
    {
        return $this->homepageService->index();
    }
}
