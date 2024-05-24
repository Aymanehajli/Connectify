<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PublicationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'titre' => 'nullable|min:5|max:150',
            'body' => 'required|min:10',
            'image' => 'nullable|image|mimes:png,jpg,svg,jpeg,jfif',
            'video' => 'nullable|mimetypes:video/mp4,video/avi,video/mpeg',
        ];
    }
    public function messages()
    {
        return[
            'titre.required'=> "titre vide...."
        ];
    }
}
