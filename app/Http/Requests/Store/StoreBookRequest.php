<?php

namespace App\Http\Requests\Store;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
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
            'category_id' => ['required', 'exists:categories,id'],
            'title' => ['required', 'string'],
            'author' => ['required', 'string'],
            'ISBN_10' => ['required', 'numeric', 'unique:books,ISBN_10', 'digits:10'],
            'ISBN_13' => ['required', 'numeric', 'unique:books,ISBN_13', 'digits:13'],
            'edition' => ['nullable'],
            'value' => ['nullable'],
            'copies' => ['numeric'],
            'publisher' => ['nullable', 'string'],
            'bookable' => ['required'],
            'cover' => ['nullable']
        ];
    }
}
