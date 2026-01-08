<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            '*.file' => [
                'required',
                'string',
            ],
            '*.id_kategori' => [
                'required',
                'integer',
            ],
            '*.no_rawat' => [
                'required',
                'string',
            ],
            '*.nik' => [
                'required',
                'string',
            ],
        ];
    }

    public function messages()
    {
        return [
            '*.file.required' => 'File wajib diisi',
            '*.id_kategori.required' => 'Kategori wajib diisi',
            '*.no_rawat.required' => 'No Rawat wajib diisi',
            '*.nik.required' => 'NIK wajib diisi',
        ];
    }
}
