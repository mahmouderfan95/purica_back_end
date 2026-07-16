<?php

namespace App\Http\Requests\Admin\ShippingPrices;

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
            'shipping_company_id' => 'required|exists:shipping_companies,id',
            'city_id' => 'required|exists:cities,id',
//            'region_id' => 'required|exists:regions,id',
            'price' => 'required|numeric',
        ];
    }
}
