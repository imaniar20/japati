<?php

namespace App\Http\Requests\KinerjaBayangan;

use Illuminate\Foundation\Http\FormRequest;

class StoreKinerjaBayangan extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'satuan_kerja_id' => ['required', 'numeric'],
            'sasaran' => ['required', 'string'],
            'indikator' => ['required', 'string'],
            'satuan' => ['required', 'string'],
            'tahun_mulai' => ['required', 'numeric'],
            'target_baseline' => ['required', 'string'],
            'target_1' => ['required', 'string'],
            'target_2' => ['required', 'string'],
            'target_3' => ['required', 'string'],
            'target_4' => ['required', 'string'],
            'target_5' => ['required', 'string'],
            'realisasi_baseline' => ['nullable', 'string'],
            'realisasi_1' => ['nullable', 'string'],
            'realisasi_2' => ['nullable', 'string'],
            'realisasi_3' => ['nullable', 'string'],
            'realisasi_4' => ['nullable', 'string'],
            'realisasi_5' => ['nullable', 'string'],
            'capaian_baseline' => ['nullable', 'numeric'],
            'capaian_1' => ['nullable', 'numeric'],
            'capaian_2' => ['nullable', 'numeric'],
            'capaian_3' => ['nullable', 'numeric'],
            'capaian_4' => ['nullable', 'numeric'],
            'capaian_5' => ['nullable', 'numeric'],
        ];
    }
}
