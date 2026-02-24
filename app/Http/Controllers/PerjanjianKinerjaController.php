<?php

namespace App\Http\Controllers;

use App\Http\Requests\VisiMisiRpjmd\StoreVisiMisiRpjmd;
use App\Http\Requests\VisiMisiRpjmd\UpdateVisiMisiRpjmd;
use App\Jobs\PerjanjianKinerja;
use App\Models\Ekinerja\TimKerja;
use App\Models\KinerjaKegiatan;
use App\Models\KinerjaProgram;
use App\Models\KinerjaSubKegiatan;
use App\Models\LinkPerjanjianKinerja;
use App\Models\Role;
use App\Models\SasaranStrategisPd;
use App\Models\Simpeg\VPegawaiData;
use App\Models\Simpeg\VStrukturOrganisasi;
use App\Models\VisiMisiRpjmd;
use App\Traits\SetdaResourceAccess;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\TemplateProcessor;

/**
 * Khusus Kinerja Makro di Sekretariat Daerah,
 * yang bisa olah data adalah akun `setda`, akun `biro` view-only,
 * `satuan_kerja_id` nya juga di set ke `setda`
 */
class PerjanjianKinerjaController extends Controller
{
    use SetdaResourceAccess;

    public function index(Request $request, bool $isExport = false)
    {

        $skp = KinerjaProgram::tahunKinerja()->roleSatuanKerja()
            ->select('v_struktur_organisasi_id', 'pengampu', 'tim_kerja_id')
            ->union(KinerjaKegiatan::tahunKinerja()->roleSatuanKerja()
                ->select('v_struktur_organisasi_id', 'pengampu', 'tim_kerja_id')
            )
            ->union(KinerjaSubKegiatan::tahunKinerja()->roleSatuanKerja()
                ->select('v_struktur_organisasi_id', 'pengampu', 'tim_kerja_id')
            )
            ->groupBy('pengampu', 'v_struktur_organisasi_id', 'tim_kerja_id')
            ->get();

        $data = collect([]);
        $org = VStrukturOrganisasi::whereIn('id', $skp->pluck('v_struktur_organisasi_id'))->pluck('jabatan_id');
        $tim = TimKerja::whereIn('id', $skp->pluck('tim_kerja_id'))->pluck('nip_ketua');
        $user = Auth::user();
        $data = VPegawaiData::where(function ($q2) use ($org, $tim) {
            $q2->where('peg_status', true)->where(function ($q) use ($org, $tim) {
                $q->whereIn('jabatan_id', $org)->orWhereIn('peg_nip', $tim);
            });
        })->orwhere(function ($q3) use ($user) {
            $q3->where('peg_status', true)->whereNull('unit_kerja_id')->where('satuan_kerja_id', $user->satuan_kerja_id)->where('jabatan_jenis', 2);
        })->orderBy('eselon_id');

        if (! $isExport) {
            $data = $data->paginate(20);
        } else {
            $data = $data->get();
        }
        foreach ($data as &$d) {
            if (! $d->unit_kerja_id && $d->jabatan_jenis == 2) {
                $d->nip_atasan = '197004151996031001';
                $d->nama_atasan = 'BEY TRIADI MACHMUDIN';
            }
        }
        // $tidakTercapai = $tidakTercapai->get();
        /**
         * @var \Illuminate\Pagination\AbstractPaginator $data
         */
        // $data->setCollection($data->getCollection()
        //     ->transform(function (VisiMisiRpjmd $item) use ($tidakTercapai) {
        //         $item->tercapai = ! $tidakTercapai->contains($item->id);

        //         return $item;
        //     })
        // );

        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreVisiMisiRpjmd $request) {}

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(VisiMisiRpjmd $visiMisiRpjmd) {}

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(VisiMisiRpjmd $visiMisiRpjmd) {}

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateVisiMisiRpjmd $request, VisiMisiRpjmd $visiMisiRpjmd) {}

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(VisiMisiRpjmd $visiMisiRpjmd)
    {
        $this->authorizeBySatuanKerja($visiMisiRpjmd->satuan_kerja_id, [Role::SUPER, Role::PEMERINTAH_DAERAH, Role::SETDA]);

        $visiMisiRpjmd->delete();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil hapus data',
        ]);
    }

    public function export(Request $request)
    {
        $req = $request->all();
        $nip = $req['nip'];
        $tahun = getTahunKinerja();
        $tanggal = $req['tanggal'];
        $pegawai = VPegawaiData::where('peg_nip', $nip)->first();
        $tim = TimKerja::where('nip_ketua', $nip)->pluck('id');
        if (! $pegawai->unit_kerja_id && $pegawai->jabatan_jenis == 2) {
            $atasan = new VPegawaiData;
            $atasan->peg_nama = 'BEY TRIADI MACHMUDIN';
            $atasan->peg_nip = '197004151996031001';
            $atasan->jabatan_nama = 'PJ GUBERNUR JAWA BARAT';
            $atasan->nm_pkt_akhir = 'PEMBINA UTAMA';
            $atasan->nm_gol_akhir = 'IV/e';
            $program = \DB::table('program')
                ->select('id', 'nama');
            $target = (getTahunKinerja() - getTahunMulai()) + 1;
            $skp = SasaranStrategisPd::TahunMulai()->roleSatuanKerja()
                ->selectRaw('sasaran_strategis_satker as sasaran,iku as indikator,target_'.$target.'::VARCHAR as target,satuan')->get();
            $program = KinerjaProgram::tahunKinerja()->selectRaw('program.nama as sasaran, sum(anggaran) as anggaran')
                ->JoinSub($program, 'program', function ($joinprogram) {
                    $joinprogram->on('program.id', '=', 'program_id');
                })
                ->roleSatuanKerja()
                ->groupBy(\DB::raw('program.nama'))
                ->get();

        } else {
            $atasan = VPegawaiData::where('peg_nip', $pegawai->nip_atasan)->first();
            if ($atasan->satuan_kerja_id != $pegawai->satuan_kerja_id) {
                $atasan->jabatan_nama = 'PLT. '.$atasan->tugas_tambahan_jabatan_nama;
            }
            $program = \DB::table('program')
                ->select('id', 'nama');
            $kegiatan = \DB::table('kegiatan')
                ->select('id', 'nama');
            $subkegiatan = \DB::table('sub_kegiatan')
                ->select('id', 'nama');
            $skp = KinerjaProgram::tahunKinerja()->roleSatuanKerja()
                ->selectRaw('kinerja_program.sasaran,kinerja_program.indikator,kinerja_program.target::VARCHAR,kinerja_program.satuan')
                ->JoinSub($program, 'program', function ($joinprogram) {
                    $joinprogram->on('program.id', '=', 'program_id');
                })
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
                ->union(KinerjaKegiatan::tahunKinerja()->roleSatuanKerja()
                    ->selectRaw('kegiatan.nama as sasaran,kinerja_kegiatan.indikator,kinerja_kegiatan.target::VARCHAR,kinerja_kegiatan.satuan')
                    ->JoinSub($kegiatan, 'kegiatan', function ($joinkegiatan) {
                        $joinkegiatan->on('kegiatan.id', '=', 'kegiatan_id');
                    })
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
                ->union(KinerjaSubKegiatan::tahunKinerja()->roleSatuanKerja()
                    ->selectRaw('sub_kegiatan.nama as sasaran,kinerja_sub_kegiatan.indikator,kinerja_sub_kegiatan.target::VARCHAR,kinerja_sub_kegiatan.satuan')
                    ->JoinSub($subkegiatan, 'sub_kegiatan', function ($joinsubkegiatan) {
                        $joinsubkegiatan->on('sub_kegiatan.id', '=', 'sub_kegiatan_id');
                    })
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

            $program = KinerjaProgram::tahunKinerja()->selectRaw("program.nama as sasaran, sum(anggaran) as anggaran, 'Program' as jenis")
                ->JoinSub($program, 'program', function ($joinprogram) {
                    $joinprogram->on('program.id', '=', 'program_id');
                })
                ->roleSatuanKerja()
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
                ->groupBy(\DB::raw('program.nama'))
                ->union(KinerjaKegiatan::tahunKinerja()->roleSatuanKerja()
                    ->selectRaw("kegiatan.nama as sasaran, sum(anggaran) as anggaran, 'Kegiatan' as jenis")
                    ->JoinSub($kegiatan, 'kegiatan', function ($joinkegiatan) {
                        $joinkegiatan->on('kegiatan.id', '=', 'kegiatan_id');
                    })
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
                    ->groupBy(\DB::raw('kegiatan.nama'))
                )
                ->union(KinerjaSubKegiatan::tahunKinerja()->roleSatuanKerja()
                    ->selectRaw("sub_kegiatan.nama as sasaran, sum(anggaran) as anggaran, 'Sub Kegiatan' as jenis")
                    ->JoinSub($subkegiatan, 'sub_kegiatan', function ($joinsubkegiatan) {
                        $joinsubkegiatan->on('sub_kegiatan.id', '=', 'sub_kegiatan_id');
                    })
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
                    ->groupBy(\DB::raw('sub_kegiatan.nama'))
                )
                ->get();
        }

        $phpWord = new PhpWord;

        if (count($program) > 0) {
            $templateProcessor = new TemplateProcessor(public_path('template_perjanjian_kinerja_program.docx'));
        } else {
            $templateProcessor = new TemplateProcessor(public_path('template_perjanjian_kinerja.docx'));
        }

        $templateProcessor->setValue('pegawainama', $pegawai->peg_nama);
        $templateProcessor->setValue('pegawaijabatan', $pegawai->jabatan_nama);
        $templateProcessor->setValue('pegawaigol', ($pegawai->nm_pkt_akhir.', '.$pegawai->nm_gol_akhir));
        $templateProcessor->setValue('atasannama', $atasan->peg_nama);
        $templateProcessor->setValue('atasanjabatan', $atasan->jabatan_nama);
        $templateProcessor->setValue('atasangol', ($atasan->nm_pkt_akhir.', '.$atasan->nm_gol_akhir));
        $templateProcessor->setValue('tanggal', $this->getFullDate($tanggal));
        $templateProcessor->setValue('tahun', $tahun);
        $templateProcessor->setValue('jenis', count($program) > 0 ? $program[0]->jenis : 'Program');
        $valuesskp = [];
        $id = 1;
        foreach ($skp as $data) {
            $valuesskp[] = ['skpid' => $id++, 'skpsasaran' => $data->sasaran, 'skpindikator' => $data->indikator, 'skptarget' => ($data->target.' '.$data->satuan)];
        }
        $templateProcessor->cloneRowAndSetValues('skpid', $valuesskp);

        if (count($program) > 0) {
            $valueprogram = [];
            $id = 1;
            foreach ($program as $data) {
                $valueprogram[] = ['programid' => $id++, 'programsasaran' => $data->sasaran, 'programanggaran' => ('Rp.'.number_format($data->anggaran, 2)), 'programketerangan' => 'APBD'];
            }
            $templateProcessor->cloneRowAndSetValues('programid', $valueprogram);
        }

        $outputPath = storage_path('app/temp/generated.docx');
        $templateProcessor->saveAs($outputPath);

        // Return the document as a download
        return response()->download($outputPath, 'customized.docx')->deleteFileAfterSend(true);
        // $phpWord = new \PhpOffice\PhpWord\PhpWord();

        // $pdf = Pdf::loadView('perjanjian-kinerja.perjanjian', $data);

        // return $pdf->download('perjanjian kinerja.pdf');
    }

    public function exportPdf(Request $request)
    {
        $req = $request->all();
        $nip = $req['nip'];
        $tahun = getTahunKinerja();
        $tanggal = $req['tanggal'];
        $pegawai = VPegawaiData::where('peg_nip', $nip)->first();
        $tim = TimKerja::where('nip_ketua', $nip)->pluck('id');

        if (! $pegawai->unit_kerja_id && $pegawai->jabatan_jenis == 2) {
            $atasan = new VPegawaiData;
            $atasan->peg_nama = 'BEY TRIADI MACHMUDIN';
            $atasan->peg_nip = '197004151996031001';
            $atasan->jabatan_nama = 'PJ GUBERNUR JAWA BARAT';
            $atasan->nm_pkt_akhir = 'PEMBINA UTAMA';
            $atasan->nm_gol_akhir = 'IV/e';
            $program = \DB::table('program')
                ->select('id', 'nama');
            $target = (getTahunKinerja() - getTahunMulai()) + 1;
            $skp = SasaranStrategisPd::TahunMulai()->roleSatuanKerja()
                ->selectRaw('sasaran_strategis_satker as sasaran,iku as indikator,target_'.$target.'::VARCHAR as target,satuan')->get();
            $program = KinerjaProgram::tahunKinerja()->selectRaw('program.nama as sasaran, sum(anggaran) as anggaran')
                ->JoinSub($program, 'program', function ($joinprogram) {
                    $joinprogram->on('program.id', '=', 'program_id');
                })
                ->roleSatuanKerja()
                ->groupBy(\DB::raw('program.nama'))
                ->get();

        } else {
            $atasan = VPegawaiData::where('peg_nip', $pegawai->nip_atasan)->first();
            if ($atasan->satuan_kerja_id != $pegawai->satuan_kerja_id) {
                $atasan->jabatan_nama = 'PLT. '.$atasan->tugas_tambahan_jabatan_nama;
            }
            $program = \DB::table('program')
                ->select('id', 'nama');
            $kegiatan = \DB::table('kegiatan')
                ->select('id', 'nama');
            $subkegiatan = \DB::table('sub_kegiatan')
                ->select('id', 'nama');
            $skp = KinerjaProgram::tahunKinerja()->roleSatuanKerja()
                ->selectRaw('program.nama as sasaran,kinerja_program.indikator,kinerja_program.target::VARCHAR,kinerja_program.satuan')
                ->JoinSub($program, 'program', function ($joinprogram) {
                    $joinprogram->on('program.id', '=', 'program_id');
                })
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
                ->union(KinerjaKegiatan::tahunKinerja()->roleSatuanKerja()
                    ->selectRaw('kegiatan.nama as sasaran,kinerja_kegiatan.indikator,kinerja_kegiatan.target::VARCHAR,kinerja_kegiatan.satuan')
                    ->JoinSub($kegiatan, 'kegiatan', function ($joinkegiatan) {
                        $joinkegiatan->on('kegiatan.id', '=', 'kegiatan_id');
                    })
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
                ->union(KinerjaSubKegiatan::tahunKinerja()->roleSatuanKerja()
                    ->selectRaw('sub_kegiatan.nama as sasaran,kinerja_sub_kegiatan.indikator,kinerja_sub_kegiatan.target::VARCHAR,kinerja_sub_kegiatan.satuan')
                    ->JoinSub($subkegiatan, 'sub_kegiatan', function ($joinsubkegiatan) {
                        $joinsubkegiatan->on('sub_kegiatan.id', '=', 'sub_kegiatan_id');
                    })
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

            $program = KinerjaProgram::tahunKinerja()->selectRaw("program.nama as sasaran, sum(anggaran) as anggaran, 'Program' as jenis")
                ->JoinSub($program, 'program', function ($joinprogram) {
                    $joinprogram->on('program.id', '=', 'program_id');
                })
                ->roleSatuanKerja()
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
                ->groupBy(\DB::raw('program.nama'))
                ->union(KinerjaKegiatan::tahunKinerja()->roleSatuanKerja()
                    ->selectRaw("kegiatan.nama as sasaran, sum(anggaran) as anggaran, 'Kegiatan' as jenis")
                    ->JoinSub($kegiatan, 'kegiatan', function ($joinkegiatan) {
                        $joinkegiatan->on('kegiatan.id', '=', 'kegiatan_id');
                    })
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
                    ->groupBy(\DB::raw('kegiatan.nama'))
                )
                ->union(KinerjaSubKegiatan::tahunKinerja()->roleSatuanKerja()
                    ->selectRaw("sub_kegiatan.nama as sasaran, sum(anggaran) as anggaran, 'Sub Kegiatan' as jenis")
                    ->JoinSub($subkegiatan, 'sub_kegiatan', function ($joinsubkegiatan) {
                        $joinsubkegiatan->on('sub_kegiatan.id', '=', 'sub_kegiatan_id');
                    })
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
                    ->groupBy(\DB::raw('sub_kegiatan.nama'))
                )
                ->get();
        }
        $data = [

            'pegawai' => $pegawai,
            'atasan' => $atasan,
            'tahun' => $tahun,
            'tanggal' => $this->getFullDate($tanggal),
            'sasaran' => $skp,
            'program_anggaran' => $program,
        ];

        $pdf = Pdf::loadView('perjanjian-kinerja.perjanjian', $data);

        return $pdf->download('perjanjian kinerja.pdf');
    }

    public function exportAll(Request $request)
    {
        $req = $request->all();
        $tanggal = $req['tanggal'];
        $tahun = getTahunKinerja();
        $satuan_kerja_id = Auth::user()->satuan_kerja_id;
        $datapegawai = $this->index($request, true)->getData();

        $pdfsPath = storage_path('app/public/pdfs/'.$tahun.'/'.$satuan_kerja_id);
        $files = Storage::disk('local')->allFiles('/public/pdfs/'.$tahun.'/'.$satuan_kerja_id);
        // dd(file_exists($pdfsPath));
        // Ensure the directory exists
        if (! file_exists($pdfsPath)) {
            // dispatch(new PerjanjianKinerja($satuan_kerja_id, $tanggal, $tahun));
            PerjanjianKinerja::dispatch($satuan_kerja_id, $tanggal, $tahun);

            return response()->json([
                'success' => false,
                'message' => 'Data dalam proses generate, silakan tunggu beberapa saat dan klik tombol export kembali',
            ], 404);
        }

        if (count($files) != count($datapegawai)) {
            return response()->json([
                'success' => false,
                'message' => 'Data sedang dalam proses generate, silakan tunggu beberapa saat dan klik tombol export kembali',
            ], 404);
        }

        $zip_file = 'Perjanjian Kinerja.zip';
        $zip = new \ZipArchive;
        $zip->open($zip_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        $path = storage_path('app/public/pdfs/'.$tahun.'/'.$satuan_kerja_id);
        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));
        foreach ($files as $name => $file) {
            // We're skipping all subfolders
            if (! $file->isDir()) {
                $filePath = $file->getRealPath();

                // extracting filename with substr/strlen
                $relativePath = 'perjanjian-kinerja/'.substr($filePath, strlen($path) + 1);

                $zip->addFile($filePath, $relativePath);
            }
        }
        $zip->close();

        return response()->download($zip_file);

        // return response()->download($destination);
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

    public function storeLink(Request $request)
    {
        // Validate input data
        $request->validate([
            'link' => 'required|url',
        ]);

        // Check if data exists, if yes update; otherwise, create new
        $data = LinkPerjanjianKinerja::updateOrCreate(
            [
                'tahun_kinerja' => getTahunKinerja(),
                'satuan_kerja_id' => Auth::user()->satuan_kerja_id,
            ],
            [
                'link' => $request->link,
            ]
        );

        return response()->json([
            'success' => true,
        ]);
    }
}
