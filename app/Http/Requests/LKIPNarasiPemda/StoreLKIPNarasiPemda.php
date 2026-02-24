<?php

namespace App\Http\Requests\LKIPNarasiPemda;

use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;

class StoreLKIPNarasiPemda extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Role::isPemerintahDaerah();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'sasaran_strategis_id' => ['required', 'integer'],
            'indikator_sasaran_strategis_id' => ['required', 'integer'],
            'narasi_1' => ['nullable'],
            'narasi_2' => ['nullable'],
            'narasi_3' => ['nullable'],
            'narasi_4' => ['nullable'],
            'narasi_5' => ['nullable'],
            'narasi_6' => ['nullable'],
            'narasi_7' => ['nullable'],
        ];
    }
}
