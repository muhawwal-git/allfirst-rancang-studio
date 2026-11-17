<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UploadBuktiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'bukti_transfer' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'bukti_transfer.required' => 'Silakan pilih berkas bukti transfer terlebih dahulu.',
            'bukti_transfer.mimes' => 'Format berkas bukti transfer harus berupa JPG, PNG, atau PDF.',
            'bukti_transfer.max' => 'Ukuran berkas bukti transfer maksimal 2 MB.',
        ];
    }
}
