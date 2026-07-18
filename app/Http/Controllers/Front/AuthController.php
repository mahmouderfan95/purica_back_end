<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\Auth\LoginRequest;
use App\Http\Requests\Front\Auth\RegisterRequest;
use App\Http\Requests\Front\Auth\UpdateProfileRequest;
use App\Services\Front\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(public AuthService $authService){}
    public function register(RegisterRequest $request) : JsonResponse
    {
        return $this->authService->register($request);
    }
    public function login(LoginRequest $request) : JsonResponse
    {
        return $this->authService->login($request);
    }
    public function logout(Request $request) : JsonResponse
    {
        return $this->authService->logout($request);
    }
    public function profile() : JsonResponse
    {
        return $this->authService->profile();
    }
    public function update(UpdateProfileRequest $request) : JsonResponse
    {
        return $this->authService->updateProfile($request);
    }
}
