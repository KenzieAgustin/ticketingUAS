<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StaffAssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        // return $this->user()->hasRole(['admin']);
        return true; // Sementara diizinkan semua untuk testing
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'integer'],
            'gate_id' => ['required', 'integer', 'exists:gates,id'],
            'assignment_date' => ['required', 'date'],
            'event_id' => ['nullable', 'integer'],
            'shift' => ['required', 'in:morning,afternoon,evening,full_day'],
            'shift_start' => ['required', 'date_format:H:i,H:i:s'],
            'shift_end' => ['required', 'date_format:H:i,H:i:s'],
            'status' => ['nullable', 'in:scheduled,active,completed,absent'],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'assignment_date.required' => 'Tanggal penugasan wajib diisi.',
            'shift_end.after' => 'Waktu selesai shift harus setelah waktu mulai shift.',
        ];
    }
}