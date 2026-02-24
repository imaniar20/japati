<?php

namespace App\Jobs;

use App\Models\Ekinerja\TimKerja;
use App\Models\KinerjaKegiatan;
use App\Models\KinerjaProgram;
use App\Models\KinerjaSubKegiatan;
use App\Models\Simpeg\VPegawaiData;
use App\Models\Simpeg\VStrukturOrganisasi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class PerjanjianKinerja implements ShouldQueue
{
    use Queueable;

    private $satker;

    private $tanggal;

    private $tahun;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $satker, string $tanggal, string $tahun)
    {
        $this->satker = $satker;
        $this->tanggal = $tanggal;
        $this->tahun = $tahun;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $satker = $this->satker;
        $tanggal = $this->tanggal;
        $tahun = $this->tahun;
        $tanggal = $this->getFullDate($tanggal);
        $satuan_kerja_id = $satker;
        $pdfsPath = storage_path('app/public/pdfs/'.$tahun.'/'.$satuan_kerja_id);
        // Ensure the directory exists
        if (! file_exists($pdfsPath)) {
            mkdir($pdfsPath, 0755, true);
        }

        $datapegawai = $this->index($tahun, $satker)->getData();
        foreach ($datapegawai as $peg) {
            $nip = $peg->peg_nip;

            if (file_exists($pdfsPath.'/Perjanjian Kinerja '.$peg->peg_nip.' Tahun '.$tahun.'.pdf')) {
                continue;
            }

            $pegawai = VPegawaiData::where('peg_nip', $nip)->first();
            $tim = TimKerja::where('nip_ketua', $nip)->pluck('id');
            $atasan = VPegawaiData::where('peg_nip', $pegawai->nip_atasan)->first();

            $skp = KinerjaProgram::where('tahun_kinerja', $tahun)->where('satuan_kerja_id', $satuan_kerja_id)
                ->selectRaw('sasaran,indikator,target::VARCHAR,satuan')
                ->where(function ($q) use ($pegawai, $tim) {
                    if ($pegawai->jabatan_jenis == 2) {
                        $q->where(function ($q2) use ($pegawai) {
                            $q2->where('v_struktur_organisasi_id', $pegawai->unit_kerja_id)->where('pengampu', 'unit-kerja');
                        })->orwhere(function ($q3) use ($tim) {
                            $q3->whereIn('tim_kerja_id', $tim)->where('pengampu', 'tim-kerja');
                        });
                    } else {
                        $q->where(function ($q3) use ($tim) {
                            $q3->whereIn('tim_kerja_id', $tim)->where('pengampu', 'tim-kerja');
                        });
                    }
                })
                ->union(KinerjaKegiatan::where('tahun_kinerja', $tahun)->where('satuan_kerja_id', $satuan_kerja_id)
                    ->selectRaw('sasaran,indikator,target::VARCHAR,satuan')
                    ->where(function ($q) use ($pegawai, $tim) {
                        if ($pegawai->jabatan_jenis == 2) {
                            $q->where(function ($q2) use ($pegawai) {
                                $q2->where('v_struktur_organisasi_id', $pegawai->unit_kerja_id)->where('pengampu', 'unit-kerja');
                            })->orwhere(function ($q3) use ($tim) {
                                $q3->whereIn('tim_kerja_id', $tim)->where('pengampu', 'tim-kerja');
                            });
                        } else {
                            $q->where(function ($q3) use ($tim) {
                                $q3->whereIn('tim_kerja_id', $tim)->where('pengampu', 'tim-kerja');
                            });
                        }
                    })
                )
                ->union(KinerjaSubKegiatan::where('tahun_kinerja', $tahun)->where('satuan_kerja_id', $satuan_kerja_id)
                    ->selectRaw('sasaran,indikator,target::VARCHAR,satuan')
                    ->where(function ($q) use ($pegawai, $tim) {
                        if ($pegawai->jabatan_jenis == 2) {
                            $q->where(function ($q2) use ($pegawai) {
                                $q2->where('v_struktur_organisasi_id', $pegawai->unit_kerja_id)->where('pengampu', 'unit-kerja');
                            })->orwhere(function ($q3) use ($tim) {
                                $q3->whereIn('tim_kerja_id', $tim)->where('pengampu', 'tim-kerja');
                            });
                        } else {
                            $q->where(function ($q3) use ($tim) {
                                $q3->whereIn('tim_kerja_id', $tim)->where('pengampu', 'tim-kerja');
                            });
                        }
                    })
                )
                ->get();
            $program = KinerjaProgram::where('tahun_kinerja', $tahun)->where('satuan_kerja_id', $satuan_kerja_id)
                ->where(function ($q) use ($pegawai, $tim) {
                    if ($pegawai->jabatan_jenis == 2) {
                        $q->where(function ($q2) use ($pegawai) {
                            $q2->where('v_struktur_organisasi_id', $pegawai->unit_kerja_id)->where('pengampu', 'unit-kerja');
                        })->orwhere(function ($q3) use ($tim) {
                            $q3->whereIn('tim_kerja_id', $tim)->where('pengampu', 'tim-kerja');
                        });
                    } else {
                        $q->where(function ($q3) use ($tim) {
                            $q3->whereIn('tim_kerja_id', $tim)->where('pengampu', 'tim-kerja');
                        });
                    }
                })->get();
            $data = [

                'pegawai' => $pegawai,
                'atasan' => $atasan,
                'tahun' => $tahun,
                'tanggal' => $tanggal,
                'sasaran' => $skp,
                'program_anggaran' => $program,
            ];

            $pdf = PDF::loadView('perjanjian-kinerja.perjanjian', $data);
            $pdf->save($pdfsPath.'/Perjanjian Kinerja '.$peg->peg_nip.' Tahun '.$tahun.'.pdf');
        }
    }

    public function getFullDate($time = 0)
    {
        if ($time == '') {
            return $time;
        }
        if ($time && $time != '0000-00-00') {
            $time = strtotime($time);
        } else {
            return '-';
        }
        $listBulan = ['' => '-', 1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        return date('j', $time).' '.$listBulan[date('n', $time)].' '.date('Y', $time);
    }

    public function index($tahun, $satker)
    {

        $skp = KinerjaProgram::where('tahun_kinerja', $tahun)->where('satuan_kerja_id', $satker)
            ->select('v_struktur_organisasi_id', 'pengampu', 'tim_kerja_id')
            ->union(KinerjaKegiatan::where('tahun_kinerja', $tahun)->where('satuan_kerja_id', $satker)
                ->select('v_struktur_organisasi_id', 'pengampu', 'tim_kerja_id')
            )
            ->union(KinerjaSubKegiatan::where('tahun_kinerja', $tahun)->where('satuan_kerja_id', $satker)
                ->select('v_struktur_organisasi_id', 'pengampu', 'tim_kerja_id')
            )
            ->groupBy('pengampu', 'v_struktur_organisasi_id', 'tim_kerja_id')
            ->get();

        $data = collect([]);
        $org = VStrukturOrganisasi::whereIn('id', $skp->pluck('v_struktur_organisasi_id'))->pluck('jabatan_id');
        $tim = TimKerja::whereIn('id', $skp->pluck('tim_kerja_id'))->pluck('nip_ketua');

        $data = VPegawaiData::where('peg_status', true)->where(function ($q) use ($org, $tim) {
            $q->whereIn('jabatan_id', $org)->orWhereIn('peg_nip', $tim);
        })->orderBy('eselon_id');

        $data = $data->get();

        return response()->json($data);
    }
}
