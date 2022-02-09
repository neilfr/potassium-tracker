<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNewfoodRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'foodGroupID' => 'exists:foodgroups,FoodGroupID',
            'foodDescription' => 'required|string',
            'measureDescription' => 'required|string',
            'kCalValue' => 'required|numeric|min:0',
            'potassiumValue' => 'required|numeric|min:0',
        ];
    }
}
