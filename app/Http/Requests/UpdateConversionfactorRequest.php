<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateConversionfactorRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'foodDescription' => 'string',
            'measureDescription' => 'string',
            'nutrients.*.NutrientAmount' => 'integer|numeric|min:0',
        ];
    }
}
