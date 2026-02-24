<?php

namespace App\Http\Requests\LKE;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRekomendasi extends FormRequest
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
            'satuan_kerja_id' => 'required',
            'rekomendasi' => 'required',
            'tindak_lanjut' => 'nullable',
            'target' => 'nullable',
            'waktu' => 'nullable',
            'penanggung_jawab' => 'nullable',
            'status' => 'nullable',
            'link_eviden' => 'nullable',
            'status_monev' => 'nullable',

        ];
    }
}
