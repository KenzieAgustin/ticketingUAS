<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CheckInRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Hanya staff_gate & admin yang melakukan scan
        return $this->user()->hasRole(['staff_gate', 'admin']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'booking_code' => ['required', 'string', 'max:100'],
            'gate_id' => ['required', 'integer', 'exists:gates,id'],
            'method' => ['nullable', 'in:qr_scan,manual_code'],
        ];
    }

    public function messages(): array
    {
        return [
            'booking_code.required' => 'Kode booking wajib diisi.',
            'booking_code.string' => 'Kode booking harus berupa teks.',
            'gate_id.required' => 'ID gate wajib diisi.',
            'gate_id.exists' => 'ID gate tidak valid.',
            'method.in' => 'Metode check-in tidak valid.',
        ];
    }
}
