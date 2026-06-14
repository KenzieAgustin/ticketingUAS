<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GateRequest extends FormRequest
{
    public function authorize(): bool
    {
        // return $this->user()->hasRole(['admin']);
        return true; // Sementara diizinkan semua untuk testing
    }

    public function rules(): array
    {
        $gateId = $this->route('gate') ?->id;
        return [
            'code' => ['required', 'string', 'max:50', Rule::unique('gates', 'code')->ignore($gateId)],
            'name' => ['required', 'string', 'max:150'],
            'type' => ['required', 'in:main,concert,exhibition,emergency'],
            'stage_id' => ['nullable', 'integer'],
            'description' => ['nullable', 'string', 'max:500'],
            'status' => ['nullable', 'in:active,inactive,maintenance'],
        ];
    }
}
