<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EfktpHasilUsgRequest extends FormRequest
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
			'no_rawat' => 'required|string|max:17',
			'kd_dokter' => 'required|string|max:20',

			'janin' => 'required|string|max:200',
			'presentasi' => 'nullable|string|max:200',
			'presentasi2' => 'nullable|string|max:200',
			'DJJ' => 'required|string|max:200',
			'DJJ2' => 'nullable|string|max:200',
			'letak_punggung' => 'nullable|string|max:200',
			'letak_punggung2' => 'nullable|string|max:200',
			'letak_plasenta' => 'nullable|string|max:200',
			'letak_plasenta2' => 'nullable|string|max:200',

			'jenis_kelamin' => 'required|in:L,P,-',
			'jenis_kelamin2' => 'nullable|in:L,P,-',

			'TBJ' => 'nullable|string|max:200',
			'TBJ2' => 'nullable|string|max:200',
			'kelainan_kongenital' => 'nullable|string|max:200',
			'kelainan_kongenital2' => 'nullable|string|max:200',

			'HPHT' => 'nullable|date',
			'HPL' => 'nullable|date',
			'HPL2' => 'nullable|date',
			'umur_kehamilan' => 'nullable|string',
			'umur_kehamilan2' => 'nullable|string',

			'GS' => 'nullable|string|max:200',
			'lain_lain' => 'nullable|string|max:200',
			'lain_lain2' => 'nullable|string|max:200',
			'ketuban' => 'nullable|string|max:200',
			'ketuban2' => 'nullable|string|max:200',

			'pemeriksaan_fisik_tambahan' => 'nullable|string',
			'pemeriksaan_fisik_tambahan2' => 'nullable|string',

			'fetalpole' => 'nullable|string|max:200',
			'pulsasi' => 'nullable|string|max:200',
			'umur_kehamilan_gs' => 'nullable|string|max:200',
			'lain' => 'nullable|string|max:200',
		];
	}


}
