<?php

namespace App\Http\Requests\TimKerja;

use Illuminate\Foundation\Http\FormRequest;

class SearchPegawaiRequest extends FormRequest
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
            'search' => ['nullable', 'string'],
            'satuan_kerja_id' => ['required', 'integer'],
        ];
    }
}
