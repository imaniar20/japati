<?php

namespace App\Http\Requests\SasaranStrategisRpjmd;

use Illuminate\Foundation\Http\FormRequest;

class StoreSasaranStrategisRpjmd extends FormRequest
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
            'indikator_sasaran_strategis_id' => ['required'],
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
            'strategi' => ['nullable'],
            'misi_id' => ['nullable'],
            'tujuan_id' => ['nullable'],
            'indikator_tujuan_id' => ['nullable'],
            'target_visi_misi_rpjmd_id' => ['nullable'],
            'capaian_terhadap_target_akhir' => ['nullable'],
            'penghargaan' => ['nullable'],
            'perbandingan_realisasi_tahun_1' => ['nullable', 'string'],
            'perbandingan_realisasi_tahun_2' => ['nullable', 'string'],
            'perbandingan_realisasi_tahun_3' => ['nullable', 'string'],
            'perbandingan_realisasi_tahun_4' => ['nullable', 'string'],
            'perbandingan_realisasi_tahun_5' => ['nullable', 'string'],
            'perbandingan_realisasi_target_1' => ['nullable', 'string'],
            'perbandingan_realisasi_target_2' => ['nullable', 'string'],
            'perbandingan_realisasi_target_3' => ['nullable', 'string'],
            'perbandingan_realisasi_target_4' => ['nullable', 'string'],
            'perbandingan_realisasi_target_5' => ['nullable', 'string'],
        ];
    }
}
