<?php

namespace App\Http\Requests\TimeKerja;

use Illuminate\Foundation\Http\FormRequest;

class StoreTimKerja extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nama' => ['required', 'string'],
            'satuan_kerja_id' => ['required', 'integer'],
            'v_struktur_organisasi_id' => ['nullable', 'string'],
            'nip_ketua' => ['required', 'string'],
        ];
    }
}
