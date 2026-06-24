<?php

use App\Http\Controllers\AnggaranCapaianIkuController;
use App\Http\Controllers\AdminBannerController;
use App\Http\Controllers\AdminSatuanKerjaController;
use App\Http\Controllers\AdminSasaranIkuRpjmdController;
use App\Http\Controllers\AdminStrukturOrganisasiController;
use App\Http\Controllers\AdminVisiMisiController;
use App\Http\Controllers\BerbagiPeranController;
use App\Http\Controllers\DiagramIkuController;
use App\Http\Controllers\DisplayMakroController;
use App\Http\Controllers\DisplayMikroController;
use App\Http\Controllers\InfografisController;
use App\Http\Controllers\KamusIndikatorController;
use App\Http\Controllers\KamusIndikatorFungsionalController;
use App\Http\Controllers\KamusIndikatorFungsionalManualController;
use App\Http\Controllers\KamusIndikatorValidasiBappedaController;
use App\Http\Controllers\KinerjaBayanganController;
use App\Http\Controllers\KinerjaKabKotaController;
use App\Http\Controllers\KinerjaKegiatanController;
use App\Http\Controllers\KinerjaKegiatanCrossController;
use App\Http\Controllers\KinerjaLangkahAksiController;
use App\Http\Controllers\KinerjaLangkahAksiTerintegrasiController;
use App\Http\Controllers\KinerjaProgramController;
use App\Http\Controllers\KinerjaProgramCrossController;
use App\Http\Controllers\KinerjaSubKegiatanController;
use App\Http\Controllers\KinerjaSubKegiatanCrossController;
use App\Http\Controllers\KinerjaSubKegiatanKabKotaController;
use App\Http\Controllers\KinerjaTercapaiController;
use App\Http\Controllers\KinerjaTidakTercapaiController;
use App\Http\Controllers\LKE\CatatanRekomendasiController;
use App\Http\Controllers\LKE\CetakLaporanControllerLke;
use App\Http\Controllers\LKE\EvidenController;
use App\Http\Controllers\LKE\PenilaianController;
use App\Http\Controllers\LKE\PenilaianHumanisController;
use App\Http\Controllers\LKE\RekomendasiController;
use App\Http\Controllers\LKIPController;
use App\Http\Controllers\LKIPNarasiPDController;
use App\Http\Controllers\LKIPNarasiPemdaController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\NilaiJenjangKinerjaController;
use App\Http\Controllers\NilaiSakipPemdaController;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\PegawaiDataController;
use App\Http\Controllers\PenyebabKegagalanController;
use App\Http\Controllers\PerjanjianKinerjaController;
use App\Http\Controllers\ProgramDataController;
use App\Http\Controllers\PerubahanJumlahOutputV2Controller;
use App\Http\Controllers\PohonKinerjaController;
use App\Http\Controllers\PohonKinerjaInsertDataController;
use App\Http\Controllers\PublicDisplay\DisplayMakroController as PublicDisplayDisplayMakroController;
use App\Http\Controllers\PublicDisplay\DisplayMikroController as PublicDisplayDisplayMikroController;
use App\Http\Controllers\PublicDisplay\EvidenController as PublicDisplayEvidenController;
use App\Http\Controllers\PublicDisplay\PublicDisplayController;
use App\Http\Controllers\PublicDisplay\RencanaAksiController;
use App\Http\Controllers\RaporKinerjaController;
use App\Http\Controllers\RaporKinerjaSasaranStrategisPdController;
use App\Http\Controllers\SasaranStrategisPdController;
use App\Http\Controllers\SasaranStrategisPdCrossController;
use App\Http\Controllers\SasaranStrategisRpjmdController;
use App\Http\Controllers\SKPController;
use App\Http\Controllers\SolusiKinerjaController;
use App\Http\Controllers\TimKerjaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ValidasiKinerjaKegiatanController;
use App\Http\Controllers\ValidasiKinerjaProgramController;
use App\Http\Controllers\ValidasiKinerjaSubKegiatanController;
use App\Http\Controllers\ValidasiPerencanaanController;
use App\Http\Controllers\ValidasiSasaranStrategisPdController;
use App\Http\Controllers\VisiMisiRpjmdController;
use App\Models\Role;
use Illuminate\Support\Facades\Route;

Route::get('/', [UserController::class, 'hello']);
Route::get('/test', [KinerjaKabKotaController::class, 'test']);
Route::group(['middleware' => ['guest:api']], function () {
    Route::post('login', [LoginController::class, 'login']);
});

Route::prefix('tim-kerja')->middleware('auth:api')->group(function () {
    Route::get('/', [TimKerjaController::class, 'index']);
    Route::post('/', [TimKerjaController::class, 'store']);
    Route::get('pegawai', [TimKerjaController::class, 'searchPegawai']);
    Route::get('{timKerja}', [TimKerjaController::class, 'show'])->whereNumber('timKerja');
    Route::patch('{timKerja}', [TimKerjaController::class, 'update'])->whereNumber('timKerja');
    Route::delete('{timKerja}', [TimKerjaController::class, 'destroy'])->whereNumber('timKerja');
});

Route::prefix('pegawai-data')->middleware('auth:api')->group(function () {
    Route::get('/', [PegawaiDataController::class, 'index']);
    Route::post('/', [PegawaiDataController::class, 'store']);
    Route::get('{pegawaiData}', [PegawaiDataController::class, 'show'])->whereNumber('pegawaiData');
    Route::patch('{pegawaiData}', [PegawaiDataController::class, 'update'])->whereNumber('pegawaiData');
    Route::delete('{pegawaiData}', [PegawaiDataController::class, 'destroy'])->whereNumber('pegawaiData');
});

Route::prefix('program-data')->middleware('auth:api')->group(function () {
    Route::get('/', [ProgramDataController::class, 'index']);
    Route::post('/', [ProgramDataController::class, 'store']);
    Route::get('{program}', [ProgramDataController::class, 'show'])->whereNumber('program');
    Route::patch('{program}', [ProgramDataController::class, 'update'])->whereNumber('program');
    Route::delete('{program}', [ProgramDataController::class, 'destroy'])->whereNumber('program');
});

Route::get('/infografis', [InfografisController::class, 'index'])->middleware('tahun-kinerja-public');
Route::group(['middleware' => ['auth:api']], function () {
    Route::prefix('kinerja-kabkota')->group(function () {
        Route::get('/satuan-kerja/{satuanKerjaId}', [KinerjaKabKotaController::class, 'getKabKotaSatuanKerja']);
        Route::get('/kinerja-sub-kegiatan/{kinerjaKabuKotaId}/{satuanKerjaId}', [KinerjaKabKotaController::class, 'getKabKotaKinerjaSubKegiatan']);
        Route::get('/kinerja-sub-kegiatan-detail/{kinerjaKabuKotaId}/{kinerjaSubKegiatanId}', [KinerjaKabKotaController::class, 'getKabKotaKinerjaSubKegiatanDetail']);

    });
    Route::post('logout', [LoginController::class, 'logout']);
    Route::get('user', [UserController::class, 'show']);
    Route::get('visi-misi-rpjmd/export', [VisiMisiRpjmdController::class, 'export']);
    Route::resource('visi-misi-rpjmd', VisiMisiRpjmdController::class);
    Route::get('kinerja-bayangan/export', [KinerjaBayanganController::class, 'export']);
    Route::resource('kinerja-bayangan', KinerjaBayanganController::class)->parameter('kinerja-bayangan', 'kinerjaBayangan')->except('create');

    Route::prefix('sasaran-strategis-rpjmd')->group(function () {
        Route::get('export', [SasaranStrategisRpjmdController::class, 'export']);

        Route::prefix('{sasaranStrategisRpjmdId}')->group(function () {
            Route::prefix('sasaran-strategis-pd-cross')->group(function () {
                Route::get('/', [SasaranStrategisPdCrossController::class, 'index']);
                Route::post('/', [SasaranStrategisPdCrossController::class, 'set']);
            });
        });
    });

    Route::resource('sasaran-strategis-rpjmd', SasaranStrategisRpjmdController::class)->parameter('sasaran-strategis-rpjmd', 'sasaranStrategisRpjmd');

    Route::prefix('sasaran-strategis-pd')->group(function () {
        Route::post('{sasaranStrategisPd}/sync', [SasaranStrategisPdController::class, 'sync']);
        Route::post('upload', [SasaranStrategisPdController::class, 'upload']);
        Route::get('export', [SasaranStrategisPdController::class, 'export']);
        Route::post('upload-definisi-operasional', [SasaranStrategisPdController::class, 'uploadDefinisiOperasional']);

        Route::prefix('{sasaranStrategisPdId}')->group(function () {
            Route::prefix('kinerja-program-cross')->group(function () {
                Route::get('/', [KinerjaProgramCrossController::class, 'index']);
                Route::post('/', [KinerjaProgramCrossController::class, 'set']);
            });
        });

        Route::prefix('validasi')->middleware(roleMiddleware(Role::VALIDATOR_BAPPEDA))->group(function () {
            Route::get('/', [ValidasiSasaranStrategisPdController::class, 'index']);
            Route::post('/', [ValidasiSasaranStrategisPdController::class, 'validasi']);
        });

        Route::prefix('validasi-pengampu')->middleware(roleMiddleware(Role::VALIDATOR_PENGAMPU))->group(function () {
            Route::get('/', [ValidasiSasaranStrategisPdController::class, 'pengampuIndex']);
            Route::post('/', [ValidasiSasaranStrategisPdController::class, 'pengampuValidasi']);
        });
    });

    Route::resource('sasaran-strategis-pd', SasaranStrategisPdController::class)->parameter('sasaran-strategis-pd', 'sasaranStrategisPd');

    Route::prefix('kinerja-program')->group(function () {
        Route::get('export', [KinerjaProgramController::class, 'export']);
        Route::post('upload-definisi-operasional', [KinerjaProgramController::class, 'uploadDefinisiOperasional']);

        Route::prefix('{kinerjaProgramId}')->group(function () {
            Route::prefix('kinerja-kegiatan-cross')->group(function () {
                Route::get('/', [KinerjaKegiatanCrossController::class, 'index']);
                Route::post('/', [KinerjaKegiatanCrossController::class, 'set']);
            });
        });

        Route::prefix('validasi')->middleware(roleMiddleware(Role::VALIDATOR_BAPPEDA))->group(function () {
            Route::get('/', [ValidasiKinerjaProgramController::class, 'index']);
            Route::post('/', [ValidasiKinerjaProgramController::class, 'validasi']);
        });

        Route::prefix('validasi-pengampu')->middleware(roleMiddleware(Role::VALIDATOR_PENGAMPU))->group(function () {
            Route::get('/', [ValidasiKinerjaProgramController::class, 'pengampuIndex']);
            Route::post('/', [ValidasiKinerjaProgramController::class, 'pengampuValidasi']);
        });
    });

    Route::resource('kinerja-program', KinerjaProgramController::class)->parameter('kinerja-program', 'kinerjaProgram');

    Route::prefix('kinerja-kegiatan')->group(function () {
        Route::post('{kinerjaKegiatan}/sync', [KinerjaKegiatanController::class, 'sync']);
        Route::get('export', [KinerjaKegiatanController::class, 'export']);
        Route::post('upload-definisi-operasional', [KinerjaKegiatanController::class, 'uploadDefinisiOperasional']);

        Route::prefix('{kinerjaKegiatanId}')->group(function () {
            Route::prefix('kinerja-sub-kegiatan-cross')->group(function () {
                Route::get('/', [KinerjaSubKegiatanCrossController::class, 'index']);
                Route::post('/', [KinerjaSubKegiatanCrossController::class, 'set']);
            });
        });

        Route::prefix('validasi')->middleware(roleMiddleware(Role::VALIDATOR_BAPPEDA))->group(function () {
            Route::get('/', [ValidasiKinerjaKegiatanController::class, 'index']);
            Route::post('/', [ValidasiKinerjaKegiatanController::class, 'validasi']);
        });

        Route::prefix('validasi-pengampu')->middleware(roleMiddleware(Role::VALIDATOR_PENGAMPU))->group(function () {
            Route::get('/', [ValidasiKinerjaKegiatanController::class, 'pengampuIndex']);
            Route::post('/', [ValidasiKinerjaKegiatanController::class, 'pengampuValidasi']);
        });
    });

    Route::resource('kinerja-kegiatan', KinerjaKegiatanController::class)->parameter('kinerja-kegiatan', 'kinerjaKegiatan');

    Route::prefix('kinerja-sub-kegiatan')->group(function () {
        Route::post('/{kinerjaSubKegiatan}/sync', [KinerjaSubKegiatanController::class, 'sync']);
        Route::post('/upload', [KinerjaSubKegiatanController::class, 'upload']);
        Route::get('/export', [KinerjaSubKegiatanController::class, 'export']);
        Route::post('/upload-eviden-bulanan', [KinerjaSubKegiatanController::class, 'uploadEvidenBulanan']);
        Route::post('upload-definisi-operasional', [KinerjaSubKegiatanController::class, 'uploadDefinisiOperasional']);

        Route::prefix('validasi')->middleware(roleMiddleware(Role::VALIDATOR_BAPPEDA))->group(function () {
            Route::get('/', [ValidasiKinerjaSubKegiatanController::class, 'index']);
            Route::post('/', [ValidasiKinerjaSubKegiatanController::class, 'validasi']);
        });

        Route::prefix('validasi-pengampu')->middleware(roleMiddleware(Role::VALIDATOR_PENGAMPU))->group(function () {
            Route::get('/', [ValidasiKinerjaSubKegiatanController::class, 'pengampuIndex']);
            Route::post('/', [ValidasiKinerjaSubKegiatanController::class, 'pengampuValidasi']);
        });
    });

    Route::resource('kinerja-sub-kegiatan', KinerjaSubKegiatanController::class)->parameter('kinerja-sub-kegiatan', 'kinerjaSubKegiatan');
    Route::resource('kinerja-sub-kegiatan-kab-kota', KinerjaSubKegiatanKabKotaController::class)->parameter('kinerja-sub-kegiatan-kab-kota', 'kinerjaSubKegiatanKabKota');

    Route::get('kinerja-langkah-aksi/export', [KinerjaLangkahAksiController::class, 'export']);
    Route::resource('kinerja-langkah-aksi', KinerjaLangkahAksiController::class)->parameter('kinerja-langkah-aksi', 'kinerjaLangkahAksi');

    Route::prefix('kinerja-langkah-aksi-terintegrasi')->group(function () {
        Route::get('/', [KinerjaLangkahAksiTerintegrasiController::class, 'index']);
    });

    Route::prefix('display-makro')->group(function () {
        Route::get('rpjmd', [DisplayMakroController::class, 'rpjmd']);
        Route::get('rkpd', [DisplayMakroController::class, 'rkpd']);
        Route::get('perjanjian-kinerja', [DisplayMakroController::class, 'perjanjianKinerja']);
        Route::get('capaian-kinerja-pemda', [DisplayMakroController::class, 'capaianKinerjaPemda']);
        Route::get('capaian-kinerja-efisiensi-anggaran', [DisplayMakroController::class, 'capaianKinerjaEfisiensiAnggaran']);
        Route::get('program-inovatif', [DisplayMakroController::class, 'programInovatif']);
        Route::get('capaian-kinerja-keuangan', [DisplayMakroController::class, 'capaianKinerjaKeuangan']);
        Route::get('capaian-kinerja-sub-kegiatan', [DisplayMakroController::class, 'capaianKinerjaSubKegiatan']);
        Route::get('rencana-aksi', [DisplayMakroController::class, 'rencanaAksi']);
        Route::get('capaian-kinerja-aktivitas', [DisplayMakroController::class, 'capaianKinerjaAktivitas']);
        Route::get('capaian-kinerja-kegiatan', [DisplayMakroController::class, 'capaianKinerjaKegiatan']);
        Route::get('cascading', [DisplayMakroController::class, 'cascading']);
    });

    Route::prefix('display-mikro')->group(function () {
        Route::get('renstra/export', [DisplayMikroController::class, 'renstraExport']);
        Route::get('renstra', [DisplayMikroController::class, 'renstra']);
        Route::get('rkt/export', [DisplayMikroController::class, 'rktExport']);
        Route::get('rkt', [DisplayMikroController::class, 'rkt']);
        Route::get('perjanjian-kinerja/export', [DisplayMikroController::class, 'perjanjianKinerjaExport']);
        Route::get('perjanjian-kinerja', [DisplayMikroController::class, 'perjanjianKinerja']);
        Route::get('rencana-aksi/export', [DisplayMikroController::class, 'rencanaAksiExport']);
        Route::get('rencana-aksi', [DisplayMikroController::class, 'rencanaAksi']);
        Route::get('capaian-kinerja-pd/export', [DisplayMikroController::class, 'capaianKinerjaPdExport']);
        Route::get('capaian-kinerja-pd', [DisplayMikroController::class, 'capaianKinerjaPD']);
        Route::get('capaian-kinerja-efisiensi-anggaran/export', [DisplayMikroController::class, 'capaianKinerjaEfisiensiAnggaranExport']);
        Route::get('capaian-kinerja-efisiensi-anggaran', [DisplayMikroController::class, 'capaianKinerjaEfisiensiAnggaran']);
        Route::get('capaian-kinerja-keuangan/export', [DisplayMikroController::class, 'capaianKinerjaKeuanganExport']);
        Route::get('capaian-kinerja-keuangan', [DisplayMikroController::class, 'capaianKinerjaKeuangan']);
        Route::get('program-inovatif/export', [DisplayMikroController::class, 'programInovatifExport']);
        Route::get('program-inovatif', [DisplayMikroController::class, 'programInovatif']);
        Route::get('capaian-kinerja-program', [DisplayMikroController::class, 'capaianKinerjaProgram']);
        Route::get('capaian-kinerja-kegiatan/export', [DisplayMikroController::class, 'capaianKinerjaKegiatanExport']);
        Route::get('capaian-kinerja-kegiatan', [DisplayMikroController::class, 'capaianKinerjaKegiatan']);
        Route::get('capaian-kinerja-sub-kegiatan/export', [DisplayMikroController::class, 'capaianKinerjaSubKegiatanExport']);
        Route::get('capaian-kinerja-sub-kegiatan', [DisplayMikroController::class, 'capaianKinerjaSubKegiatan']);
        Route::get('capaian-kinerja-langkah-aksi/export', [DisplayMikroController::class, 'capaianKinerjaLangkahAksiExport']);
        Route::get('capaian-kinerja-langkah-aksi', [DisplayMikroController::class, 'capaianKinerjaLangkahAksi']);
        Route::get('cascading', [DisplayMikroController::class, 'cascading']);
        Route::get('cascading/export', [DisplayMikroController::class, 'cascadingExport']);
    });

    Route::prefix('lkip')->group(function () {
        Route::get('arsitektur-kinerja', [LKIPController::class, 'arsitekturKinerja']);
        Route::get('tabel21', [LKIPController::class, 'tabel21']);
        Route::get('perjanjian-kinerja', [LKIPController::class, 'perjanjianKinerja']);
        Route::get('tabel31', [LKIPController::class, 'tabel31']);
        Route::get('program-inovatif', [LKIPController::class, 'programInovatif']);
        Route::get('tabel32', [LKIPController::class, 'tabel32']);
        Route::get('pengelolaan-data-kinerja', [LKIPController::class, 'pengelolaanDataKinerja']);

        Route::resource('narasi-pemda', LKIPNarasiPemdaController::class);
        Route::resource('narasi-pd', LKIPNarasiPDController::class);
    });

    

    Route::prefix('rapor-kinerja/{triwulan}')->where(['triwulan' => '[1-4]'])->group(function () {
        Route::prefix('data')->group(function () {
            Route::get('/', [RaporKinerjaController::class, 'data']);

            Route::prefix('{kinerjaSubKegiatan}/penyebab-kegagalan')->group(function () {
                Route::get('/', [PenyebabKegagalanController::class, 'index']);
                Route::post('/', [PenyebabKegagalanController::class, 'store']);
                Route::delete('/{penyebabKegagalan}', [PenyebabKegagalanController::class, 'destroy']);
                Route::patch('/{penyebabKegagalan}', [PenyebabKegagalanController::class, 'update']);
            });
        });

        Route::get('langkah-aksi-perbaikan', [RaporKinerjaController::class, 'langkahAksiPerbaikan']);
    });

    Route::prefix('rapor-kinerja/penambahan-jumlah-output')->group(function () {
        Route::get('/', [PerubahanJumlahOutputV2Controller::class, 'index']);
        Route::post('/', [PerubahanJumlahOutputV2Controller::class, 'store']);
    });

    Route::apiResource('kinerja-tidak-tercapai', KinerjaTidakTercapaiController::class)->except('show');

    Route::prefix('solusi-kinerja/{type}')->group(function () {
        Route::get('/', [SolusiKinerjaController::class, 'index']);
        Route::post('{id}', [SolusiKinerjaController::class, 'store']);
        Route::delete('{id}', [SolusiKinerjaController::class, 'destroy']);
    });

    Route::apiResource('kinerja-tercapai', KinerjaTercapaiController::class)->except('show');

    Route::prefix('lke')->group(function () {
        Route::prefix('eviden')->middleware(roleMiddleware(Role::PERANGKAT_DAERAH))->group(function () {
            Route::get('/', [EvidenController::class, 'index']);
            Route::post('/', [EvidenController::class, 'store']);
            Route::post('upload', [EvidenController::class, 'upload']);
            Route::post('penilaian', [EvidenController::class, 'submitPenilaian']);
            Route::get('hasil-self-assessment', [EvidenController::class, 'hasilSelfAssessment'])->withoutMiddleware(roleMiddleware(Role::PERANGKAT_DAERAH))->middleware(roleMiddleware(Role::PERANGKAT_DAERAH, Role::VALIDATOR_LKE));
            Route::get('hasil-akhir', [EvidenController::class, 'hasilAkhir']);
        });
        Route::prefix('eviden-2')->middleware(roleMiddleware(Role::PERANGKAT_DAERAH))->group(function () {
            Route::get('/', [EvidenController::class, 'index2']);
            Route::post('penilaian', [EvidenController::class, 'submitPenilaian2']);
        });
        Route::prefix('penilaian')->group(function () {
            Route::middleware(roleMiddleware(Role::VALIDATOR_LKE, Role::PERANGKAT_DAERAH, Role::SUPER))->group(function () {
                Route::get('/', [PenilaianController::class, 'index']);
                Route::get('/hasil-akhir', [PenilaianController::class, 'hasilAkhir']);
                Route::get('export', [PenilaianController::class, 'export']);
                Route::post('/', [PenilaianController::class, 'store']);
                Route::post('close', [PenilaianController::class, 'close']);
                Route::post('cancel-close', [PenilaianController::class, 'cancelClose']);
                Route::get('status-penilaian', [PenilaianController::class, 'getStatusPenilaian']);
            });

            Route::prefix('hasil')->group(function () {
                Route::get('/', [PenilaianController::class, 'hasil'])->middleware(roleMiddleware(Role::VALIDATOR_LKE, Role::VALIDATOR_LKE_PLENO));
                Route::get('export', [PenilaianController::class, 'hasilExport'])->middleware(roleMiddleware(Role::VALIDATOR_LKE, Role::VALIDATOR_LKE_PLENO));
            });
            Route::get('rekap', [PenilaianController::class, 'rekap'])->middleware(roleMiddleware(Role::SUPER, Role::VALIDATOR_LKE));
        });
        Route::prefix('penilaian-2')->group(function () {
            Route::middleware(roleMiddleware(Role::VALIDATOR_LKE))->group(function () {
                Route::get('/', [PenilaianController::class, 'index2']);
                Route::get('export', [PenilaianController::class, 'export2']);
                Route::post('close', [PenilaianController::class, 'close2']);
                Route::post('cancel-close', [PenilaianController::class, 'cancelClose2']);
            });
        });
        Route::prefix('penilaian-humanis')->group(function () {
            Route::middleware(roleMiddleware(Role::VALIDATOR_LKE))->group(function () {
                Route::get('/', [PenilaianHumanisController::class, 'index']);
                Route::post('/', [PenilaianHumanisController::class, 'store']);
                Route::post('close', [PenilaianHumanisController::class, 'close']);
            });
        });
        Route::prefix('catatan-rekomendasi')->group(function () {
            Route::get('/', [CatatanRekomendasiController::class, 'index'])->middleware(roleMiddleware(Role::PERANGKAT_DAERAH, Role::VALIDATOR_LKE));
            Route::post('/', [CatatanRekomendasiController::class, 'store'])->middleware(roleMiddleware(Role::VALIDATOR_LKE));
        });
    });

    Route::get('kamus-indikator', KamusIndikatorController::class);

    Route::prefix('kamus-indikator-fungsional')->group(function () {
        Route::get('/', [KamusIndikatorFungsionalController::class, 'index']);
        Route::post('/', [KamusIndikatorFungsionalController::class, 'store']);
    });

    Route::prefix('kamus-indikator-validasi-bappeda')->group(function () {
        Route::get('/', [KamusIndikatorValidasiBappedaController::class, 'index']);
        Route::post('/', [KamusIndikatorValidasiBappedaController::class, 'store']);
    });

    Route::prefix('kamus-indikator-fungsional-manual')->group(function () {
        Route::get('/', [KamusIndikatorFungsionalManualController::class, 'index']);
        Route::get('options', [KamusIndikatorFungsionalManualController::class, 'options']);
        Route::post('/', [KamusIndikatorFungsionalManualController::class, 'store']);
        Route::delete('{id}', [KamusIndikatorFungsionalManualController::class, 'destroy']);
    });

    Route::prefix('validasi-skp')->middleware(roleMiddleware(Role::SUPER))->group(function () {
        Route::post('/', [SKPController::class, 'validateSkp']);
        Route::post('reject', [SKPController::class, 'reject']);
        Route::post('{skp}/set', [SKPController::class, 'set']);
        Route::post('{skp}/unset', [SKPController::class, 'unset']);
    });

    Route::prefix('admin')->middleware(RoleMiddleware(Role::SUPER))->group(function () {
        Route::prefix('users')->group(function () {
            Route::get('/', [UserController::class, 'index']);
            Route::post('/', [UserController::class, 'store']);
            Route::get('roles', [UserController::class, 'roles']);
            Route::get('{user}', [UserController::class, 'detail']);
            Route::patch('{user}', [UserController::class, 'update']);
            Route::delete('{user}', [UserController::class, 'destroy']);
            Route::post('{user}/enable', [UserController::class, 'enable']);
            Route::post('{user}/disable', [UserController::class, 'disable']);
        });

        Route::prefix('visi-misi')->group(function () {
            Route::get('/', [AdminVisiMisiController::class, 'index']);
            Route::post('visi', [AdminVisiMisiController::class, 'storeVisi']);
            Route::patch('visi/{visi}', [AdminVisiMisiController::class, 'updateVisi']);
            Route::delete('visi/{visi}', [AdminVisiMisiController::class, 'destroyVisi']);
            Route::post('misi', [AdminVisiMisiController::class, 'storeMisi']);
            Route::patch('misi/{misi}', [AdminVisiMisiController::class, 'updateMisi']);
            Route::delete('misi/{misi}', [AdminVisiMisiController::class, 'destroyMisi']);
            Route::post('tujuan', [AdminVisiMisiController::class, 'storeTujuan']);
            Route::patch('tujuan/{tujuan}', [AdminVisiMisiController::class, 'updateTujuan']);
            Route::delete('tujuan/{tujuan}', [AdminVisiMisiController::class, 'destroyTujuan']);
            Route::post('indikator-tujuan', [AdminVisiMisiController::class, 'storeIndikatorTujuan']);
            Route::patch('indikator-tujuan/{indikatorTujuan}', [AdminVisiMisiController::class, 'updateIndikatorTujuan']);
            Route::delete('indikator-tujuan/{indikatorTujuan}', [AdminVisiMisiController::class, 'destroyIndikatorTujuan']);
        });

        Route::prefix('sasaran-iku-rpjmd')->group(function () {
            Route::get('/', [AdminSasaranIkuRpjmdController::class, 'index']);
            Route::post('sasaran', [AdminSasaranIkuRpjmdController::class, 'storeSasaran']);
            Route::patch('sasaran/{sasaranStrategis}', [AdminSasaranIkuRpjmdController::class, 'updateSasaran']);
            Route::delete('sasaran/{sasaranStrategis}', [AdminSasaranIkuRpjmdController::class, 'destroySasaran']);
            Route::post('indikator', [AdminSasaranIkuRpjmdController::class, 'storeIndikator']);
            Route::patch('indikator/{indikatorSasaranStrategis}', [AdminSasaranIkuRpjmdController::class, 'updateIndikator']);
            Route::delete('indikator/{indikatorSasaranStrategis}', [AdminSasaranIkuRpjmdController::class, 'destroyIndikator']);
        });

        Route::prefix('satuan-kerja')->group(function () {
            Route::get('/', [AdminSatuanKerjaController::class, 'index']);
            Route::post('/', [AdminSatuanKerjaController::class, 'store']);
            Route::get('{satuanKerja}', [AdminSatuanKerjaController::class, 'show']);
            Route::patch('{satuanKerja}', [AdminSatuanKerjaController::class, 'update']);
            Route::delete('{satuanKerja}', [AdminSatuanKerjaController::class, 'destroy']);
        });

        Route::prefix('struktur-organisasi')->group(function () {
            Route::get('/', [AdminStrukturOrganisasiController::class, 'index']);
        });

        Route::prefix('banner')->group(function () {
            Route::get('/', [AdminBannerController::class, 'index']);
            Route::post('/', [AdminBannerController::class, 'store']);
            Route::get('{bannerId}', [AdminBannerController::class, 'show'])->whereNumber('bannerId');
            Route::post('{bannerId}', [AdminBannerController::class, 'update'])->whereNumber('bannerId');
            Route::patch('{bannerId}', [AdminBannerController::class, 'update'])->whereNumber('bannerId');
            Route::delete('{bannerId}', [AdminBannerController::class, 'destroy'])->whereNumber('bannerId');
        });
    });

    Route::prefix('validasi-perencanaan')->group(function () {
        Route::get('/', [ValidasiPerencanaanController::class, 'status']);
        Route::post('/', [ValidasiPerencanaanController::class, 'submit']);

        Route::prefix('validasi')->group(function () {
            Route::get('/', [ValidasiPerencanaanController::class, 'opd']);
            Route::get('{satkerId}', [ValidasiPerencanaanController::class, 'statusValidasiOpd']);
            Route::get('{satkerId}/data', [ValidasiPerencanaanController::class, 'data']);
            Route::post('{satkerId}', [ValidasiPerencanaanController::class, 'validasi']);
        });
    });
    Route::prefix('perjanjian-kinerja')->group(function () {
        Route::get('export-pdf', [PerjanjianKinerjaController::class, 'exportPdf']);
        Route::get('export', [PerjanjianKinerjaController::class, 'export']);
        Route::get('export-all', [PerjanjianKinerjaController::class, 'exportAll']);
        Route::post('link', [PerjanjianKinerjaController::class, 'storeLink']);
    });

    Route::resource('perjanjian-kinerja', PerjanjianKinerjaController::class);
    Route::resource('lke-rekomendasi', RekomendasiController::class)->parameters(['lke-rekomendasi' => 'rekomendasi']);

    Route::prefix('anggaran-capaian-iku')->middleware(roleMiddleware(Role::SUPER))->group(function () {
        Route::get('/', [AnggaranCapaianIkuController::class, 'index']);
        Route::post('/', [AnggaranCapaianIkuController::class, 'store']);
        Route::get('{anggaranCapaianIku}', [AnggaranCapaianIkuController::class, 'show']);
        Route::patch('{anggaranCapaianIku}', [AnggaranCapaianIkuController::class, 'update']);
        Route::delete('{anggaranCapaianIku}', [AnggaranCapaianIkuController::class, 'destroy']);
    });

    Route::prefix('nilai-sakip-pemda')->middleware(roleMiddleware(Role::SUPER))->group(function () {
        Route::get('/', [NilaiSakipPemdaController::class, 'index']);
        Route::post('/', [NilaiSakipPemdaController::class, 'store']);
        Route::get('{nilaiSakip}', [NilaiSakipPemdaController::class, 'show']);
        Route::patch('{nilaiSakip}', [NilaiSakipPemdaController::class, 'update']);
        Route::delete('{nilaiSakip}', [NilaiSakipPemdaController::class, 'destroy']);
    });

    Route::get('pohon-kinerja-korelasi-ai', [PohonKinerjaController::class, 'sendAIKorelasi']);
    Route::get('pohon-kinerja-rekomendasi-ai', [PohonKinerjaController::class, 'sendAIRekomendasi']);
    Route::get('evaluasi-ai', [PenilaianController::class, 'sendAIEvaluasi']);

});

Route::prefix('laporan-lhe')->group(function () {
    Route::get('export', [CetakLaporanControllerLke::class, 'export']);
});

Route::prefix('public-display')->middleware('tahun-kinerja-public')->group(function () {
    Route::prefix('display-makro')->group(function () {
        Route::get('rpjmd', [PublicDisplayDisplayMakroController::class, 'rpjmd']);
        Route::get('rkpd', [PublicDisplayDisplayMakroController::class, 'rkpd']);
        Route::get('perjanjian-kinerja', [PublicDisplayDisplayMakroController::class, 'perjanjianKinerja']);
        Route::get('capaian-kinerja-pemda', [PublicDisplayDisplayMakroController::class, 'capaianKinerjaPemda']);
        Route::get('capaian-kinerja-efisiensi-anggaran', [PublicDisplayDisplayMakroController::class, 'capaianKinerjaEfisiensiAnggaran']);
        Route::get('program-inovatif', [PublicDisplayDisplayMakroController::class, 'programInovatif']);
        Route::get('capaian-kinerja-keuangan', [PublicDisplayDisplayMakroController::class, 'capaianKinerjaKeuangan']);
        Route::get('capaian-kinerja-sub-kegiatan', [PublicDisplayDisplayMakroController::class, 'capaianKinerjaSubKegiatan']);
        Route::get('rencana-aksi', [PublicDisplayDisplayMakroController::class, 'rencanaAksi']);
        Route::get('capaian-kinerja-aktivitas', [PublicDisplayDisplayMakroController::class, 'capaianKinerjaAktivitas']);
        Route::get('capaian-kinerja-kegiatan', [PublicDisplayDisplayMakroController::class, 'capaianKinerjaKegiatan']);
        Route::get('cascading', [PublicDisplayDisplayMakroController::class, 'cascading']);

    });

    Route::prefix('display-mikro')->group(function () {
        Route::get('renstra', [PublicDisplayDisplayMikroController::class, 'renstra']);
        Route::get('rkt', [PublicDisplayDisplayMikroController::class, 'rkt']);
        Route::get('perjanjian-kinerja', [PublicDisplayDisplayMikroController::class, 'perjanjianKinerja']);
        Route::get('rencana-aksi', [PublicDisplayDisplayMikroController::class, 'rencanaAksi']);
        Route::get('rencana-aksi-terintegrasi', [PublicDisplayDisplayMikroController::class, 'rencanaAksiTerintegrasi']);
        Route::get('capaian-kinerja-pd', [PublicDisplayDisplayMikroController::class, 'capaianKinerjaPD']);
        Route::get('capaian-kinerja-efisiensi-anggaran', [PublicDisplayDisplayMikroController::class, 'capaianKinerjaEfisiensiAnggaran']);
        Route::get('capaian-kinerja-keuangan', [PublicDisplayDisplayMikroController::class, 'capaianKinerjaKeuangan']);
        Route::get('program-inovatif', [PublicDisplayDisplayMikroController::class, 'programInovatif']);
        Route::get('capaian-kinerja-program', [PublicDisplayDisplayMikroController::class, 'capaianKinerjaProgram']);
        Route::get('capaian-kinerja-kegiatan', [PublicDisplayDisplayMikroController::class, 'capaianKinerjaKegiatan']);
        Route::get('capaian-kinerja-sub-kegiatan', [PublicDisplayDisplayMikroController::class, 'capaianKinerjaSubKegiatan']);
        Route::get('capaian-kinerja-langkah-aksi', [PublicDisplayDisplayMikroController::class, 'capaianKinerjaLangkahAksi']);
        Route::get('cascading', [PublicDisplayDisplayMikroController::class, 'cascading']);
        Route::get('why', [PublicDisplayDisplayMikroController::class, 'why']);
    });

    Route::prefix('lke')->group(function () {
        Route::prefix('eviden')->group(function () {
            Route::get('hasil-self-assessment', [PublicDisplayEvidenController::class, 'hasilSelfAssessment']);
            Route::get('hasil-akhir', [PublicDisplayEvidenController::class, 'hasilAkhir']);
        });
    });

    Route::get('arsitektur-kinerja', [PublicDisplayController::class, 'arsitekturKinerja']);
    Route::get('rencana-aksi', [RencanaAksiController::class, 'index']);
    Route::get('rencana-aksi-gubernur', [RencanaAksiController::class, 'gubernur']);
    Route::get('definisi-operasional', [PublicDisplayDisplayMakroController::class, 'definisiOperasional']);
    Route::get('why', [PublicDisplayDisplayMakroController::class, 'why']);
    Route::get('progres-kinerja-makro', [PublicDisplayController::class, 'progresKinerjaMakro']);
    Route::get('progres-rata-capaian-iku', [PublicDisplayController::class, 'progresRataCapaianIku']);
    Route::get('progres-rata-kenaikan-realisasi', [PublicDisplayController::class, 'progresRataKenaikanRealisasi']);
    Route::get('progres-nilai-sakip-pemda', [PublicDisplayController::class, 'progresNilaiSakipPemda']);
    Route::get('pohon-kinerja', [PohonKinerjaController::class, 'index']);
    Route::get('pohon-kinerja-detail', [PohonKinerjaController::class, 'detail']);
    Route::get('pohon-kinerja-import', [PohonKinerjaInsertDataController::class, 'importToPohonKinerja']);

    Route::get('berbagi-peran', [BerbagiPeranController::class, 'Index']);
});

Route::prefix('option')->group(function () {
    Route::get('visi', [OptionController::class, 'visi']);
    Route::get('misi', [OptionController::class, 'misi']);
    Route::get('tujuan', [OptionController::class, 'tujuan']);
    Route::get('indikator-tujuan', [OptionController::class, 'indikatorTujuan']);
    Route::get('satuan-kerja', [OptionController::class, 'satuanKerja']);
    Route::get('unit-kerja/{satuanKerjaId}', [OptionController::class, 'unitKerja']);
    Route::get('sasaran-strategis', [OptionController::class, 'sasaranStrategis']);
    Route::get('indikator-sasaran-strategis', [OptionController::class, 'indikatorSasaranStrategis']);

    Route::get('sasaran/{model}', [OptionController::class, 'sasaranKinerja'])->middleware('auth:api');
    Route::get('indikator/{model}', [OptionController::class, 'indikatorKinerja'])->middleware('auth:api');

    Route::get('sub-kegiatan/{satuanKerjaId}', [OptionController::class, 'subKegiatan']);
    Route::get('sasaran-pohon-kinerja', [OptionController::class, 'sasaranPohonKinerja']);

    Route::prefix('pengampu')->group(function () {
        Route::get('tim-kerja', [OptionController::class, 'pengampuTimKerja']);
        Route::get('unit-kerja', [OptionController::class, 'pengampuUnitKerja']);
    });
});

Route::prefix('nilai-jenjang-kinerja')->group(function () {
    Route::get('/', [NilaiJenjangKinerjaController::class, 'index']);
    Route::post('/', [NilaiJenjangKinerjaController::class, 'store']);
    Route::get('rekap', [NilaiJenjangKinerjaController::class, 'rekap']);
});

Route::get('diagram-iku-gubernur/{satker?}', [DiagramIkuController::class, 'ikuGubernur']);

Route::get('solusi-masalah', [SolusiKinerjaController::class, 'solusiMasalah']);

Route::get('rapor-kinerja/{triwulan}/report', [RaporKinerjaController::class, 'report']);
Route::get('rapor-kinerja/{triwulan}/diagram', [RaporKinerjaController::class, 'diagram']);
Route::get('rapor-kinerja/{triwulan}/report/export', [RaporKinerjaController::class, 'reportExport']);

Route::prefix('rapor-kinerja-sasaran-strategis-pd')->group(function () {
    Route::get('kinerja', [RaporKinerjaSasaranStrategisPdController::class, 'kinerja']);
});

Route::get('cascading-json', [PublicDisplayController::class, 'cascadingJson']);
