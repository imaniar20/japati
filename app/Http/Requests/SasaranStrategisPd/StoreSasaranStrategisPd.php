<?php

namespace App\Http\Requests\SasaranStrategisPd;

use Illuminate\Foundation\Http\FormRequest;

class StoreSasaranStrategisPd extends FormRequest
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
            'sasaran_strategis_id' => ['required'],
            'kinerja_bayangan_id' => ['nullable', 'numeric'],
            'indikator_sasaran_strategis_id' => ['required'],
            'sasaran_strategis_satker' => ['nullable'],
            'iku' => ['required'],
            'satuan' => ['required'],
            'tahun_mulai' => ['required'],
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
            'rata_nasional' => ['nullable'],
            'peringkat_nasional' => ['nullable'],
            'redaksi' => ['nullable'],
            'lampiran' => ['nullable'],
            'sasaran_strategis_rpjmd_id' => ['nullable'],
            'narasi_keberhasilan' => ['nullable'],
            'is_rapor_kinerja' => ['boolean'],
            'rata_internasional' => ['nullable'],
            'peringkat_internasional' => ['nullable'],
            'do_narasi' => ['nullable', 'string'],
            'do_rumus' => ['nullable', 'string'],
            'do_keterangan' => ['nullable', 'string'],
            'do_sumber' => ['nullable', 'string'],
            'why' => ['nullable', 'string'],
        ];
    }
}
