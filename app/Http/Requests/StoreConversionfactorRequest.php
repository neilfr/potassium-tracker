<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreConversionfactorRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'foodDescription' => 'required|string',
            'measureDescription' => 'required|string',
            'kcal' => 'required|integer|numeric|min:0',
            'k' => 'required|integer|numeric|min:0',
        ];
    }
}
