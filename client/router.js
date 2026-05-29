import Vue from 'vue'
import Router from 'vue-router'
import { scrollBehavior } from '~/utils'

Vue.use(Router)

const page = path => () => import(`~/pages/${path}`).then(m => m.default || m)

const routes = [
  { path: '/', name: 'welcome', component: page('welcome.vue') },

  { path: '/login', name: 'login', component: page('auth/login.vue') },
  { path: '/dashboard', name: 'dashboard', component: page('dashboard.vue') },

  { path: '/visi-misi-rpjmd', name: 'visi-misi-rpjmd.index', component: page('visi-misi-rpjmd/index'), meta: { label: 'Visi, Misi & Tujuan RPJMD' } },
  { path: '/visi-misi-rpjmd/create', name: 'visi-misi-rpjmd.create', component: page('visi-misi-rpjmd/create'), meta: { label: 'Tambah Data', breadCrumbParent: {name: 'visi-misi-rpjmd.index'} } },
  { path: '/visi-misi-rpjmd/:id/edit', name: 'visi-misi-rpjmd.edit', component: page('visi-misi-rpjmd/edit'), meta: { label: 'Edit Data', breadCrumbParent: {name: 'visi-misi-rpjmd.index'} } },
  { path: '/visi-misi-rpjmd/:id', name: 'visi-misi-rpjmd.show', component: page('visi-misi-rpjmd/show'), meta: { label: 'Detail Data', breadCrumbParent: {name: 'visi-misi-rpjmd.index'} } },
  
  { path: '/sasaran-strategis-rpjmd', name: 'sasaran-strategis-rpjmd.index', component: page('sasaran-strategis-rpjmd/index'), meta: { label: 'Sasaran Strategis RPJMD & IKU Bupati' } },
  { path: '/sasaran-strategis-rpjmd/create', name: 'sasaran-strategis-rpjmd.create', component: page('sasaran-strategis-rpjmd/create'), meta: { label: 'Tambah Data', breadCrumbParent: {name: 'sasaran-strategis-rpjmd.index'} } },
  { path: '/sasaran-strategis-rpjmd/:id/edit', name: 'sasaran-strategis-rpjmd.edit', component: page('sasaran-strategis-rpjmd/edit'), meta: { label: 'Edit Data', breadCrumbParent: {name: 'sasaran-strategis-rpjmd.index'} } },
  { path: '/sasaran-strategis-rpjmd/:id', name: 'sasaran-strategis-rpjmd.show', component: page('sasaran-strategis-rpjmd/show'), meta: { label: 'Detail Data', breadCrumbParent: {name: 'sasaran-strategis-rpjmd.index'} } },
  { path: '/sasaran-strategis-rpjmd/:id/sasaran-strategis-pd-cross', name: 'sasaran-strategis-rpjmd.sasaran-strategis-pd-cross', component: page('sasaran-strategis-rpjmd/sasaran-strategis-pd-cross'), meta: { label: 'Sasaran Cross', breadCrumbParent: {name: 'sasaran-strategis-rpjmd.index'} } },
  
  { path: '/kinerja-cross-cutting', name: 'kinerja-cross-cutting.index', component: page('kinerja-bayangan/index'), meta: { label: 'Kinerja Cross-Cutting' } },
  { path: '/kinerja-cross-cutting/create', name: 'kinerja-cross-cutting.create', component: page('kinerja-bayangan/create'), meta: { label: 'Tambah Data', breadCrumbParent: {name: 'kinerja-cross-cutting.index'} } },
  { path: '/kinerja-cross-cutting/:id/edit', name: 'kinerja-cross-cutting.edit', component: page('kinerja-bayangan/edit'), meta: { label: 'Edit Data', breadCrumbParent: {name: 'kinerja-cross-cutting.index'} } },
  { path: '/kinerja-cross-cutting/:id', name: 'kinerja-cross-cutting.show', component: page('kinerja-bayangan/show'), meta: { label: 'Detail Data', breadCrumbParent: {name: 'kinerja-cross-cutting.index'} } },
  
  { path: '/sasaran-strategis-pd', name: 'sasaran-strategis-pd.index', component: page('sasaran-strategis-pd/index'), meta: { label: 'Sasaran Strategis Perangkat Daerah' } },
  { path: '/sasaran-strategis-pd/create', name: 'sasaran-strategis-pd.create', component: page('sasaran-strategis-pd/create'), meta: { label: 'Tambah Data', breadCrumbParent: {name: 'sasaran-strategis-pd.index'} } },
  { path: '/sasaran-strategis-pd/validasi', name: 'sasaran-strategis-pd.validasi', component: page('sasaran-strategis-pd/validasi'), meta: { label: 'Validasi Sasaran Strategis Perangkat Daerah' } },
  { path: '/sasaran-strategis-pd/validasi-pengampu', name: 'sasaran-strategis-pd.validasi-pengampu', component: page('sasaran-strategis-pd/validasi-pengampu'), meta: { label: 'Validasi Pengampu Sasaran Strategis Perangkat Daerah' } },
  { path: '/sasaran-strategis-pd/:id/edit', name: 'sasaran-strategis-pd.edit', component: page('sasaran-strategis-pd/edit'), meta: { label: 'Edit Data', breadCrumbParent: {name: 'sasaran-strategis-pd.index'} } },
  { path: '/sasaran-strategis-pd/:id', name: 'sasaran-strategis-pd.show', component: page('sasaran-strategis-pd/show'), meta: { label: 'Detail Data', breadCrumbParent: {name: 'sasaran-strategis-pd.index'} } },
  { path: '/sasaran-strategis-pd/:id/kinerja-program-cross', name: 'sasaran-strategis-pd.kinerja-program-cross', component: page('sasaran-strategis-pd/kinerja-program-cross'), meta: { label: 'Kinerja Cross', breadCrumbParent: {name: 'sasaran-strategis-pd.index'} } },
  
  { path: '/kinerja-program', name: 'kinerja-program.index', component: page('kinerja-program/index'), meta: { label: 'Kinerja Program' } },
  { path: '/kinerja-program/create', name: 'kinerja-program.create', component: page('kinerja-program/create'), meta: { label: 'Tambah Data', breadCrumbParent: {name: 'kinerja-program.index'} } },
  { path: '/kinerja-program/validasi', name: 'kinerja-program.validasi', component: page('kinerja-program/validasi'), meta: { label: 'Validasi Kinerja Program' } },
  { path: '/kinerja-program/validasi-pengampu', name: 'kinerja-program.validasi-pengampu', component: page('kinerja-program/validasi-pengampu'), meta: { label: 'Validasi Pengampu Kinerja Program' } },
  { path: '/kinerja-program/:id/edit', name: 'kinerja-program.edit', component: page('kinerja-program/edit'), meta: { label: 'Edit Data', breadCrumbParent: {name: 'kinerja-program.index'} } },
  { path: '/kinerja-program/:id', name: 'kinerja-program.show', component: page('kinerja-program/show'), meta: { label: 'Detail Data', breadCrumbParent: {name: 'kinerja-program.index'} } },
  { path: '/kinerja-program/:id/kinerja-kegiatan-cross', name: 'kinerja-program.kinerja-kegiatan-cross', component: page('kinerja-program/kinerja-kegiatan-cross'), meta: { label: 'Kinerja Cross', breadCrumbParent: {name: 'kinerja-program.index'} } },
  
  { path: '/kinerja-kegiatan', name: 'kinerja-kegiatan.index', component: page('kinerja-kegiatan/index'), meta: { label: 'Kinerja Kegiatan' } },
  { path: '/kinerja-kegiatan/create', name: 'kinerja-kegiatan.create', component: page('kinerja-kegiatan/create'), meta: { label: 'Tambah Data', breadCrumbParent: {name: 'kinerja-kegiatan.index'} } },
  { path: '/kinerja-kegiatan/validasi', name: 'kinerja-kegiatan.validasi', component: page('kinerja-kegiatan/validasi'), meta: { label: 'Validasi Kinerja Kegiatan' } },
  { path: '/kinerja-kegiatan/validasi-pengampu', name: 'kinerja-kegiatan.validasi-pengampu', component: page('kinerja-kegiatan/validasi-pengampu'), meta: { label: 'Validasi Pengampu Kinerja Kegiatan' } },
  { path: '/kinerja-kegiatan/:id/edit', name: 'kinerja-kegiatan.edit', component: page('kinerja-kegiatan/edit'), meta: { label: 'Edit Data', breadCrumbParent: {name: 'kinerja-kegiatan.index'} } },
  { path: '/kinerja-kegiatan/:id', name: 'kinerja-kegiatan.show', component: page('kinerja-kegiatan/show'), meta: { label: 'Detail Data', breadCrumbParent: {name: 'kinerja-kegiatan.index'} } },
  { path: '/kinerja-kegiatan/:id/kinerja-sub-kegiatan-cross', name: 'kinerja-kegiatan.kinerja-sub-kegiatan-cross', component: page('kinerja-kegiatan/kinerja-sub-kegiatan-cross'), meta: { label: 'Kinerja Cross', breadCrumbParent: {name: 'kinerja-kegiatan.index'} } },
  
  { path: '/kinerja-sub-kegiatan', name: 'kinerja-sub-kegiatan.index', component: page('kinerja-sub-kegiatan/index'), meta: { label: 'Kinerja Sub Kegiatan' } },
  { path: '/kinerja-sub-kegiatan/create', name: 'kinerja-sub-kegiatan.create', component: page('kinerja-sub-kegiatan/create'), meta: { label: 'Tambah Data', breadCrumbParent: {name: 'kinerja-sub-kegiatan.index'} } },
  { path: '/kinerja-sub-kegiatan/validasi', name: 'kinerja-sub-kegiatan.validasi', component: page('kinerja-sub-kegiatan/validasi'), meta: { label: 'Validasi Kinerja Sub Kegiatan' } },
  { path: '/kinerja-sub-kegiatan/validasi-pengampu', name: 'kinerja-sub-kegiatan.validasi-pengampu', component: page('kinerja-sub-kegiatan/validasi-pengampu'), meta: { label: 'Validasi Pengampu Kinerja Sub Kegiatan' } },
  { path: '/kinerja-sub-kegiatan/:id/edit', name: 'kinerja-sub-kegiatan.edit', component: page('kinerja-sub-kegiatan/edit'), meta: { label: 'Edit Data', breadCrumbParent: {name: 'kinerja-sub-kegiatan.index'} } },
  { path: '/kinerja-sub-kegiatan/:id', name: 'kinerja-sub-kegiatan.show', component: page('kinerja-sub-kegiatan/show'), meta: { label: 'Detail Data', breadCrumbParent: {name: 'kinerja-sub-kegiatan.index'} } },
  
  { path: '/kinerja-kab-kota', name: 'kinerja-kab-kota.index', component: page('kinerja-kab-kota/index'), meta: { label: 'Kinerja Sub Kegiatan' } },
  { path: '/kinerja-kab-kota/create', name: 'kinerja-kab-kota.create', component: page('kinerja-kab-kota/create'), meta: { label: 'Tambah Data', breadCrumbParent: {name: 'kinerja-sub-kegiatan.index'} } },
  { path: '/kinerja-kab-kota/validasi', name: 'kinerja-kab-kota.validasi', component: page('kinerja-kab-kota/validasi'), meta: { label: 'Validasi Kinerja Sub Kegiatan' } },
  { path: '/kinerja-kab-kota/validasi-pengampu', name: 'kinerja-kab-kota.validasi-pengampu', component: page('kinerja-kab-kota/validasi-pengampu'), meta: { label: 'Validasi Pengampu Kinerja Sub Kegiatan' } },
  { path: '/kinerja-kab-kota/:id/edit', name: 'kinerja-kab-kota.edit', component: page('kinerja-kab-kota/edit'), meta: { label: 'Edit Data', breadCrumbParent: {name: 'kinerja-sub-kegiatan.index'} } },
  { path: '/kinerja-kab-kota/:id', name: 'kinerja-kab-kota.show', component: page('kinerja-kab-kota/show'), meta: { label: 'Detail Data', breadCrumbParent: {name: 'kinerja-sub-kegiatan.index'} } },
  

  { path: '/kinerja-langkah-aksi', name: 'kinerja-langkah-aksi.index', component: page('kinerja-langkah-aksi/index'), meta: { label: 'Kinerja Langkah Aksi' } },
  { path: '/kinerja-langkah-aksi/create', name: 'kinerja-langkah-aksi.create', component: page('kinerja-langkah-aksi/create'), meta: { label: 'Tambah Data', breadCrumbParent: {name: 'kinerja-langkah-aksi.index'} } },
  { path: '/kinerja-langkah-aksi/:id/edit', name: 'kinerja-langkah-aksi.edit', component: page('kinerja-langkah-aksi/edit'), meta: { label: 'Edit Data', breadCrumbParent: {name: 'kinerja-langkah-aksi.index'} } },
  { path: '/kinerja-langkah-aksi/:id', name: 'kinerja-langkah-aksi.show', component: page('kinerja-langkah-aksi/show'), meta: { label: 'Detail Data', breadCrumbParent: {name: 'kinerja-langkah-aksi.index'} } },
  
  { path: '/kinerja-langkah-aksi-terintegrasi', name: 'kinerja-langkah-aksi-terintegrasi.index', component: page('kinerja-langkah-aksi-terintegrasi/index'), meta: { label: 'Rencana Aksi Terintegrasi' } },
  { path: '/kinerja-langkah-aksi-terintegrasi/realisasi', name: 'kinerja-langkah-aksi-terintegrasi.realisasi', component: page('kinerja-langkah-aksi-terintegrasi/realisasi'), meta: { label: 'Realisasi Rencana Aksi Terintegrasi' } },

  { path: '/kamus-indikator', name: 'kamus-indikator', component: page('kamus-indikator'), meta: { label: 'Kamus Indikator' } },
  { path: '/kamus-indikator-fungsional', name: 'kamus-indikator-fungsional', component: page('kamus-indikator-fungsional'), meta: { label: 'Kamus Indikator Fungsional' } },
  { path: '/kamus-indikator-validasi-bappeda', name: 'kamus-indikator-validasi-bappeda', component: page('kamus-indikator-validasi-bappeda'), meta: { label: 'Validasi Kamus Indikator Bappeda' } },

  { path: '/display-makro/rpjmd', name: 'display-makro.rpjmd', component: page('display-makro/rpjmd'), meta: { label: 'RPJMD Kabupaten Kuningan' } },
  { path: '/display-makro/rkpd', name: 'display-makro.rkpd', component: page('display-makro/rkpd'), meta: { label: 'RKPD Kabupaten Kuningan' } },
  { path: '/display-makro/perjanjian-kinerja', name: 'display-makro.perjanjian-kinerja', component: page('display-makro/perjanjian-kinerja'), meta: { label: 'Perjanjian Kinerja Bupati Kabupaten Kuningan' } },
  { path: '/display-makro/rencana-aksi', name: 'display-makro.rencana-aksi', component: page('display-makro/rencana-aksi'), meta: { label: 'Rencana Aksi Bupati Kabupaten Kuningan' } },
  { path: '/display-makro/capaian-kinerja-pemda', name: 'display-makro.capaian-kinerja-pemda', component: page('display-makro/capaian-kinerja-pemda'), meta: { label: 'Capaian Kinerja Pemda' } },
  { path: '/display-makro/capaian-kinerja-efisiensi-anggaran', name: 'display-makro.capaian-kinerja-efisiensi-anggaran', component: page('display-makro/capaian-kinerja-efisiensi-anggaran'), meta: { label: 'Capaian Kinerja dan Efisiensi Anggaran' } },
  { path: '/display-makro/capaian-kinerja-keuangan', name: 'display-makro.capaian-kinerja-keuangan', component: page('display-makro/capaian-kinerja-keuangan'), meta: { label: 'Capaian Kinerja dan Keuangan' } },
  { path: '/display-makro/program-inovatif', name: 'display-makro.program-inovatif', component: page('display-makro/program-inovatif'), meta: { label: 'Program Inovatif' } },
  { path: '/display-makro/capaian-kinerja-kegiatan', name: 'display-makro.capaian-kinerja-kegiatan', component: page('display-makro/capaian-kinerja-kegiatan'), meta: { label: 'Capaian Kinerja Kegiatan' } },
  { path: '/display-makro/capaian-kinerja-sub-kegiatan', name: 'display-makro.capaian-kinerja-sub-kegiatan', component: page('display-makro/capaian-kinerja-sub-kegiatan'), meta: { label: 'Capaian Kinerja Sub Kegiatan' } },
  { path: '/display-makro/capaian-kinerja-aktivitas', name: 'display-makro.capaian-kinerja-aktivitas', component: page('display-makro/capaian-kinerja-aktivitas'), meta: { label: 'Capaian Kinerja Aktivitas' } },
  { path: '/display-makro/cascading', name: 'display-makro.cascading', component: page('display-makro/cascading'), meta: { label: 'Cascading' } },
  
  { path: '/display-mikro/renstra', name: 'display-mikro.renstra', component: page('display-mikro/renstra'), meta: { label: 'Renstra' } },
  { path: '/display-mikro/rkt', name: 'display-mikro.rkt', component: page('display-mikro/rkt'), meta: { label: 'RKT' } },
  { path: '/display-mikro/perjanjian-kinerja', name: 'display-mikro.perjanjian-kinerja', component: page('display-mikro/perjanjian-kinerja'), meta: { label: 'Perjanjian Kinerja' } },
  { path: '/display-mikro/rencana-aksi', name: 'display-mikro.rencana-aksi', component: page('display-mikro/rencana-aksi'), meta: { label: 'Rencana Aksi Perangkat Daerah' } },
  { path: '/display-mikro/capaian-kinerja-pd', name: 'display-mikro.capaian-kinerja-pd', component: page('display-mikro/capaian-kinerja-pd'), meta: { label: 'Capaian Kinerja Perangkat Daerah' } },
  { path: '/display-mikro/capaian-kinerja-efisiensi-anggaran', name: 'display-mikro.capaian-kinerja-efisiensi-anggaran', component: page('display-mikro/capaian-kinerja-efisiensi-anggaran'), meta: { label: 'Capaian Kinerja dan Efisiensi Anggaran' } },
  { path: '/display-mikro/capaian-kinerja-keuangan', name: 'display-mikro.capaian-kinerja-keuangan', component: page('display-mikro/capaian-kinerja-keuangan'), meta: { label: 'Capaian Kinerja dan Keuangan Perangkat Daerah' } },
  { path: '/display-mikro/program-inovatif', name: 'display-mikro.program-inovatif', component: page('display-mikro/program-inovatif'), meta: { label: 'Program Inovatif' } },
  { path: '/display-mikro/capaian-kinerja-program', name: 'display-mikro.capaian-kinerja-program', component: page('display-mikro/capaian-kinerja-program'), meta: { label: 'Capaian Kinerja Program' } },
  { path: '/display-mikro/capaian-kinerja-kegiatan', name: 'display-mikro.capaian-kinerja-kegiatan', component: page('display-mikro/capaian-kinerja-kegiatan'), meta: { label: 'Capaian Kinerja Kegiatan' } },
  { path: '/display-mikro/capaian-kinerja-sub-kegiatan', name: 'display-mikro.capaian-kinerja-sub-kegiatan', component: page('display-mikro/capaian-kinerja-sub-kegiatan'), meta: { label: 'Capaian Kinerja Sub Kegiatan' } },
  { path: '/display-mikro/capaian-kinerja-langkah-aksi', name: 'display-mikro.capaian-kinerja-langkah-aksi', component: page('display-mikro/capaian-kinerja-langkah-aksi'), meta: { label: 'Capaian Kinerja Langkah Aksi' } },
  { path: '/display-mikro/cascading', name: 'display-mikro.cascading', component: page('display-mikro/cascading'), meta: { label: 'Cascading' } },

  { path: '/public-display/display-makro/dashboard', name: 'public-display.display-makro.dashboard', component: page('public-display/display-makro/dashboard'), meta: { label: 'Dashboard' } },
  { path: '/public-display/display-makro/rpjmd', name: 'public-display.display-makro.rpjmd', component: page('public-display/display-makro/rpjmd'), meta: { label: 'RPJMD Kabupaten Kuningan' } },
  { path: '/public-display/display-makro/rkpd', name: 'public-display.display-makro.rkpd', component: page('public-display/display-makro/rkpd'), meta: { label: 'RKPD Kabupaten Kuningan' } },
  { path: '/public-display/display-makro/perjanjian-kinerja', name: 'public-display.display-makro.perjanjian-kinerja', component: page('public-display/display-makro/perjanjian-kinerja'), meta: { label: 'Perjanjian Kinerja Bupati Kabupaten Kuningan' } },
  { path: '/public-display/display-makro/rencana-aksi', name: 'public-display.display-makro.rencana-aksi', component: page('public-display/display-makro/rencana-aksi'), meta: { label: 'Rencana Aksi Bupati Kabupaten Kuningan' } },
  { path: '/public-display/display-makro/capaian-kinerja-pemda', name: 'public-display.display-makro.capaian-kinerja-pemda', component: page('public-display/display-makro/capaian-kinerja-pemda'), meta: { label: 'Capaian Kinerja Pemda' } },
  { path: '/public-display/display-makro/capaian-kinerja-efisiensi-anggaran', name: 'public-display.display-makro.capaian-kinerja-efisiensi-anggaran', component: page('public-display/display-makro/capaian-kinerja-efisiensi-anggaran'), meta: { label: 'Capaian Kinerja dan Efisiensi Anggaran' } },
  { path: '/public-display/display-makro/capaian-kinerja-keuangan', name: 'public-display.display-makro.capaian-kinerja-keuangan', component: page('public-display/display-makro/capaian-kinerja-keuangan'), meta: { label: 'Capaian Kinerja dan Keuangan' } },
  { path: '/public-display/display-makro/program-inovatif', name: 'public-display.display-makro.program-inovatif', component: page('public-display/display-makro/program-inovatif'), meta: { label: 'Program Inovatif' } },
  { path: '/public-display/display-makro/capaian-kinerja-kegiatan', name: 'public-display.display-makro.capaian-kinerja-kegiatan', component: page('public-display/display-makro/capaian-kinerja-kegiatan'), meta: { label: 'Capaian Kinerja Kegiatan' } },
  { path: '/public-display/display-makro/capaian-kinerja-sub-kegiatan', name: 'public-display.display-makro.capaian-kinerja-sub-kegiatan', component: page('public-display/display-makro/capaian-kinerja-sub-kegiatan'), meta: { label: 'Capaian Kinerja Sub Kegiatan' } },
  { path: '/public-display/display-makro/capaian-kinerja-aktivitas', name: 'public-display.display-makro.capaian-kinerja-aktivitas', component: page('public-display/display-makro/capaian-kinerja-aktivitas'), meta: { label: 'Capaian Kinerja Aktivitas' } },
  { path: '/public-display/display-makro/cascading', name: 'public-display.display-makro.cascading', component: page('public-display/display-makro/cascading'), meta: { label: 'Cascading' } },
  
  { path: '/public-display/display-mikro/renstra', name: 'public-display.display-mikro.renstra', component: page('public-display/display-mikro/renstra'), meta: { label: 'Renstra' } },
  { path: '/public-display/display-mikro/rkt', name: 'public-display.display-mikro.rkt', component: page('public-display/display-mikro/rkt'), meta: { label: 'RKT' } },
  { path: '/public-display/display-mikro/perjanjian-kinerja', name: 'public-display.display-mikro.perjanjian-kinerja', component: page('public-display/display-mikro/perjanjian-kinerja'), meta: { label: 'Perjanjian Kinerja' } },
  { path: '/public-display/display-mikro/rencana-aksi', name: 'public-display.display-mikro.rencana-aksi', component: page('public-display/display-mikro/rencana-aksi'), meta: { label: 'Rencana Aksi Perangkat Daerah' } },
  { path: '/public-display/display-mikro/capaian-kinerja-pd', name: 'public-display.display-mikro.capaian-kinerja-pd', component: page('public-display/display-mikro/capaian-kinerja-pd'), meta: { label: 'Capaian Kinerja Perangkat Daerah' } },
  { path: '/public-display/display-mikro/capaian-kinerja-efisiensi-anggaran', name: 'public-display.display-mikro.capaian-kinerja-efisiensi-anggaran', component: page('public-display/display-mikro/capaian-kinerja-efisiensi-anggaran'), meta: { label: 'Capaian Kinerja dan Efisiensi Anggaran' } },
  { path: '/public-display/display-mikro/capaian-kinerja-keuangan', name: 'public-display.display-mikro.capaian-kinerja-keuangan', component: page('public-display/display-mikro/capaian-kinerja-keuangan'), meta: { label: 'Capaian Kinerja dan Keuangan Perangkat Daerah' } },
  { path: '/public-display/display-mikro/program-inovatif', name: 'public-display.display-mikro.program-inovatif', component: page('public-display/display-mikro/program-inovatif'), meta: { label: 'Program Inovatif' } },
  { path: '/public-display/display-mikro/capaian-kinerja-program', name: 'public-display.display-mikro.capaian-kinerja-program', component: page('public-display/display-mikro/capaian-kinerja-program'), meta: { label: 'Capaian Kinerja Program' } },
  { path: '/public-display/display-mikro/capaian-kinerja-kegiatan', name: 'public-display.display-mikro.capaian-kinerja-kegiatan', component: page('public-display/display-mikro/capaian-kinerja-kegiatan'), meta: { label: 'Capaian Kinerja Kegiatan' } },
  { path: '/public-display/display-mikro/capaian-kinerja-sub-kegiatan', name: 'public-display.display-mikro.capaian-kinerja-sub-kegiatan', component: page('public-display/display-mikro/capaian-kinerja-sub-kegiatan'), meta: { label: 'Capaian Kinerja Sub Kegiatan' } },
  { path: '/public-display/display-mikro/capaian-kinerja-langkah-aksi', name: 'public-display.display-mikro.capaian-kinerja-langkah-aksi', component: page('public-display/display-mikro/capaian-kinerja-langkah-aksi'), meta: { label: 'Capaian Kinerja Langkah Aksi' } },
  { path: '/public-display/display-mikro/rencana-aksi-terintegrasi', name: 'public-display.display-mikro.rencana-aksi-terintegrasi', component: page('public-display/display-mikro/rencana-aksi-terintegrasi'), meta: { label: 'Rencana Aksi Perangkat Daerah' } },
  { path: '/public-display/display-mikro/realisasi-rencana-aksi-terintegrasi', name: 'public-display.display-mikro.realisasi-rencana-aksi-terintegrasi', component: page('public-display/display-mikro/realisasi-rencana-aksi-terintegrasi'), meta: { label: 'Realisasi Rencana Aksi Perangkat Daerah' } },
  { path: '/public-display/display-mikro/cascading', name: 'public-display.display-mikro.cascading', component: page('public-display/display-mikro/cascading'), meta: { label: 'Cascading' } },
  
  { path: '/public-display/lke/hasil-self-assessment', name: 'public-display.lke.hasil-self-assessment', component: page('public-display/lke/hasil-self-assessment'), meta: { label: 'Hasil Self-Assessment LKE' } },
  { path: '/public-display/lke/hasil-akhir', name: 'public-display.lke.hasil-akhir', component: page('public-display/lke/hasil-akhir'), meta: { label: 'Hasil Akhir LKE' } },

  { path: '/public-display/arsitektur-kinerja', name: 'public-display.arsitektur-kinerja', component: page('public-display/arsitektur-kinerja'), meta: { label: 'Arsitektur Kinerja' } },
  { path: '/public-display/arsitektur-kinerja-cross-cutting', name: 'public-display.arsitektur-kinerja-cross-cutting', component: page('public-display/arsitektur-kinerja-bayangan'), meta: { label: 'Arsitektur Kinerja' } },
  { path: '/public-display/capaian-iku', name: 'public-display.capaian-iku', component: page('public-display/capaian-iku'), meta: { label: 'Diagram Capaian IKU' } },
  { path: '/public-display/rapor-kinerja/:triwulan/:satuanKerjaId', name: 'public-display.rapor-kinerja', component: page('public-display/rapor-kinerja'), meta: { label: 'Diagram Rapor Kinerja' } },

  { path: '/public-display/progres-kinerja-makro', name: 'public-display.progres-kinerja-makro', component: page('public-display/progres-kinerja-makro'), meta: { label: 'Progres Kinerja Makro' } },
  { path: '/public-display/progres-rata-capaian-iku', name: 'public-display.progres-rata-capaian-iku', component: page('public-display/progres-rata-capaian-iku'), meta: { label: 'Progres Rata-Rata Capaian IKU' } },
  { path: '/public-display/progres-nilai-sakip-pemda', name: 'public-display.progres-nilai-sakip-pemda', component: page('public-display/progres-nilai-sakip-pemda'), meta: { label: 'Progres Nilai Sakip Pemda' } },
  { path: '/public-display/progres-rata-kenaikan-realisasi', name: 'public-display.progres-rata-kenaikan-realisasi', component: page('public-display/progres-rata-kenaikan-realisasi'), meta: { label: 'Progres Rata-Rata Kenaikan Realisasi' } },
  { path: '/public-display/pohon-kinerja', name: 'public-display.pohon-kinerja', component: page('public-display/pohon-kinerja'), meta: { label: 'Pohon Kinerja' } },

  { path: '/capaian-iku', name: 'capaian-iku', component: page('capaian-iku'), meta: { label: 'Capaian Iku' } },

  { path: '/lkip/bab-1', name: 'lkip.bab-1', component: page('lkip/bab-1'), meta: { label: 'LKIP BAB I' } },
  { path: '/lkip/bab-2', name: 'lkip.bab-2', component: page('lkip/bab-2'), meta: { label: 'LKIP BAB II' } },
  { path: '/lkip/bab-3', name: 'lkip.bab-3', component: page('lkip/bab-3'), meta: { label: 'LKIP BAB III' } },

  { path: '/lkip/narasi-pemda', name: 'lkip.narasi-pemda.index', component: page('lkip/narasi-pemda/index'), meta: { label: 'Narasi LKIP Pemda' } },
  { path: '/lkip/narasi-pemda/create', name: 'lkip.narasi-pemda.create', component: page('lkip/narasi-pemda/create'), meta: { label: 'Buat Narasi LKIP Pemda', breadCrumbParent: {name: 'lkip.narasi-pemda.index'} } },
  { path: '/lkip/narasi-pemda/:id/edit', name: 'lkip.narasi-pemda.edit', component: page('lkip/narasi-pemda/edit'), meta: { label: 'Edit Narasi LKIP Pemda', breadCrumbParent: {name: 'lkip.narasi-pemda.index'} } },
  { path: '/lkip/narasi-pemda/:id', name: 'lkip.narasi-pemda.show', component: page('lkip/narasi-pemda/show'), meta: { label: 'Detail Narasi LKIP Pemda', breadCrumbParent: {name: 'lkip.narasi-pemda.index'} } },
  
  { path: '/lkip/narasi-pd', name: 'lkip.narasi-pd.index', component: page('lkip/narasi-pd/index'), meta: { label: 'Narasi LKIP Perangkat Daerah' } },
  { path: '/lkip/narasi-pd/create', name: 'lkip.narasi-pd.create', component: page('lkip/narasi-pd/create'), meta: { label: 'Buat Narasi LKIP Perangkat Daerah', breadCrumbParent: {name: 'lkip.narasi-pd.index'} } },
  { path: '/lkip/narasi-pd/:id/edit', name: 'lkip.narasi-pd.edit', component: page('lkip/narasi-pd/edit'), meta: { label: 'Edit Narasi LKIP Perangkat Daerah', breadCrumbParent: {name: 'lkip.narasi-pd.index'} } },
  { path: '/lkip/narasi-pd/:id', name: 'lkip.narasi-pd.show', component: page('lkip/narasi-pd/show'), meta: { label: 'Detail Narasi LKIP Perangkat Daerah', breadCrumbParent: {name: 'lkip.narasi-pd.index'} } },
  
  { path: '/rapor-kinerja/:triwulan/diagram', name: 'rapor-kinerja.diagram', component: page('rapor-kinerja/diagram'), meta: { label: 'Diagram Rapor Kinerja' } },
  { path: '/rapor-kinerja/:triwulan/diagram-external', name: 'rapor-kinerja.diagram-external', component: page('rapor-kinerja/diagram-external'), meta: { label: 'Diagram Rapor Kinerja Eksternal' } },
  { path: '/rapor-kinerja/:triwulan/data', name: 'rapor-kinerja.data', component: page('rapor-kinerja/data'), meta: { label: 'Data Rapor Kinerja' } },
  { path: '/rapor-kinerja/:triwulan/data-external', name: 'rapor-kinerja.data-external', component: page('rapor-kinerja/data-external'), meta: { label: 'Data Rapor Kinerja Eksternal' } },
  { path: '/rapor-kinerja/:triwulan/data/:kinerjaId/penyebab-kegagalan', name: 'rapor-kinerja.data.penyebab-kegagalan', component: page('rapor-kinerja/penyebab-kegagalan'), meta: { label: 'Penyebab Kegagalan Kinerja', breadCrumbParent: {name: 'rapor-kinerja.data'} } },
  { path: '/rapor-kinerja/:triwulan/rank', name: 'rapor-kinerja.rank', component: page('rapor-kinerja/rank'), meta: { label: 'Data Ranking Rapor Kinerja' } },
  { path: '/rapor-kinerja/penambahan-jumlah-output', name: 'rapor-kinerja.penambahan-jumlah-output', component: page('rapor-kinerja/penambahan-jumlah-output'), meta: { label: 'Penambahan Jumlah Output' } },
  { path: '/rapor-kinerja/:triwulan/langkah-aksi-perbaikan', name: 'rapor-kinerja.langkah-aksi-perbaikan', component: page('rapor-kinerja/langkah-aksi-perbaikan'), meta: { label: 'Langkah Aksi Perbaikan' } },
  
  { path: '/rapor-kinerja-sasaran-strategis-pd/:triwulan/diagram', name: 'rapor-kinerja-sasaran-strategis-pd.diagram', component: page('rapor-kinerja-sasaran-strategis-pd/diagram'), meta: { label: 'Diagram Rapor Kinerja Sasaran Strategis Renstra PD' } },
  
  { path: '/lke/eviden', name: 'lke.eviden', component: page('lke/eviden'), meta: { label: 'Input Eviden LKE' } },
  { path: '/lke/eviden-2', name: 'lke.eviden-2', component: page('lke/eviden-2'), meta: { label: 'Input Eviden Tahap Akhir LKE' } },
  { path: '/lke/hasil-self-assessment', name: 'lke.hasil-self-assessment', component: page('lke/hasil-self-assessment'), meta: { label: 'Hasil Self-Assessment LKE' } },
  { path: '/lke/hasil-akhir', name: 'lke.hasil-akhir', component: page('lke/hasil-akhir'), meta: { label: 'Hasil Akhir LKE' } },
  { path: '/lke/hasil', name: 'lke.hasil', component: page('lke/hasil'), meta: { label: 'Hasil LKE' } },

  { path: '/lke/penilaian', name: 'lke.penilaian', component: page('lke/penilaian'), meta: { label: 'Penilaian LKE' } },
  { path: '/lke/penilaian-2', name: 'lke.penilaian-2', component: page('lke/penilaian-2'), meta: { label: 'Penilaian Tahap Akhir LKE' } },
  { path: '/lke/penilaian-humanis', name: 'lke.penilaian-humanis', component: page('lke/penilaian-humanis'), meta: { label: 'Penilaian Pleno' } },
  { path: '/lke/hasil-penilaian', name: 'lke.hasil-penilaian', component: page('lke/hasil-penilaian'), meta: { label: 'Hasil Penilaian LKE' } },
  { path: '/lke/lhe', name: 'lke.lhe', component: page('lke/lhe'), meta: { label: 'Laporan Hasil Evaluasi' } },
  { path: '/lke/status-penilaian', name: 'lke.status-penilaian', component: page('lke/status-penilaian'), meta: { label: 'Status Penilaian' } },
  
  { path: '/lke/rekap-pd', name: 'lke.rekap-pd', component: page('lke/rekap-pd'), meta: { label: 'Rekapitulasi Nilai' } },
  
  { path: '/nilai-jenjang-kinerja', name: 'nilai-jenjang-kinerja', component: page('nilai-jenjang-kinerja/index'), meta: { label: 'Nilai Jenjang Kinerja' } },
  { path: '/nilai-jenjang-kinerja/rekap', name: 'nilai-jenjang-kinerja.rekap', component: page('nilai-jenjang-kinerja/rekap'), meta: { label: 'Rekap Nilai Jenjang Kinerja' } },

  { path: '/admin/users', name: 'admin.users', component: page('admin/users/index'), meta: { label: 'Daftar Pengguna' } },
  
  { path: '/validasi-perencanaan', name: 'validasi-perencanaan', component: page('validasi-perencanaan/opd'), meta: { label: 'Validasi Perencanaan' } },
  { path: '/validasi-perencanaan/:satkerId', name: 'validasi-perencanaan.data', component: page('validasi-perencanaan/data'), meta: { label: 'Data Validasi Perencanaan', breadCrumbParent: {name: 'validasi-perencanaan'} } },

  { path: '/perjanjian-kinerja', name: 'perjanjian-kinerja.index', component: page('perjanjian-kinerja/index'), meta: { label: 'Perjanjian Kinerja' } },
  { path: '/perjanjian-kinerja/create', name: 'perjanjian-kinerja.create', component: page('perjanjian-kinerja/create'), meta: { label: 'Tambah Data', breadCrumbParent: {name: 'perjanjian-kinerja.index'} } },
  { path: '/perjanjian-kinerja/:id/edit', name: 'perjanjian-kinerja.edit', component: page('perjanjian-kinerja/edit'), meta: { label: 'Edit Data', breadCrumbParent: {name: 'perjanjian-kinerja.index'} } },
  { path: '/perjanjian-kinerja/:id', name: 'perjanjian-kinerja.show', component: page('perjanjian-kinerja/show'), meta: { label: 'Detail Data', breadCrumbParent: {name: 'perjanjian-kinerja.index'} } },

  { path: '/lke/rekomendasi', name: 'lke.rekomendasi.index', component: page('lke-rekomendasi/index'), meta: { label: 'Rekomendasi' } },
  { path: '/lke/rekomendasi/create', name: 'lke.rekomendasi.create', component: page('lke-rekomendasi/create'), meta: { label: 'Tambah Data', breadCrumbParent: {name: 'lke-rekomendasi.index'} } },
  { path: '/lke/rekomendasi/:id/edit', name: 'lke.rekomendasi.edit', component: page('lke-rekomendasi/edit'), meta: { label: 'Edit Data', breadCrumbParent: {name: 'lke-rekomendasi.index'} } },
  { path: '/lke/rekomendasi/:id', name: 'lke.rekomendasi.show', component: page('lke-rekomendasi/show'), meta: { label: 'Detail Data', breadCrumbParent: {name: 'lke-rekomendasi.index'} } },

  { path: '/anggaran-capaian-iku', name: 'anggaran-capaian-iku.index', component: page('anggaran-capaian-iku/index'), meta: { label: 'Anggaran Capaian IKU' } },
  { path: '/anggaran-capaian-iku/create', name: 'anggaran-capaian-iku.create', component: page('anggaran-capaian-iku/create'), meta: { label: 'Tambah Data', breadCrumbParent: {name: 'anggaran-capaian-iku.index'} } },
  { path: '/anggaran-capaian-iku/:id/edit', name: 'anggaran-capaian-iku.edit', component: page('anggaran-capaian-iku/edit'), meta: { label: 'Edit Data', breadCrumbParent: {name: 'anggaran-capaian-iku.index'} } },
  
  { path: '/nilai-sakip-pemda', name: 'nilai-sakip-pemda.index', component: page('nilai-sakip-pemda/index'), meta: { label: 'Nilai Sakip Pemda' } },
  { path: '/nilai-sakip-pemda/create', name: 'nilai-sakip-pemda.create', component: page('nilai-sakip-pemda/create'), meta: { label: 'Tambah Data', breadCrumbParent: {name: 'nilai-sakip-pemda.index'} } },
  { path: '/nilai-sakip-pemda/:id/edit', name: 'nilai-sakip-pemda.edit', component: page('nilai-sakip-pemda/edit'), meta: { label: 'Edit Data', breadCrumbParent: {name: 'nilai-sakip-pemda.index'} } },
]

export function createRouter () {
  return new Router({
    routes,
    scrollBehavior,
    mode: 'history',
    base: process.env.baseRoute || "/",
  })
}
