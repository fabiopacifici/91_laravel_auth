<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePostRequest extends FormRequest
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
        //dd($this->post);
        return [
            'title' => ['required', Rule::unique('posts', 'title')->ignore($this->post), 'max:150'],
            'cover_image' => ['nullable', 'max:255'],
            'content' => ['nullable'],
            'category_id' => ['exists:categories,id']
        ];
    }
}
