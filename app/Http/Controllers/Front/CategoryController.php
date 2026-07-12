<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Services\Front\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(public CategoryService $categoryService){}
    public function index() : JsonResponse
    {
        return $this->categoryService->index();
    }
    public function show($id, Request $request) : JsonResponse
    {
        return $this->categoryService->show($id,$request);
    }
}
