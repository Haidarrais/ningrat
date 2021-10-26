<?php

namespace App\Http\Requests\Variant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VariantStoreRequest extends FormRequest
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
        // $name = $this->request->name;
        // $parent_id = $this->request->parent_id;
        return [
            'name' => [
                'required',
                Rule::unique('variants')->where('parent_id', $this->parent_id)
                ->where('name', $this->name)
            ],
        ];
    }
}
