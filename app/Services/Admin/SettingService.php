<?php
namespace App\Services\Admin;
use App\Helper\FileUpload;
use App\Http\Resources\Admin\Settings\SettingResource;
use App\Models\Setting;
use App\Repositories\Admin\SettingRepository;
use App\Traits\ApiResponseAble;
use Illuminate\Http\JsonResponse;

class SettingService
{
    use ApiResponseAble,FileUpload;
    public function __construct(public SettingRepository $settingRepository){}
    public function index() : JsonResponse
    {
        try{
            $settings = $this->settingRepository->getSettings();
            if($settings)
                return $this->ApiSuccessResponse(SettingResource::make($settings),'settings retrieved successfully.');
            return $this->listResponse([]);
        }catch (\Exception $exception){
            return $this->ApiErrorResponse([],$exception->getMessage(),500);
        }
    }
    public function update($request) : JsonResponse
    {
        try{
            $data = $request->validated();
            $setting = Setting::query()->first();
            if(!$setting)
                return $this->ApiSuccessResponse([],"Setting not found");
            if ($request->hasFile('site_logo')) {
                // Delete the old image if it exists
                if ($setting->site_logo) {
                    $this->remove_file('settings', $setting->site_logo);
                }
                // Save the new image
                $data['site_logo'] = $this->save_file($request->file('site_logo'), 'settings');
            }
            $setting->update([
                'site_name' => $request->site_name,
                'site_description' => $request->site_description,
                'site_logo' => $data['site_logo'] ?? $setting->site_logo,
                'site_address' => $request->site_address,
                'site_phone' => $request->site_phone,
                'site_video' => $request->site_video,
                'whatsapp' => $request->whatsapp,
                'facebook' => $request->facebook,
                'tiktok' => $request->tiktok,
                'instagram' => $request->instagram,
            ]);
            return $this->ApiSuccessResponse(SettingResource::make($setting),'Setting updated successfully.');
        }catch (\Exception $exception){
            return $this->ApiErrorResponse([],$exception->getMessage(),500);
        }
    }
}
