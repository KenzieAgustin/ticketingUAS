<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StaffAssignmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->hasRole(['admin']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'staff_id' => ['required', 'integer', 'exists:users,id'],
            'gate_id' => ['required', 'integer', 'exists:gates,id'],
            'assignment_date' => ['required', 'date'],
            'event_id' => ['required', 'integer'],
            'shift' => ['required', 'in:morning,afternoon,evening,full_day'],
            'shift_start' => ['required', 'date_format:H:i'],
            'shift_end' => ['required', 'date_format:H:i', 'after:shift_start'],
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
