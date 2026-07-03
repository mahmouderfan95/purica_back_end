<?php
namespace App\Services\Front;
use App\Models\User;
use App\Repositories\Front\AuthRepository;
use App\Repositories\Front\ForgetPasswordRepository;
use App\Traits\ApiResponseAble;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ForgetPasswordService
{
    use ApiResponseAble;
    public function __construct(
        public ForgetPasswordRepository $repository,
        public AuthRepository $authRepository,

    ){}
    public function forgetPassword($request) : JsonResponse
    {
//        try{
            DB::beginTransaction();

            $email = $request->email;

            $checkEmail = $this->authRepository->getModelByEmail($email);

            if(!$checkEmail){
                return $this->ApiErrorResponse([],__('general.email_not_found'),400);
            }

            $otp = rand(100000, 999999);

            $expiresAt = Carbon::now()->addMinutes(15);

            DB::table('password_reset_tokens')->where('email', $email)->delete();

            DB::table('password_reset_tokens')->insert([
                'email' => $email,
                'token' => $otp,
                'created_at' => Carbon::now(),
                'expired_at' => $expiresAt,
            ]);
            $user = User::query()->where('email', $email)->first();

            $user->notify(new \App\Notifications\ResetPasswordNotification($otp));
            DB::commit();
            return $this->ApiSuccessResponse([],'تم إرسال كود إعادة التعيين إلى بريدك الإلكتروني (صالح لمدة 15 دقيقة)');
//        }catch (\Exception $exception){
//            DB::rollBack();
//            Log::error('forget password error' . $exception->getMessage());
//            return $this->ApiErrorResponse([],'something went wrong');
//        }
    }
    public function resetPassword($request) : JsonResponse
    {
        try{
            DB::beginTransaction();
            $reset = DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->where('token', $request->otp)
                ->first();

            if (!$reset) {
                return $this->ApiErrorResponse([],'الكود غير صحيح');
            }

            if (Carbon::now()->greaterThan($reset->expired_at)) {
                return $this->ApiErrorResponse([],'انتهت صلاحية الكود');
            }

            $user = User::query()->where('email', $request->email)->first();
            $user->update([
                'password' => Hash::make($request->new_password),
            ]);

            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            DB::commit();
            return $this->ApiSuccessResponse([],'تم تغيير كلمة المرور بنجاح');
        }catch (\Exception $exception){
            DB::rollBack();
            Log::error('reset password error' . $exception->getMessage());
            return $this->ApiErrorResponse([],'something went wrong');
        }
    }
}
