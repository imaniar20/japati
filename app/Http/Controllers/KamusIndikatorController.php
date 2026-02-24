<?php

namespace App\Http\Controllers;

use App\Models\KinerjaKegiatan;
use App\Models\KinerjaProgram;
use App\Models\KinerjaSubKegiatan;
use App\Models\Role;
use App\Models\SasaranStrategisPd;
use App\Models\SasaranStrategisRpjmd;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class KamusIndikatorController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        if (Role::isSuper() || Role::isViewAll()) {
            $satkerId = $request->get('satuan_kerja_id', 1030);
        } elseif (Role::isSetda()) {
            $satkerId = $request->get('satuan_kerja_id', 100103010000);
        } else {
            $satkerId = null;
        }

        $statusValidasi = $request->get('status_validasi');

        $filterStatusValidasi = fn (Builder $query) => $query
            ->when(! is_null($statusValidasi), fn (Builder $query) => $query
                ->when(
                    $statusValidasi,
                    fn (Builder $query) => $query->whereHas('skp', fn (Builder $query) => $query->tahunKinerja()),
                    fn (Builder $query) => $query->whereDoesntHave('skp', fn (Builder $query) => $query->tahunKinerja()),
                )
            );

        $sasaranStrategisRpjmd = SasaranStrategisRpjmd::tahunMulai()
            ->roleSatuanKerja($satkerId)
            ->select('id', 'indikator_sasaran_strategis_id', 'updated_at')
            ->with([
                'indikatorSasaranStrategis:id,indikator',
            ])
            ->get()
            ->transform(fn (SasaranStrategisRpjmd $item) => [
                'tipe' => 'Indikator Sasaran Strategis RPJMD',
                'indikator' => $item->indikatorSasaranStrategis->indikator,
                'updated_at' => $item->updated_at,
            ]);

        $parseKeteranganPerubahan = function (object $item, ?object $skp) {
            if (! $skp) {
                return null;
            }

            $note = 'Terdapat perubahan data pada ';
            $diff = [];

            if ($item->sasaran != $skp->sasaran) {
                $diff[] = 'sasaran';
            }
            if ($item->indikator != $skp->indikator) {
                $diff[] = 'indikator';
            }
            if ($item->target != $skp->target) {
                $diff[] = 'target';
            }
            if ($item->satuan != $skp->satuan) {
                $diff[] = 'satuan';
            }
            if (
                $item->pengampu != $skp->pengampu
                || ($item->pengampu == $skp->pengampu && $item->pengampu == 'unit-kerja' && $item->v_struktur_organisasi_id != $skp->v_struktur_organisasi_id)
                || ($item->pengampu == $skp->pengampu && $item->pengampu == 'tim-kerja' && $item->tim_kerja_id != $skp->tim_kerja_id)
            ) {
                $diff[] = 'pengampu';
            }

            if (! count($diff)) {
                return null;
            }

            return $note.implode(', ', $diff);
        };

        $targetColumn = getKeyTahun('target');

        $sasaranStrategisPd = SasaranStrategisPd::tahunMulai()
            ->roleSatuanKerja($satkerId)
            ->selectRaw("id, iku indikator, updated_at, sasaran_strategis_satker sasaran, null pengampu, null v_struktur_organisasi_id, null tim_kerja_id, {$targetColumn} target, satuan")
            ->with([
                'skp' => fn (Builder $query) => $query->tahunKinerja(),
                'riwayatSkpRejectedLatest' => fn (Builder $query) => $query->tahunKinerja(),
                'riwayatSkpValidatedLatest' => fn (Builder $query) => $query->tahunKinerja(),
            ])
            ->where($filterStatusValidasi)
            ->get()
            ->transform(fn (SasaranStrategisPd $item) => [
                'id' => $item->id,
                'skp' => $item->skp->first(),
                'keterangan' => $item->riwayatSkpRejectedLatest->keterangan ?? null,
                'catatan_validasi' => $item->riwayatSkpValidatedLatest->keterangan ?? null,
                'tipe' => 'Indikator Sasaran Strategis Renstra PD',
                'class' => 'sasaran-strategis-pd',
                'indikator' => $item->indikator,
                'updated_at' => $item->updated_at,
                'keterangan_perubahan' => $parseKeteranganPerubahan($item, $item->skp->first()),
            ]);

        $kinerjaProgram = KinerjaProgram::tahunKinerja()
            ->roleSatuanKerja($satkerId)
            ->select('id', 'indikator', 'updated_at', 'pengampu', 'v_struktur_organisasi_id', 'tim_kerja_id', 'sasaran', 'target', 'satuan')
            ->with([
                'skp' => fn (Builder $query) => $query->tahunKinerja(),
                'riwayatSkpRejectedLatest' => fn (Builder $query) => $query->tahunKinerja(),
                'riwayatSkpValidatedLatest' => fn (Builder $query) => $query->tahunKinerja(),
            ])
            ->where($filterStatusValidasi)
            ->get()
            ->transform(fn (KinerjaProgram $item) => [
                'id' => $item->id,
                'skp' => $item->skp->first(),
                'keterangan' => $item->riwayatSkpRejectedLatest->keterangan ?? null,
                'catatan_validasi' => $item->riwayatSkpValidatedLatest->keterangan ?? null,
                'tipe' => 'Indikator Program',
                'class' => 'kinerja-program',
                'indikator' => $item->indikator,
                'updated_at' => $item->updated_at,
                'keterangan_perubahan' => $parseKeteranganPerubahan($item, $item->skp->first()),
            ]);

        $kinerjaKegiatan = KinerjaKegiatan::tahunKinerja()
            ->roleSatuanKerja($satkerId)
            ->select('id', 'indikator', 'updated_at', 'pengampu', 'v_struktur_organisasi_id', 'tim_kerja_id', 'sasaran', 'target', 'satuan')
            ->with([
                'skp' => fn (Builder $query) => $query->tahunKinerja(),
                'riwayatSkpRejectedLatest' => fn (Builder $query) => $query->tahunKinerja(),
                'riwayatSkpValidatedLatest' => fn (Builder $query) => $query->tahunKinerja(),
            ])
            ->where($filterStatusValidasi)
            ->get()
            ->transform(fn (KinerjaKegiatan $item) => [
                'id' => $item->id,
                'skp' => $item->skp->first(),
                'keterangan' => $item->riwayatSkpRejectedLatest->keterangan ?? null,
                'catatan_validasi' => $item->riwayatSkpValidatedLatest->keterangan ?? null,
                'tipe' => 'Indikator Kegiatan',
                'class' => 'kinerja-kegiatan',
                'indikator' => $item->indikator,
                'updated_at' => $item->updated_at,
                'keterangan_perubahan' => $parseKeteranganPerubahan($item, $item->skp->first()),
            ]);

        $kinerjaSubKegiatan = KinerjaSubKegiatan::tahunKinerja()
            ->roleSatuanKerja($satkerId)
            ->select('id', 'indikator', 'indikator_kemendagri', 'updated_at', 'pengampu', 'v_struktur_organisasi_id', 'tim_kerja_id', 'sasaran', 'target', 'satuan')
            ->with([
                'skp' => fn (Builder $query) => $query->tahunKinerja(),
                'riwayatSkpRejectedLatest' => fn (Builder $query) => $query->tahunKinerja(),
                'riwayatSkpValidatedLatest' => fn (Builder $query) => $query->tahunKinerja(),
            ])
            ->where($filterStatusValidasi)
            ->get()
            ->transform(fn (KinerjaSubKegiatan $item) => [
                'id' => $item->id,
                'skp' => $item->skp->first(),
                'keterangan' => $item->riwayatSkpRejectedLatest->keterangan ?? null,
                'catatan_validasi' => $item->riwayatSkpValidatedLatest->keterangan ?? null,
                'tipe' => 'Indikator Sub Kegiatan',
                'class' => 'kinerja-sub-kegiatan',
                'indikator' => $item->indikator,
                'indikator_kemendagri' => $item->indikator_kemendagri,
                'updated_at' => $item->updated_at,
                'keterangan_perubahan' => $parseKeteranganPerubahan($item, $item->skp->first()),
            ]);

        $data = [
            ...$sasaranStrategisRpjmd,
            ...$sasaranStrategisPd,
            ...$kinerjaProgram,
            ...$kinerjaKegiatan,
            ...$kinerjaSubKegiatan,
        ];

        return response()->json($data);
    }
}
