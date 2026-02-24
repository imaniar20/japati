<?php

namespace App\Http\Requests\KinerjaProgram;

use Illuminate\Foundation\Http\FormRequest;

class UpdateKinerjaProgram extends FormRequest
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
            'sasaran_strategis_pd_id' => ['required'],
            'satker_iku_id' => ['required'],
            'kinerja_bayangan_id' => ['nullable', 'numeric'],
            'program_id' => ['required'],
            'sasaran' => ['nullable'],
            'indikator' => ['required'],
            'indikator_kemendagri' => ['nullable'],
            'satuan' => ['required'],
            'target' => ['required'],
            'anggaran' => ['required'],
            'realisasi' => ['nullable'],
            'capaian' => ['nullable'],
            'penyebab_kegagalan' => ['nullable', 'string'],
            'realisasi_anggaran' => ['required'],
            'capaian_anggaran' => ['nullable'],
            'v_struktur_organisasi_id' => ['nullable'],
            'pengampu' => ['nullable', 'string'],
            'tim_kerja_id' => ['nullable'],
            'order' => ['required', 'numeric'],
            'do_narasi' => ['nullable', 'string'],
            'do_rumus' => ['nullable', 'string'],
            'do_keterangan' => ['nullable', 'string'],
            'do_sumber' => ['nullable', 'string'],
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
