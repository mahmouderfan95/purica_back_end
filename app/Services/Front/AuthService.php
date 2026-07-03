<?php
namespace App\Services\Front;
use App\Enums\GeneralStatusEnum;
use App\Http\Resources\Front\Auth\AuthResource;
use App\Models\User;
use App\Repositories\Front\AuthRepository;
use App\Traits\ApiResponseAble;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Testing\Fluent\Concerns\Has;

class AuthService
{
    use ApiResponseAble;
    public function __construct(public AuthRepository $repository){}
    public function register($request) : JsonResponse
    {
        DB::beginTransaction();
        try{
            $user = User::query()->create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'status' => GeneralStatusEnum::ACTIVE
            ]);

            // Sanctum
            $token = $user->createToken('user_token')->plainTextToken;

            DB::commit();

            return $this->ApiSuccessResponseAndToken($user,'user registered successfully',$token);
        }catch (\Exception $exception)
        {
            DB::rollBack();
            Log::error('error for register request' . $exception->getMessage());
            return $this->ApiErrorResponse([],'something went wrong',500);
        }
    }
    public function login($request) : JsonResponse
    {
        DB::beginTransaction();
        try{
            $user = $this->repository->getModelByEmail($request->email);

            if (!$user || !Hash::check($request->password, $user->password)) {
                return $this->unAuthenticatedResponse();
            }
            // Create Sanctum token
            $token = $user->createToken('user_token')->plainTextToken;

            return $this->ApiSuccessResponseAndToken(
                AuthResource::make($user),
                'login successfully',
                $token
            );
        }catch (\Exception $exception)
        {
            Log::error('error for login request' . $exception->getMessage());
            return $this->ApiErrorResponse([],'something went wrong',500);
        }
    }
}
