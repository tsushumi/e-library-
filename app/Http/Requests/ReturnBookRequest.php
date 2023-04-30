<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReturnBookRequest extends FormRequest
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
            'book_sn' => ['','confirmed'],
        ];
    }

    public function validationData()
    {
        return array_merge($this->all(), [
            'book_sn' => $this->loan()->book_sn
        ]);
    }

    public function attributes()
    {
        return [
            'confirm_book_sn' => 'Book serial number'
        ];
    }

    public function messages()
    {
        return [
            'confirm_book_sn.required' => 'Please prodive the appropriate book '
        ];
    }
}
