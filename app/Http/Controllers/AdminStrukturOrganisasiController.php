<?php

namespace App\Http\Controllers;

use App\Models\VStrukturOrganisasi;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class AdminStrukturOrganisasiController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'with_inactive' => ['nullable', 'in:0,1,true,false'],
        ]);

        $data = VStrukturOrganisasi::query()
            ->when($request->boolean('with_inactive'), fn (Builder $query) => $query->withoutGlobalScope('active'))
            ->select($this->columns())
            ->orderBy('satuan_kerja_nama')
            ->orderBy('level')
            ->orderBy('unit_kerja_nama')
            ->get();

        return response()->json($data);
    }

    private function columns(): array
    {
        $preferred = [
            'id',
            'satuan_kerja_id',
            'satuan_kerja_nama',
            'unit_kerja_nama',
            'jabatan_id',
            'jabatan_nama',
            'level',
            'status',
            'unit_kerja_aktif_selesai',
            'lv1_unit_kerja_nama',
            'lv2_unit_kerja_id',
            'lv2_unit_kerja_nama',
            'lv3_unit_kerja_nama',
            'lv4_unit_kerja_nama',
            'lv5_unit_kerja_nama',
            'lv6_unit_kerja_nama',
            'lv7_unit_kerja_nama',
        ];

        try {
            $available = Schema::getColumnListing('v_struktur_organisasi');
        } catch (\Throwable $exception) {
            return $preferred;
        }

        $columns = array_values(array_intersect($preferred, $available));

        return count($columns) ? $columns : $preferred;
    }
}
