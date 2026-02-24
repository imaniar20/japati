<?php

namespace App\Http\Requests\VisiMisiRpjmd;

use Illuminate\Foundation\Http\FormRequest;

class StoreVisiMisiRpjmd extends FormRequest
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
            'satuan_kerja_id' => ['required'],
            'visi_id' => ['required'],
            'misi_id' => ['required'],
            'tujuan_id' => ['required'],
            'indikator_tujuan_id' => ['required'],
            'satuan' => ['required'],
            'target_baseline' => ['required'],
            'target_1' => ['required'],
            'target_2' => ['required'],
            'target_3' => ['required'],
            'target_4' => ['required'],
            'target_5' => ['required'],
            'realisasi_baseline' => ['nullable'],
            'realisasi_1' => ['nullable'],
            'realisasi_2' => ['nullable'],
            'realisasi_3' => ['nullable'],
            'realisasi_4' => ['nullable'],
            'realisasi_5' => ['nullable'],
            'capaian_baseline' => ['nullable'],
            'capaian_1' => ['nullable'],
            'capaian_2' => ['nullable'],
            'capaian_3' => ['nullable'],
            'capaian_4' => ['nullable'],
            'capaian_5' => ['nullable'],
        ];
    }
}
