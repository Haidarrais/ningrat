<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
            'email' => "required",
            'phone_number' => 'required',
            'password' => 'nullable|confirmed|min:8',
            'province_id' => 'required|integer',
            'city_id' => 'required|integer',
            'subdistrict_id' => 'required|integer',
            'address' => 'required'
        ];
    }
}
