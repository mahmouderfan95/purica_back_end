<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(public AuthService $authService){}
    public function login(Request $request) : JsonResponse
    {
        return $this->authService->login($request);
    }
    public function logout(Request $request) : JsonResponse
    {
        return $this->authService->logout($request);
    }
}
