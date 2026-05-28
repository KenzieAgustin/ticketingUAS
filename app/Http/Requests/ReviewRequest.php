<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->hasRole(['customer']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'event_id' => ['required', 'integer'],
            'order_id' => ['required', 'integer', 'exists:orders,id'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'title' => ['nullable', 'string', 'max:150'],
            'body' => ['nullable', 'string', 'max:1000'],
            'is_approved' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'event_id.required' => 'ID event wajib diisi.',
            'order_id.required' => 'ID order wajib diisi.',
            'order_id.exists' => 'ID order tidak valid.',
            'rating.required' => 'Rating wajib diisi (1-5).',
        ];
    }
}
