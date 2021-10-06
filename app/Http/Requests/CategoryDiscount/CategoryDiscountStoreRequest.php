<?php

namespace App\Http\Requests\CategoryDiscount;

use Illuminate\Foundation\Http\FormRequest;

class CategoryDiscountStoreRequest extends FormRequest
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
            'category_id' => 'required|unique:category_discounts,category_id,' . request()->id
        ];
    }
}
