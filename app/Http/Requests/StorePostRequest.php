<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
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
            'title' => ['required', 'unique:posts', 'max:150'],
            'cover_image' => ['nullable', 'image', 'max:955'],
            'content' => ['nullable'],
            'category_id' => ['exists:categories,id'],
            'tags' => ['exists:tags,id']
        ];
    }
}
