<?php

namespace App\Http\Requests\Store;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('user create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'username' => ['required', 'string', 'unique:users,username'],
            'phone' => ['required', 'string', 'unique:users,phone'],
            'email' => ['required', 'string', 'unique:users,email'],
            'joined_date' => ['required', 'date'],
            'roles' => ['required', 'exists:roles,id']
        ];
    }
}
