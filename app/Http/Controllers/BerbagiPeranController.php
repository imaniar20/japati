<?php

namespace App\Http\Controllers;

use App\Models\KinerjaKegiatan;
use App\Models\KinerjaKegiatanCross;
use App\Models\KinerjaProgram;
use App\Models\KinerjaProgramCross;
use App\Models\KinerjaSubKegiatan;
use App\Models\KinerjaSubKegiatanCross;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BerbagiPeranController extends Controller
{
    public function Index(Request $request): JsonResponse
    {
        $query = null;
        $transformedDataCross = collect();
        $queryExternal = null;
        if ($request->filled('sasaran_strategis_pd_id')) {
            $query = KinerjaProgram::tahunKinerja()
                ->where('sasaran_strategis_pd_id', $request->sasaran_strategis_pd_id);
            $dataCross = KinerjaProgramCross::where('sasaran_strategis_pd_id', $request->sasaran_strategis_pd_id)
                ->with([
                    'kinerjaProgram.satuanKerja',
                    'kinerjaProgram.strukturOrganisasi' => fn (Builder $query) => $query
                        ->selectRaw('id, jabatan_nama')
                        ->withoutGlobalScope('active'),
                    'kinerjaProgram.timKerja:id,nama,nip_ketua',
                    'kinerjaProgram.timKerja.ketua:peg_nip,peg_nama',
                ])->get();

            $transformedDataCross = $dataCross->map(function ($item) {
                return [
                    'satuan_kerja_nama' => $item->kinerjaProgram?->satuanKerja?->satuan_kerja_nama,
                    'jabatan_nama' => $item->kinerjaProgram?->strukturOrganisasi?->jabatan_nama,
                    'nama' => $item->kinerjaProgram?->timKerja?->nama,
                    'peg_nama' => $item->kinerjaProgram?->timKerja?->ketua?->peg_nama,
                ];
            });

            $queryExternal = KinerjaSubKegiatan::tahunKinerja()
                ->where('sasaran_strategis_pd_id', $request->sasaran_strategis_pd_id)
                ->where('is_external', 1);

        } elseif ($request->filled('kinerja_program_id')) {
            $query = KinerjaKegiatan::tahunKinerja()
                ->where('kinerja_program_id', $request->kinerja_program_id);
            $dataCross = KinerjaKegiatanCross::where('kinerja_program_id', $request->kinerja_program_id)
                ->with([
                    'kinerjaKegiatan.satuanKerja',
                    'kinerjaKegiatan.strukturOrganisasi' => fn (Builder $query) => $query
                        ->selectRaw('id, jabatan_nama')
                        ->withoutGlobalScope('active'),
                    'kinerjaKegiatan.timKerja:id,nama,nip_ketua',
                    'kinerjaKegiatan.timKerja.ketua:peg_nip,peg_nama',
                ])->get();

            $transformedDataCross = $dataCross->map(function ($item) {
                return [
                    'satuan_kerja_nama' => $item->kinerjaKegiatan?->satuanKerja?->satuan_kerja_nama,
                    'jabatan_nama' => $item->kinerjaKegiatan?->strukturOrganisasi?->jabatan_nama,
                    'nama' => $item->kinerjaKegiatan?->timKerja?->nama,
                    'peg_nama' => $item->kinerjaKegiatan?->timKerja?->ketua?->peg_nama,
                ];
            });

            $queryExternal = KinerjaSubKegiatan::tahunKinerja()
                ->where('kinerja_program_id', $request->kinerja_program_id)
                ->where('is_external', 1);

        } elseif ($request->filled('kinerja_kegiatan_id')) {
            $query = KinerjaSubKegiatan::tahunKinerja()
                ->where('kinerja_kegiatan_id', $request->kinerja_kegiatan_id)
                ->where('is_external', 0);
            $dataCross = KinerjaSubKegiatanCross::where('kinerja_kegiatan_id', $request->kinerja_kegiatan_id)
                ->with([
                    'kinerjaSubKegiatan.satuanKerja',
                    'kinerjaSubKegiatan.strukturOrganisasi' => fn (Builder $query) => $query
                        ->selectRaw('id, jabatan_nama')
                        ->withoutGlobalScope('active'),
                    'kinerjaSubKegiatan.timKerja:id,nama,nip_ketua',
                    'kinerjaSubKegiatan.timKerja.ketua:peg_nip,peg_nama',
                ])->get();
            $transformedDataCross = $dataCross->map(function ($item) {
                return [
                    'satuan_kerja_nama' => $item->kinerjaSubKegiatan?->satuanKerja?->satuan_kerja_nama,
                    'jabatan_nama' => $item->kinerjaSubKegiatan?->strukturOrganisasi?->jabatan_nama,
                    'nama' => $item->kinerjaSubKegiatan?->timKerja?->nama,
                    'peg_nama' => $item->kinerjaSubKegiatan?->timKerja?->ketua?->peg_nama,
                ];
            });

            $queryExternal = KinerjaSubKegiatan::tahunKinerja()
                ->where('kinerja_kegiatan_id', $request->kinerja_kegiatan_id)
                ->where('is_external', 1);
        }

        $data = $query->with([
            'strukturOrganisasi' => fn (Builder $query) => $query
                ->selectRaw('id, jabatan_nama')
                ->withoutGlobalScope('active'),
            'timKerja:id,nama,nip_ketua',
            'timKerja.ketua:peg_nip,peg_nama',
        ])->get();

        $dataExternal = $queryExternal->with([
            'strukturOrganisasi' => fn (Builder $query) => $query
                ->selectRaw('id, jabatan_nama')
                ->withoutGlobalScope('active'),
            'timKerja:id,nama,nip_ketua',
            'timKerja.ketua:peg_nip,peg_nama',
        ])->get();

        $transformedData = $data->map(function ($item) {
            return [
                'jabatan_nama' => $item->strukturOrganisasi?->jabatan_nama,
                'nama' => $item->timKerja?->nama,
                'peg_nama' => $item->timKerja?->ketua?->peg_nama,
            ];
        });

        $transformedDataExternal = $dataExternal->map(function ($item) {
            return [
                'jabatan_nama' => $item->strukturOrganisasi?->jabatan_nama,
                'nama' => $item->timKerja?->nama,
                'peg_nama' => $item->timKerja?->ketua?->peg_nama,
            ];
        });

        $distinctData = $transformedData->unique()->values();
        $distinctDataCross = $transformedDataCross->unique()->values();
        $distinctDataExternal = $transformedDataExternal->unique()->values();

        return response()->json([
            'kinerjaInternal' => $distinctData,
            'kinerjaCrossCutting' => $distinctDataCross,
            'kinerjaExternal' => $distinctDataExternal,
        ]);

    }
}
