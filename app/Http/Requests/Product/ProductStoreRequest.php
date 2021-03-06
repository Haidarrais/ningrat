<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'sub_category' => 'required',
            'description' => 'required',
            'image' => 'required',
            'price' => 'required',
            'weight' => 'required|numeric',
            // 'variant_id' => 'required|numeric'
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'price.numeric' => 'Harga harus angka',
            'weight.numeric' => 'Berat harus angka'
        ];
    }
}
