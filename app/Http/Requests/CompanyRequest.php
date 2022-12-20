<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'category'  => 'required|exists:categories,id',
            'name'      => "required|string|min:3|unique:companies,name,{$this->company},uuid",
            'email'     => "required|email|unique:companies,email,{$this->company},uuid",
            'phone'     => 'required|string|min:3',
            'whatsapp'  => 'nullable',
            'facebook'  => 'nullable',
            'instagram' => 'nullable',
            'youtube'   => 'nullable',
        ];
    }
}
