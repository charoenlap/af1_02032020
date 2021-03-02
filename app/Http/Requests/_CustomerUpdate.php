<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerUpdate extends FormRequest
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
            'email' => 'required|email',
        ];
    }

    public function messages()
    {
        return [
            'name.required'  => 'ไม่มีชื่อว่ะ',
            'email.required' => 'ห้ามว่างนะ',
            'email.email' => 'ใส่ผิดป่าว'
        ];
    }
}
