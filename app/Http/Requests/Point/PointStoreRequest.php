<?php

namespace App\Http\Requests\Point;

use Illuminate\Foundation\Http\FormRequest;

class PointStoreRequest extends FormRequest
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
            'category_id' => 'required|unique:points,category_id',
            'min' => 'required|numeric',
            'point' => 'required|numeric',
        ];
    }
}
