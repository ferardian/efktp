<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CpptRequest extends FormRequest
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
            'no_rawat' => 'required',
            'tgl_perawatan' => 'required',
            'jam_rawat' => 'required',
            'suhu_tubuh' => 'required',
            'tensi' => 'required',
            'nadi' => 'required',
            'respirasi' => 'required',
            'saturasi' => 'required',
            'tinggi' => 'required',
            'berat' => 'required',
            'spo2' => 'required',
            'kesadaran' => 'required',
            'keluhan' => 'required',
            'pemeriksaan' => 'required',
            'alergi' => 'required',
            'lingkar_perut' => 'required',
            'rtl' => 'required',
            'penilaian' => 'required',
            'instruksi' => 'required',
            'evaluasi' => 'required',
            'nip' => 'required',
        ];
    }
}
