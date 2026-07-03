<?php
namespace App\Services\Admin;
use App\Http\Resources\Admin\AuthResource;
use App\Repositories\Admin\AuthRepository;
use App\Traits\ApiResponseAble;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    use ApiResponseAble;
    public function __construct(public AuthRepository $repository){}
    public function login($request) : JsonResponse
    {
        $admin = $this->repository->getModelByEmail($request->email);

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return $this->unAuthenticatedResponse();
        }
        // Create Sanctum token
        $token = $admin->createToken('admin-token')->plainTextToken;

        return $this->ApiSuccessResponseAndToken(
            AuthResource::make($admin),
            'login successfully',
            $token
        );
    }

    public function logout($request) : JsonResponse
    {
        $user = auth('adminApi')->user();

        $request->user()->currentAccessToken()->delete();

        return $this->ApiSuccessResponse([],'Admin Logged out successfully');
    }
}
