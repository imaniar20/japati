<template>
  <div class="sidebar">
    <div class="sidebar-inner">
      <!-- ### $Sidebar Header ### -->
      <div class="sidebar-logo">
        <div class="peers ai-c fxw-nw">
          <div class="peer peer-greed">
            <nuxt-link to="/" class="td-n">
              <div class="peers ai-c fxw-nw">
                <div class="peer">
                  <div class="logo text-center align-items-center justify-content-center" style="display: flex">
                    <img src="~/assets/images/japati.png" alt="" style="height: 38px" />
                  </div>
                </div>
              </div>
            </nuxt-link>
          </div>
          <div class="peer">
            <div class="mobile-toggle sidebar-toggle">
              <a href="" class="td-n c-white">
                <i class="ti-arrow-circle-left"></i>
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- ### $Sidebar Menu ### -->
      <ul class="sidebar-menu scrollable pos-r">
        <template v-if="$role.isValidatorBappeda()">
          <li class="nav-item">
            <nuxt-link class="sidebar-link" to="/kamus-indikator-validasi-bappeda">
              <span class="icon-holder">
                <i class="text-info ti-bookmark-alt"></i>
              </span>
              <span class="title">Validasi Kamus Indikator Bappeda</span>
            </nuxt-link>
          </li>
          <li class="nav-item">
            <nuxt-link class="sidebar-link" to="/sasaran-strategis-pd/validasi">
              <span class="icon-holder">
                <i class="text-info ti-bookmark-alt"></i>
              </span>
              <span class="title">Validasi Sasaran Strategis Perangkat Daerah</span>
            </nuxt-link>
          </li>
          <li class="nav-item">
            <nuxt-link class="sidebar-link" to="/kinerja-program/validasi">
              <span class="icon-holder">
                <i class="text-info ti-bookmark-alt"></i>
              </span>
              <span class="title">Validasi Kinerja Program</span>
            </nuxt-link>
          </li>
          <li class="nav-item">
            <nuxt-link class="sidebar-link" to="/kinerja-kegiatan/validasi">
              <span class="icon-holder">
                <i class="text-info ti-bookmark-alt"></i>
              </span>
              <span class="title">Validasi Kinerja Kegiatan</span>
            </nuxt-link>
          </li>
          <li class="nav-item">
            <nuxt-link class="sidebar-link" to="/kinerja-sub-kegiatan/validasi">
              <span class="icon-holder">
                <i class="text-info ti-bookmark-alt"></i>
              </span>
              <span class="title">Validasi Kinerja Sub Kegiatan</span>
            </nuxt-link>
          </li>
        </template>

        <template v-else-if="$role.isValidatorPengampu()">
          <li class="nav-item">
            <nuxt-link class="sidebar-link" to="/sasaran-strategis-pd/validasi-pengampu">
              <span class="icon-holder">
                <i class="text-info ti-bookmark-alt"></i>
              </span>
              <span class="title">Validasi Pengampu Sasaran Strategis Perangkat Daerah</span>
            </nuxt-link>
          </li>
          <li class="nav-item">
            <nuxt-link class="sidebar-link" to="/kinerja-program/validasi-pengampu">
              <span class="icon-holder">
                <i class="text-info ti-bookmark-alt"></i>
              </span>
              <span class="title">Validasi Pengampu Kinerja Program</span>
            </nuxt-link>
          </li>
          <li class="nav-item">
            <nuxt-link class="sidebar-link" to="/kinerja-kegiatan/validasi-pengampu">
              <span class="icon-holder">
                <i class="text-info ti-bookmark-alt"></i>
              </span>
              <span class="title">Validasi Pengampu Kinerja Kegiatan</span>
            </nuxt-link>
          </li>
          <li class="nav-item">
            <nuxt-link class="sidebar-link" to="/kinerja-sub-kegiatan/validasi-pengampu">
              <span class="icon-holder">
                <i class="text-info ti-bookmark-alt"></i>
              </span>
              <span class="title">Validasi Pengampu Kinerja Sub Kegiatan</span>
            </nuxt-link>
          </li>
        </template>

        <template v-else-if="$role.isValidatorLKE() || $role.isValidatorPleno()">
          <li class="nav-item mT-10">
            <nuxt-link class="sidebar-link" to="/lke/penilaian">
              <span class="icon-holder">
                <i class="ti-pencil-alt" style="color:black"></i>
              </span>
              <span style="font-size: small;" class="font-weight-bold title">Penilaian Awal</span>
            </nuxt-link>
          </li>
          <li class="nav-item">
            <nuxt-link class="sidebar-link" to="/lke/penilaian-2">
              <span class="icon-holder">
                <i class="ti-check-box" style="color:black"></i>
              </span>
              <span style="font-size: small;" class="font-weight-bold title">Penilaian Akhir</span>
            </nuxt-link>
          </li>
          <li class="nav-item">
            <nuxt-link class="sidebar-link" to="/lke/penilaian-humanis">
              <span class="icon-holder">
                <i class="ti-comments" style="color:black"></i>
              </span>
              <span style="font-size: small;" class="font-weight-bold title">Penilaian Pleno</span>
            </nuxt-link>
          </li>
          <!-- <li class="nav-item">
            <nuxt-link class="sidebar-link" to="/lke/hasil-self-assessment">
              <span class="icon-holder">
                <i class="text-info ti-bookmark-alt"></i>
              </span>
              <span class="title">Hasil Self Assesment</span>
            </nuxt-link>
          </li> -->
          <li class="nav-item">
            <nuxt-link class="sidebar-link" to="/lke/hasil">
              <span class="icon-holder">
                <i class="ti-bar-chart" style="color:black"></i>
              </span>
              <span style="font-size: small;" class="font-weight-bold title">Hasil Penilaian</span>
            </nuxt-link>
          </li>
          <li class="nav-item">
            <nuxt-link class="sidebar-link" to="/lke/status-penilaian">
              <span class="icon-holder">
                <i class="ti-info-alt" style="color:black"></i>
              </span>
              <span style="font-size: small;" class="font-weight-bold title">Status Penilaian</span>
            </nuxt-link>
          </li>
          <li class="nav-item">
            <nuxt-link class="sidebar-link" to="/lke/lhe">
              <span class="icon-holder">
                <i class="ti-folder" style="color:black"></i>
              </span>
              <span style="font-size: small;" class="font-weight-bold title">LHE</span>
            </nuxt-link>
          </li>
          <!-- <li class="nav-item">
            <nuxt-link class="sidebar-link" to="/lke/hasil-penilaian">
              <span class="icon-holder">
                <i class="text-info ti-bookmark-alt"></i>
              </span>
              <span class="title">Hasil Penilaian</span>
            </nuxt-link>
          </li> -->
          <li class="nav-item">
            <nuxt-link class="sidebar-link" to="/lke/rekap-pd">
              <span class="icon-holder">
                <i class="ti-view-list-alt" style="color:black"></i>
              </span>
              <span style="font-size: small;" class="font-weight-bold title">Rekapitulasi Penilaian PD</span>
            </nuxt-link>
          </li>
          <li class="nav-item">
            <nuxt-link class="sidebar-link" to="/lke/rekomendasi">
              <span class="icon-holder">
                <i class="ti-thumb-up" style="color:black"></i>
              </span>
              <span style="font-size: small;" class="font-weight-bold title">Rekomendasi dan Tindak Lanjut</span>
            </nuxt-link>
          </li>
          <!-- <li class="nav-item dropdown">
            <a class="dropdown-toggle" href="javascript:void(0);">
              <span class="icon-holder">
                <i class="c-grey-800 ti-bookmark-alt"></i>
              </span>
              <span class="font-weight-bold title">LKE</span>
              <span class="arrow">
                <i class="ti-angle-right"></i>
              </span>
            </a>
            <ul class="dropdown-menu">
              <li>
                <nuxt-link class='sidebar-link' to="/lke/penilaian">Penilaian Awal</nuxt-link>
              </li>
              <li>
                <nuxt-link class='sidebar-link' to="/lke/penilaian-2">Penilaian Akhir</nuxt-link>
              </li>
              <li>
                <nuxt-link class='sidebar-link' to="/lke/hasil-penilaian">Hasil Penilaian Awal dan Akhir</nuxt-link>
              </li>
              <li>
                <nuxt-link class='sidebar-link' to="/lke/penilaian-humanis">Hasil Pleno</nuxt-link>
              </li>
              <li>
                <nuxt-link class='sidebar-link' to="/lke/rekap-pd">Rekapitulasi Nilai</nuxt-link>
              </li>
              <li>
                <nuxt-link class='sidebar-link' to="/lke/hasil-self-assessment">Hasil Self Assessment dan Perbaikan</nuxt-link>
              </li>
              <li>
                <nuxt-link class='sidebar-link' to="/lke/rekomendasi">Rekomendasi</nuxt-link>
              </li>
            </ul>
          </li> -->
        </template>

        <template v-else-if="
          $role.isValidatorPerencanaan1() ||
          $role.isValidatorPerencanaan2() ||
          $role.isValidatorPerencanaan3()
        ">
          <li class="nav-item mT-30 actived">
            <nuxt-link class="sidebar-link" to="/validasi-perencanaan">
              <span class="icon-holder">
                <i class="text-info ti-bookmark-alt"></i>
              </span>
              <span class="title">Validasi Perencanaan</span>
            </nuxt-link>
          </li>
        </template>

        <template v-else-if="$role.isviewRaporKinerja()">
          <li class="nav-item dropdown">
            <a class="dropdown-toggle" href="javascript:void(0);">
              <span class="icon-holder">
                <i class="c-grey-800 ti-bookmark-alt"></i>
              </span>
              <span class="font-weight-bold title">Rapor Kinerja</span>
              <span class="arrow">
                <i class="ti-angle-right"></i>
              </span>
            </a>
            <ul class="dropdown-menu">
              <li>
                <nuxt-link class="sidebar-link" to="/rapor-kinerja/1/diagram"><i class="ti-bar-chart mr-2"></i>Diagram Rapor Kinerja</nuxt-link>
              </li>
              <li>
                <nuxt-link class="sidebar-link" to="/rapor-kinerja/1/data"><i class="ti-view-list-alt mr-2"></i>Data Rapor Kinerja</nuxt-link>
              </li>
              <li>
                <nuxt-link class="sidebar-link" to="/rapor-kinerja/1/rank"><i class="ti-stats-up mr-2"></i>Ranking Raport Kinerja</nuxt-link>
              </li>
              <li v-if="
                ($role.isPemerintahDaerah() || $role.isPerangkatDaerah()) &&
                $helper.getTahunKinerja() == 2023
              ">
                <nuxt-link class="sidebar-link" to="/rapor-kinerja/penambahan-jumlah-output"><i class="ti-plus mr-2"></i>Penambahan Jumlah
                  Output</nuxt-link>
              </li>
              <li>
                <nuxt-link class="sidebar-link" to="/rapor-kinerja/1/langkah-aksi-perbaikan"><i class="ti-check-box mr-2"></i>Langkah Aksi
                  Perbaikan</nuxt-link>
              </li>
            </ul>
          </li>
        </template>

        <template v-else>
          <li class="nav-item dropdown">
            <a class="dropdown" href="javascript:void(0);">
              <span class="icon-holder">
                <i class="ti-dashboard" style="color:black"></i>
              </span>
              <span style="font-size: smaller;" class="font-weight-bold title badge badge-dark">Dashboard</span>
              <span class="arrow">
                <i class="ti-angle-right"></i>
              </span>
            </a>
            <ul class="dropdown-menu">
              <li>
                <nuxt-link class="sidebar-link" to="/dashboard"><i class="ti-layout-grid2 mr-2"></i>Dashboard Pemda</nuxt-link>
              </li>

            </ul>
          </li>

          <li class="nav-item dropdown" v-if="$role.isPerangkatDaerah()">
            <a class="dropdown" href="javascript:void(0);">
              <span class="icon-holder">
                <i class="ti-folder" style="color:black"></i>
              </span>
              <span style="font-size: smaller;" class="font-weight-bold title badge badge-dark">Master Data</span>
              <span class="arrow">
                <i class="ti-angle-right"></i>
              </span>
            </a>
            <ul class="dropdown-menu">
              <li>
                <nuxt-link class="sidebar-link" to="/admin/program">
                  <i class="ti-folder mr-2"></i>Master Program
                </nuxt-link>
              </li>
              <li>
                <nuxt-link class="sidebar-link" to="/admin/kegiatan">
                  <i class="ti-folder mr-2"></i>Master Kegiatan
                </nuxt-link>
              </li>
              <li>
                <nuxt-link class="sidebar-link" to="/admin/sub-kegiatan">
                  <i class="ti-file mr-2"></i>Master Sub Kegiatan
                </nuxt-link>
              </li>
              <li>
                <nuxt-link class="sidebar-link" to="/admin/pegawai">
                  <i class="ti-user mr-2"></i>Master Pegawai
                </nuxt-link>
              </li>
              <li>
                <nuxt-link class="sidebar-link" to="/admin/tim-kerja">
                  <i class="ti-id-badge mr-2"></i>Master Tim Kerja / Pengampu Outcome
                </nuxt-link>
              </li>
            </ul>
          </li>

          <li class="nav-item dropdown">
            <a class="dropdown" href="javascript:void(0);">
              <span class="icon-holder">
                <i class="ti-calendar" style="color:black"></i>
              </span>
              <span style="font-size: smaller;" class="font-weight-bold title badge badge-dark">Perencanaan</span>
              <span class="arrow">
                <i class="ti-angle-right"></i>
              </span>
            </a>
            <ul class="dropdown-menu">
              <li>
                <nuxt-link class="sidebar-link" to="/visi-misi-rpjmd"><i class="ti-bookmark mr-2"></i>Visi, Misi & Tujuan RPJMD</nuxt-link>
              </li>
              <li>
                <nuxt-link class="sidebar-link" to="/sasaran-strategis-rpjmd"><i class="ti-bookmark-alt mr-2"></i>Sasaran Strategis RPJMD & <br />
                  IKU Bupati</nuxt-link>
              </li>
              <li>
                <nuxt-link class="sidebar-link" to="/sasaran-strategis-pd"><i class="ti-bookmark-alt mr-2"></i>Sasaran Strategis <br />
                  Perangkat Daerah</nuxt-link>
              </li>
              <li>
                <nuxt-link class="sidebar-link" to="/kinerja-program"><i class="ti-bar-chart mr-2"></i>Kinerja Program</nuxt-link>
              </li>
              <li>
                <nuxt-link class="sidebar-link" to="/kinerja-kegiatan"><i class="ti-bar-chart-alt mr-2"></i>Kinerja Kegiatan</nuxt-link>
              </li>
              <li>
                <nuxt-link class="sidebar-link" to="/kinerja-sub-kegiatan"><i class="ti-file mr-2"></i>Kinerja Sub Kegiatan</nuxt-link>
              </li>
              <!-- <li>
                <nuxt-link class="sidebar-link" to="/kinerja-kab-kota">Kinerja Kabupaten Kota</nuxt-link>
              </li> -->
              <li>
                <nuxt-link class="sidebar-link" to="/kinerja-langkah-aksi"><i class="ti-write mr-2"></i>Kinerja Langkah Aksi</nuxt-link>
              </li>
              <li>
                <nuxt-link class="sidebar-link" to="/kamus-indikator"><i class="ti-book mr-2"></i>Kamus Indikator</nuxt-link>
              </li>
              <li>
                <nuxt-link class="sidebar-link" to="/kamus-indikator-fungsional"><i class="ti-book mr-2"></i>Kamus Indikator Fungsional</nuxt-link>
              </li>
              <li class="nav-item">
                <nuxt-link class="sidebar-link" to="/perjanjian-kinerja">
                  <i class="ti-notepad mr-2"></i>Perjanjian Kinerja
                </nuxt-link>
              </li>
              <li class="nav-item dropdown">
                <a class="dropdown-toggle" href="javascript:void(0);">
                  <span class="icon-holder mr-2">
                    <i class="c-grey-800 ti-stats-up"></i>
                  </span>
                  <span class="font-weight-bold title badge badge-dark">Display Kinerja Makro</span>
                  <span class="arrow">
                    <i class="ti-angle-right"></i>
                  </span>
                </a>
                <ul class="dropdown-menu">
                  <li>
                    <nuxt-link class="sidebar-link ml-2" to="/display-makro/cascading"><i class="ti-layers mr-2"></i>Cascading</nuxt-link>
                  </li>
                  <li>
                    <nuxt-link class="sidebar-link ml-2" to="/display-makro/rpjmd"><i class="ti-map mr-2"></i>RPJMD Kabupaten Kuningan</nuxt-link>
                  </li>
                  <li>
                    <nuxt-link class="sidebar-link ml-2" to="/display-makro/rkpd"><i class="ti-map-alt mr-2"></i>RKPD Kabupaten Kuningan</nuxt-link>
                  </li>
                  <li>
                    <nuxt-link class="sidebar-link ml-2" to="/display-makro/perjanjian-kinerja"><i class="ti-notepad mr-2"></i>Perjanjian Kinerja
                      Bupati Kabupaten Kuningan</nuxt-link>
                  </li>
                  <li>
                    <nuxt-link class="sidebar-link ml-2" to="/display-makro/rencana-aksi"><i class="ti-write mr-2"></i>Rencana Aksi Bupati Kabupaten
                      Kuningan</nuxt-link>
                  </li>
                  <li>
                    <nuxt-link class="sidebar-link ml-2" to="/display-makro/capaian-kinerja-pemda"><i class="ti-stats-up mr-2"></i>Capaian Kinerja
                      Pemda</nuxt-link>
                  </li>
                  <li>
                    <nuxt-link class="sidebar-link ml-2" to="/display-makro/capaian-kinerja-efisiensi-anggaran"><i class="ti-bar-chart mr-2"></i>Capaian
                      Kinerja dan Efisiensi Anggaran</nuxt-link>
                  </li>
                  <li>
                    <nuxt-link class="sidebar-link ml-2" to="/display-makro/capaian-kinerja-keuangan"><i class="ti-money mr-2"></i>Capaian Kinerja
                      dan Keuangan</nuxt-link>
                  </li>
                  <li>
                    <nuxt-link class="sidebar-link ml-2" to="/display-makro/program-inovatif"><i class="ti-light-bulb mr-2"></i>Program
                      Inovatif</nuxt-link>
                  </li>
                  <li>
                    <nuxt-link class="sidebar-link ml-2" to="/display-makro/capaian-kinerja-kegiatan"><i class="ti-bar-chart-alt mr-2"></i>Capaian Kinerja
                      Kegiatan</nuxt-link>
                  </li>
                  <li>
                    <nuxt-link class="sidebar-link ml-2" to="/display-makro/capaian-kinerja-sub-kegiatan"><i class="ti-file mr-2"></i>Capaian
                      Kinerja Sub Kegiatan</nuxt-link>
                  </li>
                  <li>
                    <nuxt-link class="sidebar-link ml-2" to="/display-makro/capaian-kinerja-aktivitas"><i class="ti-pulse mr-2"></i>Capaian Kinerja
                      Aktivitas</nuxt-link>
                  </li>

                </ul>
              </li>
              <li class="nav-item dropdown">
                <a class="dropdown-toggle" href="javascript:void(0);">
                  <span class="icon-holder mr-2">
                    <i class="c-grey-800 ti-stats-down"></i>
                  </span>
                  <span class="font-weight-bold title badge badge-dark">Display Kinerja Mikro</span>
                  <span class="arrow">
                    <i class="ti-angle-right"></i>
                  </span>
                </a>
                <ul class="dropdown-menu">
                  <li>
                    <nuxt-link class="sidebar-link ml-2" to="/display-mikro/cascading"><i class="ti-layers mr-2"></i>Cascading</nuxt-link>
                  </li>
                  <li>
                    <nuxt-link class="sidebar-link ml-2" to="/display-mikro/renstra"><i class="ti-map mr-2"></i>Renstra</nuxt-link>
                  </li>
                  <li>
                    <nuxt-link class="sidebar-link ml-2" to="/display-mikro/rkt"><i class="ti-map-alt mr-2"></i>RKT</nuxt-link>
                  </li>
                  <li>
                    <nuxt-link class="sidebar-link ml-2" to="/display-mikro/perjanjian-kinerja"><i class="ti-notepad mr-2"></i>Perjanjian
                      Kinerja</nuxt-link>
                  </li>
                  <li>
                    <nuxt-link class="sidebar-link ml-2" to="/display-mikro/rencana-aksi"><i class="ti-write mr-2"></i>Rencana Aksi Perangkat
                      Daerah</nuxt-link>
                  </li>
                  <li>
                    <nuxt-link class="sidebar-link ml-2" to="/display-mikro/capaian-kinerja-pd"><i class="ti-stats-up mr-2"></i>Capaian Kinerja
                      Perangkat Daerah</nuxt-link>
                  </li>
                  <li>
                    <nuxt-link class="sidebar-link ml-2" to="/display-mikro/capaian-kinerja-efisiensi-anggaran"><i class="ti-bar-chart mr-2"></i>Capaian
                      Kinerja dan Efisiensi Anggaran</nuxt-link>
                  </li>
                  <li>
                    <nuxt-link class="sidebar-link ml-2" to="/display-mikro/capaian-kinerja-keuangan"><i class="ti-money mr-2"></i>Capaian Kinerja
                      dan Keuangan Perangkat Daerah</nuxt-link>
                  </li>
                  <li>
                    <nuxt-link class="sidebar-link ml-2" to="/display-mikro/program-inovatif"><i class="ti-light-bulb mr-2"></i>Program
                      Inovatif</nuxt-link>
                  </li>
                  <li>
                    <nuxt-link class="sidebar-link ml-2" to="/display-mikro/capaian-kinerja-kegiatan"><i class="ti-bar-chart-alt mr-2"></i>Capaian Kinerja
                      Kegiatan</nuxt-link>
                  </li>
                  <li>
                    <nuxt-link class="sidebar-link ml-2" to="/display-mikro/capaian-kinerja-sub-kegiatan"><i class="ti-file mr-2"></i>Capaian
                      Kinerja Sub Kegiatan</nuxt-link>
                  </li>
                  <li>
                    <nuxt-link class="sidebar-link ml-2" to="/display-mikro/capaian-kinerja-langkah-aksi"><i class="ti-pulse mr-2"></i>Capaian
                      Kinerja Langkah Aksi</nuxt-link>
                  </li>

                </ul>
              </li>
            </ul>
          </li>

          <li class="nav-item dropdown" v-if="!$role.isSuper() && !$role.isSetda()">
            <a class="dropdown-toggle" href="javascript:void(0);">
              <span class="icon-holder">
                <i class="ti-printer" style="color:black"></i>
              </span>
              <span class="font-weight-bold title badge badge-dark" style="font-size: smaller;">Pelaporan</span>
              <span class="arrow">
                <i class="ti-angle-right"></i>
              </span>
            </a>
            <ul class="dropdown-menu">
              <li>
                <nuxt-link class="sidebar-link" to="/lkip/bab-1"><i class="ti-file mr-2"></i>LKIP BAB I</nuxt-link>
              </li>
              <li>
                <nuxt-link class="sidebar-link" to="/lkip/bab-2"><i class="ti-file mr-2"></i>LKIP BAB II</nuxt-link>
              </li>
              <li>
                <nuxt-link class="sidebar-link" to="/lkip/bab-3"><i class="ti-file mr-2"></i>LKIP BAB III</nuxt-link>
              </li>
              <li>
                <nuxt-link class="sidebar-link" to="/lkip/narasi-pemda"><i class="ti-comment-alt mr-2"></i>LKIP Narasi Pemda</nuxt-link>
              </li>
              <li>
                <nuxt-link class="sidebar-link" to="/lkip/narasi-pd"><i class="ti-comment-alt mr-2"></i>LKIP Narasi PD</nuxt-link>
              </li>
            </ul>
          </li>
          <li class="nav-item dropdown" v-if="!$role.isSetda()">
            <a class="dropdown-toggle" href="javascript:void(0);">
              <span class="icon-holder">
                <i class="ti-ruler-pencil" style="color:black"></i>
              </span>
              <span class="font-weight-bold title badge badge-dark" style="font-size: smaller;">Pengukuran</span>
              <span class="arrow">
                <i class="ti-angle-right"></i>
              </span>
            </a>
            <ul class="dropdown-menu">
              <li>
                <nuxt-link class="sidebar-link" to="/rapor-kinerja/1/diagram"><i class="ti-bar-chart mr-2"></i>Diagram Rapor Kinerja</nuxt-link>
              </li>
              <li>
                <nuxt-link class="sidebar-link" to="/rapor-kinerja/1/diagram-external"><i class="ti-bar-chart-alt mr-2"></i>Diagram Rapor Kinerja
                  Eksternal</nuxt-link>
              </li>
              <li>
                <nuxt-link class="sidebar-link" to="/rapor-kinerja-sasaran-strategis-pd/1/diagram"><i class="ti-stats-up mr-2"></i>Diagram Rapor Kinerja
                  Sasaran Strategis</nuxt-link>
              </li>
              <li>
                <nuxt-link class="sidebar-link" to="/rapor-kinerja/1/data"><i class="ti-view-list-alt mr-2"></i>Data Rapor Kinerja</nuxt-link>
              </li>
              <li>
                <nuxt-link class="sidebar-link" to="/rapor-kinerja/1/data-external"><i class="ti-list mr-2"></i>Data Rapor Kinerja
                  Eksternal</nuxt-link>
              </li>
              <li>
                <nuxt-link class="sidebar-link" to="/rapor-kinerja/1/rank"><i class="ti-stats-up mr-2"></i>Ranking Raport Kinerja</nuxt-link>
              </li>
              <li v-if="
                ($role.isPemerintahDaerah() ||
                  $role.isPerangkatDaerah()) &&
                $helper.getTahunKinerja() == 2023
              ">
                <nuxt-link class="sidebar-link" to="/rapor-kinerja/penambahan-jumlah-output"><i class="ti-plus mr-2"></i>Penambahan Jumlah
                  Output</nuxt-link>
              </li>
              <li>
                <nuxt-link class="sidebar-link" to="/rapor-kinerja/1/langkah-aksi-perbaikan"><i class="ti-check-box mr-2"></i>Langkah Aksi
                  Perbaikan</nuxt-link>
              </li>
              <li>
                <nuxt-link class="sidebar-link" to="/display-mikro/capaian-kinerja-program">
                  <i class="ti-stats-up mr-2"></i>Capaian Kinerja Program
                </nuxt-link>
              </li>
              <li>
                <nuxt-link class="sidebar-link" to="/display-mikro/capaian-kinerja-kegiatan">
                  <i class="ti-stats-up mr-2"></i>Capaian Kinerja Kegiatan
                </nuxt-link>
              </li>
              <li>
                <nuxt-link class="sidebar-link" to="/display-mikro/capaian-kinerja-sub-kegiatan">
                  <i class="ti-stats-up mr-2"></i>Capaian Kinerja Sub Kegiatan
                </nuxt-link>
              </li>

            </ul>
          </li>
          <li class="nav-item dropdown" v-if="!$role.isSetda()">
            <a class="dropdown-toggle" href="javascript:void(0);">
              <span class="icon-holder">
                <i class="ti-shield" style="color:black"></i>
              </span>
              <span class="font-weight-bold title badge badge-dark" style="font-size:smaller;">Evaluasi Akunt.
                Internal</span>
              <span class="arrow">
                <i class="ti-angle-right"></i>
              </span>
            </a>
            <ul class="dropdown-menu">
              <li class="nav-item dropdown" v-if="!$role.isSetda()">
                <a class="dropdown-toggle" href="javascript:void(0);">
                  <span class="icon-holder mr-2">
                    <i class="c-grey-800 ti-comment-alt"></i>
                  </span>
                  <span class="font-weight-bold title badge badge-dark">LKE</span>
                  <span class="arrow">
                    <i class="ti-angle-right"></i>
                  </span>
                </a>
                <ul class="dropdown-menu">
                  <li>
                    <nuxt-link class="sidebar-link ml-2" to="/lke/eviden"><i class="ti-write mr-2"></i>Input Evidence Self Assessment
                    </nuxt-link>
                  </li>
                  <li>
                    <nuxt-link class="sidebar-link ml-2" to="/lke/eviden-2"><i class="ti-pencil mr-2"></i>Input Eviden Perbaikan</nuxt-link>
                  </li>
                  <li>
                    <nuxt-link class="sidebar-link ml-2" to="/lke/hasil-self-assessment"><i class="ti-comment-alt mr-2"></i>Hasil Self Assessment dan
                      Perbaikan</nuxt-link>
                  </li>

                  <li>
                    <nuxt-link class="sidebar-link ml-2" to="/lke/hasil-akhir"><i class="ti-check-box mr-2"></i>Hasil Evaluasi Akhir</nuxt-link>
                  </li>
                  <li>
                    <nuxt-link class="sidebar-link ml-2" to="/lke/hasil"><i class="ti-stats-up mr-2"></i>Hasil Penilaian</nuxt-link>
                  </li>
                </ul>
              </li>
              <li>
                <nuxt-link class="sidebar-link" to="/lke/lhe">
                  <i class="ti-folder mr-2"></i>LHE</nuxt-link>
              </li>
              <li>
                <nuxt-link class="sidebar-link" to="/lke/rekomendasi"><i class="ti-check mr-2"></i>Tindak Lanjut Rekomendasi LHE PD dan Monev
                  Inspektorat</nuxt-link>
              </li>
            </ul>
          </li>

        </template>

        <template v-if="$role.isSuper()">
          <li class="nav-item dropdown mT-30 actived">
            <a class="dropdown" href="javascript:void(0);">
              <span class="icon-holder">
                <i class="text-info ti-folder"></i>
              </span>
              <span class="title">Master Data</span>
              <span class="arrow">
                <i class="ti-angle-right"></i>
              </span>
            </a>
            <ul class="dropdown-menu">
              <li>
                <nuxt-link class="sidebar-link" to="/admin/users">
                  <i class="ti-user mr-2"></i>Daftar Pengguna
                </nuxt-link>
              </li>
              <li>
                <nuxt-link class="sidebar-link" to="/admin/visi-misi">
                  <i class="ti-bookmark mr-2"></i>Master Perencanaan RPJMD
                </nuxt-link>
              </li>
              <li>
                <nuxt-link class="sidebar-link" to="/admin/sasaran-iku-rpjmd">
                  <i class="ti-bookmark-alt mr-2"></i>Master Sasaran & IKU RPJMD
                </nuxt-link>
              </li>
              <li>
                <nuxt-link class="sidebar-link" to="/admin/satuan-kerja">
                  <i class="ti-home mr-2"></i>Master Satuan Kerja / OPD
                </nuxt-link>
              </li>
              <li>
                <nuxt-link class="sidebar-link" to="/admin/program">
                  <i class="ti-folder mr-2"></i>Master Program
                </nuxt-link>
              </li>
              <li>
                <nuxt-link class="sidebar-link" to="/admin/kegiatan">
                  <i class="ti-folder mr-2"></i>Master Kegiatan
                </nuxt-link>
              </li>
              <li>
                <nuxt-link class="sidebar-link" to="/admin/sub-kegiatan">
                  <i class="ti-file mr-2"></i>Master Sub Kegiatan
                </nuxt-link>
              </li>
              <li>
                <nuxt-link class="sidebar-link" to="/admin/pegawai">
                  <i class="ti-id-badge mr-2"></i>Master Pegawai
                </nuxt-link>
              </li>
              <li>
                <nuxt-link class="sidebar-link" to="/admin/tim-kerja">
                  <i class="ti-agenda mr-2"></i>Master Tim Kerja / Pengampu Outcome
                </nuxt-link>
              </li>
              <li>
                <nuxt-link class="sidebar-link" to="/admin/struktur-organisasi">
                  <i class="ti-layers mr-2"></i>Master Unit Kerja / Struktur Organisasi
                </nuxt-link>
              </li>
              <li>
                <nuxt-link class="sidebar-link" to="/admin/banner">
                  <i class="ti-image mr-2"></i>Master Banner
                </nuxt-link>
              </li>
              <li>
                <nuxt-link class="sidebar-link" to="/anggaran-capaian-iku">
                  <i class="ti-money mr-2"></i>Anggaran Capaian IKU
                </nuxt-link>
              </li>
              <li>
                <nuxt-link class="sidebar-link" to="/nilai-sakip-pemda">
                  <i class="ti-stats-up mr-2"></i>Nilai Sakip Pemda
                </nuxt-link>
              </li>
            </ul>
          </li>
        </template>
      </ul>
    </div>
  </div>
</template>

<script>
export default {
  mounted() {
    if ($(window).width() < 992) {
      $(".sidebar-link").click(function () {
        document.body.classList.toggle("is-collapsed");
      });
    }
  },
};
</script>
