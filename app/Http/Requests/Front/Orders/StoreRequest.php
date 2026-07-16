<?php

namespace App\Http\Requests\Front\Orders;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'address' => ['required', 'string', 'max:255'],
            'payment_type' => ['required', 'string', 'in:cod,visa'],
            'notes' => ['nullable', 'string'],
            'city_id' => ['required', 'exists:cities,id'],
            'region_id' => ['required', 'exists:regions,id'],
//            'wallet_number' => ['required_if:payment_type,wallet', 'string', 'max:20'],
//            'conversion_image' => ['required_if:payment_type,wallet', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
//            'coupon_code' => ['nullable', 'string', 'exists:coupons,code'],
//            'shipping_company_id' => ['nullable', 'exists:shipping_companies,id'],
//            'shipping_cost' => ['nullable', 'numeric'],
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string','regex:/^01[0-2,5][0-9]{8}$/',],
        ];
    }
}
