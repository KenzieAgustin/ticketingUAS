<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function authorize()
    { 
        return true; 
    }

    public function rules()
    {
        return [
            'name'   => ['required', 'string', 'max:100'],
            'email'  => ['required', 'email', Rule::unique('users')->ignore(Auth::id())],
            'phone'  => ['nullable', 'string', 'max:20'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    public function messages()
    {
        return [
            'name.required'  => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique'   => 'Email sudah dipakai akun lain.',
            'avatar.image'   => 'File harus berupa gambar.',
            'avatar.max'     => 'Ukuran gambar maksimal 2MB.',
        ];
    }
}