<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\Auth\ForgetPasswordRequest;
use App\Http\Requests\Front\Auth\ResetPasswordRequest;
use App\Services\Front\ForgetPasswordService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ForgetPasswordController extends Controller
{
    public function __construct(public ForgetPasswordService $forgetPasswordService){}
    public function forgetPassword(ForgetPasswordRequest $request) : JsonResponse
    {
        return $this->forgetPasswordService->forgetPassword($request);
    }
    public function resetPassword(ResetPasswordRequest $request) : JsonResponse
    {
        return $this->forgetPasswordService->resetPassword($request);
    }
}
