<?php

namespace App\Http\Requests\KinerjaLangkahAksi;

use Illuminate\Foundation\Http\FormRequest;

class UpdateKinerjaLangkahAksi extends FormRequest
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
        $months = ['jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec'];
        $rules = [
            'satuan_kerja_id' => ['required'],
            'sub_kegiatan_id' => ['required'],
            'langkah_aksi' => ['required'],
            'sasaran' => ['nullable'],
            'indikator' => ['required'],
            'satuan' => ['required'],
            'target' => ['required'],
            'anggaran' => ['required'],
            'realisasi' => ['nullable'],
            'capaian' => ['nullable'],
            'penyebab_kegagalan' => ['nullable', 'string'],
            'realisasi_anggaran' => ['required'],
            'capaian_anggaran' => ['nullable'],
            'sasaran_strategis_rpjmd_id' => ['nullable'],
            'sasaran_strategis_pd_id' => ['nullable'],
            'kinerja_program_id' => ['nullable'],
            'kinerja_kegiatan_id' => ['nullable'],
            'kinerja_sub_kegiatan_id' => ['nullable'],
        ];

        foreach ($months as $month) {
            $rules["target_bulanan.{$month}"] = ['required'];
            $rules["realisasi_bulanan.{$month}"] = ['nullable'];
            $rules["anggaran_bulanan.{$month}"] = ['required', 'numeric'];
            $rules["realisasi_anggaran_bulanan.{$month}"] = ['required', 'numeric'];
        }

        return $rules;
    }
}
