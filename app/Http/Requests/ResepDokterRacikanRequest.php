<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResepDokterRacikanRequest extends FormRequest
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
			'data.*.no_resep' => 'required',
			'data.*.id_template' => 'nullable', // Contoh validasi integer & keberadaan di tabel lain
			'data.*.no_racik' => 'required|integer|min:1',
			'data.*.nama_racik' => 'required|string|max:100',
			'data.*.jml_dr' => 'required|numeric|min:1',
			'data.*.kd_racik' => 'required|string|max:10',
			'data.*.aturan_pakai' => 'required|string|max:255',
			'data.*.keterangan' => 'nullable|string|max:255', // Keterangan dibuat nullable
		];
	}

	public function prepareForValidation()
	{
		if ($this->has('keterangan') || $this->keterangan === null) {
			$this->merge([
				'keterangan' => '-'
			]);
		}
	}

}
