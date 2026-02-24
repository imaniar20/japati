<?php

namespace App\Http\Requests\KinerjaSubKegiatan;

use Illuminate\Foundation\Http\FormRequest;

class StoreKinerjaSubKegiatan extends FormRequest
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
            'kegiatan_id' => ['required'],
            'sub_kegiatan_id' => ['nullable'],
            'sasaran' => ['nullable'],
            'indikator' => ['required'],
            'satuan' => ['required'],
            'target' => ['required'],
            'realisasi' => ['required'],
            'anggaran' => ['required'],
            'has_inovasi' => ['required'],
            'inovasi_uraian' => ['nullable'],
            'inovasi_tujuan' => ['nullable'],
            'inovasi_lampiran' => ['nullable'],
            'capaian' => ['nullable'],
            'realisasi_anggaran' => ['required'],
            'capaian_anggaran' => ['nullable'],
            'sasaran_strategis_rpjmd_id' => ['nullable'],
            'kinerja_program_id' => ['nullable'],
            'kinerja_kegiatan_id' => ['nullable'],
            'sasaran_strategis_pd_id' => ['nullable'],
            'v_struktur_organisasi_id' => ['nullable'],
            'pengampu' => ['nullable', 'string'],
            'tim_kerja_id' => ['nullable'],
            'indikator_kemendagri' => ['nullable', 'string'],
            'rencana_aksi' => ['nullable', 'string'],
            'is_external' => ['required', 'boolean'],
            'is_rencana_aksi_gubernur' => ['required', 'boolean'],
            'kab_kota_id' => ['nullable'],
            'kab_kota_satuan_kerja_id' => ['nullable'],
            'kab_kota_kinerja_sub_kegiatan_id' => ['nullable'],
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
