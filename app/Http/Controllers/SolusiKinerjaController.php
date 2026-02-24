<?php

namespace App\Http\Controllers;

use App\Models\KinerjaKegiatan;
use App\Models\KinerjaProgram;
use App\Models\KinerjaSubKegiatan;
use App\Models\SolusiKinerja;
use Exception;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SolusiKinerjaController extends Controller
{
    public function index(string $type, Request $request)
    {
        switch ($type) {
            case 'kinerja-program':
                $data = KinerjaProgram::roleSatuanKerja()
                    ->tahunKinerja($this->getPrevTahunKinerja())
                    ->with('program')
                    ->get()
                    ->transform(function (KinerjaProgram $kinerja) {
                        return [
                            'id' => $kinerja->id,
                            'nama' => $kinerja->program?->nama,
                            'sasaran' => $kinerja->sasaran,
                            'indikator' => $kinerja->indikator,
                            'type' => 'kinerja-program',
                        ];
                    });
                break;
            case 'kinerja-kegiatan':
                $data = KinerjaKegiatan::roleSatuanKerja()
                    ->tahunKinerja($this->getPrevTahunKinerja())
                    ->with('kegiatan')
                    ->get()
                    ->transform(function (KinerjaKegiatan $kinerja) {
                        return [
                            'id' => $kinerja->id,
                            'nama' => $kinerja->kegiatan?->nama, // harusnya kegiatan ga akan null kecuali kegiatannya dihapus atau belum di set
                            'sasaran' => $kinerja->sasaran,
                            'indikator' => $kinerja->indikator,
                            'type' => 'kinerja-kegiatan',
                        ];
                    });
                break;
            case 'kinerja-sub-kegiatan':
            case 'kinerja-langkah-aksi':
                $data = KinerjaSubKegiatan::roleSatuanKerja()
                    ->tahunKinerja($this->getPrevTahunKinerja())
                    ->with('subKegiatan')
                    ->get()
                    ->transform(function (KinerjaSubKegiatan $kinerja) {
                        return [
                            'id' => $kinerja->id,
                            'nama' => $kinerja->subKegiatan?->nama,
                            'sasaran' => $kinerja->sasaran,
                            'indikator' => $kinerja->indikator,
                            'type' => 'kinerja-sub-kegiatan',
                        ];
                    });
                break;

            default:
                abort(404, 'Type tidak valid');
                break;
        }

        return response()->json($data);
    }

    public function store(string $type, int $id, Request $request)
    {
        $validated = $request->validate([
            'masalah_id' => ['required', 'numeric'],
            'masalah_type' => ['required', 'string'],
        ]);

        $validated['masalah_type'] = SolusiKinerja::parseTypeClass($validated['masalah_type']);
        $type = SolusiKinerja::parseTypeClass($type);

        SolusiKinerja::query()
            ->updateOrCreate([
                'solusi_id' => $id,
                'solusi_type' => $type,
            ], [
                'masalah_id' => $validated['masalah_id'],
                'masalah_type' => $validated['masalah_type'],
            ]);

        return response()->json(['status' => true]);
    }

    public function destroy(string $type, int $id)
    {
        SolusiKinerja::query()
            ->where('solusi_id', $id)
            ->where('solusi_type', SolusiKinerja::parseTypeClass($type))
            ->delete();

        return response()->json(['status' => true]);
    }

    private function getPrevTahunKinerja()
    {
        return getTahunKinerja() - 1;
    }

    public function solusiMasalah(Request $request)
    {
        $validated = $request->validate([
            'type' => ['required', Rule::in(['kinerja-program', 'kinerja-kegiatan', 'kinerja-sub-kegiatan'])],
            'id' => ['required', 'integer'],
        ]);

        $relations = [
            'strukturOrganisasi:id,jabatan_nama',
            'solusi.masalah.strukturOrganisasi:id,jabatan_nama',
        ];

        $getPengampu = function ($kinerja) {
            if ($kinerja->pengampu == 'unit-kerja') {
                return $kinerja->strukturOrganisasi?->jabatan_nama;
            } elseif ($kinerja->timKerja) {
                return "{$kinerja->timKerja->nama} - {$kinerja->timKerja->ketua?->peg_nama}";
            } else {
                return null;
            }
        };

        switch ($validated['type']) {
            case 'kinerja-program':
                $solusi = KinerjaProgram::find($validated['id'], ['id', 'sasaran', 'indikator', 'target', 'realisasi', 'capaian', 'v_struktur_organisasi_id', 'tahun_kinerja', 'program_id']);
                $solusi->load(array_merge($relations, [
                    'program:id,nama',

                    'solusi.masalah:id,sasaran,indikator,program_id,v_struktur_organisasi_id,target,realisasi,capaian',
                    'solusi.masalah.program:id,nama',
                    'solusi.masalah.kinerjaTidakTercapai' => fn (Builder $query) => $query->where('tahun_kinerja', $solusi->tahun_kinerja - 1),
                ]));

                $solusi->nama = $solusi->program->nama;
                $solusi->pengampu = $solusi->strukturOrganisasi?->jabatan_nama;

                $solusi->solusi->masalah->nama = $solusi->solusi->masalah->program->nama;
                break;
            case 'kinerja-kegiatan':
                $solusi = KinerjaKegiatan::find($validated['id'], ['id', 'sasaran', 'indikator', 'target', 'realisasi', 'capaian', 'v_struktur_organisasi_id', 'tahun_kinerja', 'kegiatan_id', 'pengampu', 'tim_kerja_id']);
                $solusi->load(array_merge($relations, [
                    'timKerja:id,nama,nip_ketua',
                    'timKerja.ketua:peg_nip,peg_nama',
                    'kegiatan:id,nama',
                    'kinerjaSubKegiatan:id,kinerja_kegiatan_id,sasaran,indikator,target,realisasi,capaian,sub_kegiatan_id',
                    'kinerjaSubKegiatan.subKegiatan:id,nama',

                    'solusi.masalah:id,sasaran,indikator,kegiatan_id,v_struktur_organisasi_id,target,realisasi,capaian,pengampu,tim_kerja_id',
                    'solusi.masalah.kegiatan:id,nama',
                    'solusi.masalah.timKerja:id,nama,nip_ketua',
                    'solusi.masalah.timKerja.ketua:peg_nip,peg_nama',
                    'solusi.masalah.kinerjaTidakTercapai' => fn (Builder $query) => $query->where('tahun_kinerja', $solusi->tahun_kinerja - 1),
                    'solusi.masalah.kinerjaSubKegiatan:id,kinerja_kegiatan_id,sasaran,indikator,target,realisasi,capaian,sub_kegiatan_id,penyebab_kegagalan',
                    'solusi.masalah.kinerjaSubKegiatan.subKegiatan:id,nama',
                    'solusi.masalah.kinerjaSubKegiatan.kinerjaTidakTercapai' => fn (Builder $query) => $query->where('tahun_kinerja', $solusi->tahun_kinerja - 1),
                ]));

                $solusi->nama = $solusi->kegiatan->nama;
                $solusi->pengampu = $getPengampu($solusi);

                $solusi->children = $solusi->kinerjaSubKegiatan->map(function (KinerjaSubKegiatan $kinerja) {
                    $kinerja->nama = $kinerja->subKegiatan?->nama ?? '-';
                    unset($kinerja->subKegiatan);

                    return $kinerja;
                });
                unset($solusi->kinerjaSubKegiatan);

                $solusi->solusi->masalah->nama = $solusi->solusi->masalah->kegiatan->nama;
                $solusi->solusi->masalah->pengampu = $getPengampu($solusi->solusi->masalah);

                $solusi->solusi->masalah->children = $solusi->solusi->masalah->kinerjaSubKegiatan->map(function (KinerjaSubKegiatan $kinerja) {
                    $kinerja->nama = $kinerja->subKegiatan?->nama ?? '-';
                    unset($kinerja->subKegiatan);

                    return $kinerja;
                });
                unset($solusi->solusi->masalah->kinerjaSubKegiatan);
                break;
            case 'kinerja-sub-kegiatan':
                $solusi = KinerjaSubKegiatan::find($validated['id'], ['id', 'sasaran', 'indikator', 'target', 'realisasi', 'capaian', 'v_struktur_organisasi_id', 'tahun_kinerja', 'sub_kegiatan_id', 'pengampu', 'tim_kerja_id']);
                $solusi->load(array_merge($relations, [
                    'timKerja:id,nama,nip_ketua',
                    'timKerja.ketua:peg_nip,peg_nama',
                    'subKegiatan:id,nama',
                    'kinerjaLangkahAksi:id,kinerja_sub_kegiatan_id,sasaran,indikator,target,realisasi,capaian,langkah_aksi AS nama',

                    'solusi.masalah:id,sasaran,indikator,sub_kegiatan_id,v_struktur_organisasi_id,target,realisasi,capaian,pengampu,tim_kerja_id',
                    'solusi.masalah.subKegiatan:id,nama',
                    'solusi.masalah.timKerja:id,nama,nip_ketua',
                    'solusi.masalah.timKerja.ketua:peg_nip,peg_nama',
                    'solusi.masalah.kinerjaTidakTercapai' => fn (Builder $query) => $query->where('tahun_kinerja', $solusi->tahun_kinerja - 1),
                    'solusi.masalah.kinerjaLangkahAksi:id,kinerja_sub_kegiatan_id,sasaran,indikator,target,realisasi,capaian,langkah_aksi AS nama,penyebab_kegagalan',
                    'solusi.masalah.kinerjaLangkahAksi.kinerjaTidakTercapai' => fn (Builder $query) => $query->where('tahun_kinerja', $solusi->tahun_kinerja - 1),
                ]));

                $solusi->nama = $solusi->subKegiatan->nama;
                $solusi->pengampu = $getPengampu($solusi);
                $solusi->children = $solusi->kinerjaLangkahAksi;
                unset($solusi->kinerjaLangkahAksi);

                $solusi->solusi->masalah->nama = optional($solusi->solusi->masalah->subKegiatan)->nama ?? 'Sub Kegiatan tidak ditemukan';
                $solusi->solusi->masalah->pengampu = $getPengampu($solusi->solusi->masalah);
                $solusi->solusi->masalah->children = $solusi->solusi->masalah->kinerjaLangkahAksi;
                unset($solusi->solusi->masalah->kinerjaLangkahAksi);
                break;

            default:
                throw new Exception('Tipe tidak dikenali');
                break;
        }

        $masalah = $solusi->solusi->masalah;

        return response()->json([
            'masalah' => $masalah,
            'solusi' => $solusi,
        ]);
    }
}
