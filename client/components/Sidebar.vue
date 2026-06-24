<template>
  <div class="sidebar">
    <div class="sidebar-inner">
      <!-- ### $Sidebar Header ### -->
      <div class="sidebar-logo">
        <div class="peers ai-c fxw-nw">
          <div class="peer peer-greed">
            <nuxt-link to="/" class="sidebar-link td-n">
              <div class="peers ai-c fxw-nw">
                <div class="peer">
                  <div
                    class="logo text-center align-items-center justify-content-center"
                    style="display: flex"
                  >
                    <img
                      src="~/assets/images/japati.png"
                      alt=""
                      style="height: 32px"
                    />
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
      <ul class="sidebar-menu scrollable pos-r bg-lightyellow">
        <template v-if="$role.isValidatorBappeda()">
          <li class="nav-item">
            <nuxt-link
              class="sidebar-link"
              to="/kamus-indikator-validasi-bappeda"
            >
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
              <span class="title"
                >Validasi Sasaran Strategis Perangkat Daerah</span
              >
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
            <nuxt-link
              class="sidebar-link"
              to="/sasaran-strategis-pd/validasi-pengampu"
            >
              <span class="icon-holder">
                <i class="text-info ti-bookmark-alt"></i>
              </span>
              <span class="title"
                >Validasi Pengampu Sasaran Strategis Perangkat Daerah</span
              >
            </nuxt-link>
          </li>
          <li class="nav-item">
            <nuxt-link
              class="sidebar-link"
              to="/kinerja-program/validasi-pengampu"
            >
              <span class="icon-holder">
                <i class="text-info ti-bookmark-alt"></i>
              </span>
              <span class="title">Validasi Pengampu Kinerja Program</span>
            </nuxt-link>
          </li>
          <li class="nav-item">
            <nuxt-link
              class="sidebar-link"
              to="/kinerja-kegiatan/validasi-pengampu"
            >
              <span class="icon-holder">
                <i class="text-info ti-bookmark-alt"></i>
              </span>
              <span class="title">Validasi Pengampu Kinerja Kegiatan</span>
            </nuxt-link>
          </li>
          <li class="nav-item">
            <nuxt-link
              class="sidebar-link"
              to="/kinerja-sub-kegiatan/validasi-pengampu"
            >
              <span class="icon-holder">
                <i class="text-info ti-bookmark-alt"></i>
              </span>
              <span class="title">Validasi Pengampu Kinerja Sub Kegiatan</span>
            </nuxt-link>
          </li>
        </template>

        <template
          v-else-if="$role.isValidatorLKE() || $role.isValidatorPleno()"
        >
          <li class="nav-item mT-10">
            <nuxt-link class="sidebar-link" to="/lke/penilaian">
              <span class="icon-holder">
                <i class="ti-list" style="color:black"></i>
                </span>
                <span  style="font-size: small;" class="font-weight-bold title" 
                  >Penilaian Awal</span
                >
            </nuxt-link>
          </li>
      <li class="nav-item">
            <nuxt-link class="sidebar-link" to="/lke/penilaian-2">
               <span class="icon-holder">
                <i class="ti-list" style="color:black"></i>
                </span>
                <span  style="font-size: small;" class="font-weight-bold title" 
                  >Penilaian Akhir</span
                >
            </nuxt-link>
          </li>
         <li class="nav-item">
            <nuxt-link class="sidebar-link" to="/lke/penilaian-humanis">
               <span class="icon-holder">
                <i class="ti-list" style="color:black"></i>
                </span>
                <span  style="font-size: small;" class="font-weight-bold title" 
                  >Penilaian Pleno</span
                >
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
                <i class="ti-list" style="color:black"></i>
                </span>
                <span  style="font-size: small;" class="font-weight-bold title" 
                  >Hasil Penilaian</span
                >
            </nuxt-link>
          </li>
            <li class="nav-item">
            <nuxt-link class="sidebar-link" to="/lke/status-penilaian">
                <span class="icon-holder">
                <i class="ti-list" style="color:black"></i>
                </span>
                <span  style="font-size: small;" class="font-weight-bold title" 
                  >Status Penilaian</span
                >
            </nuxt-link>
          </li>
      <li class="nav-item">
            <nuxt-link class="sidebar-link" to="/lke/lhe">
               <span class="icon-holder">
                <i class="ti-list" style="color:black"></i>
                </span>
                <span  style="font-size: small;" class="font-weight-bold title" 
                  >LHE</span
                >
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
                <i class="ti-list" style="color:black"></i>
                </span>
                <span  style="font-size: small;" class="font-weight-bold title" 
                  >Rekapitulasi Penilaian PD</span
                >
            </nuxt-link>
          </li>
          <li class="nav-item">
            <nuxt-link class="sidebar-link" to="/lke/rekomendasi">
             <span class="icon-holder">
                <i class="ti-list" style="color:black"></i>
                </span>
                <span  style="font-size: small;" class="font-weight-bold title" 
                  >Rekomendasi dan Tindak Lanjut</span
                >
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

        <template
          v-else-if="
            $role.isValidatorPerencanaan1() ||
            $role.isValidatorPerencanaan2() ||
            $role.isValidatorPerencanaan3()
          "
        >
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
                <nuxt-link class="sidebar-link" to="/rapor-kinerja/1/diagram"
                  >Diagram Rapor Kinerja</nuxt-link
                >
              </li>
              <li>
                <nuxt-link class="sidebar-link" to="/rapor-kinerja/1/data"
                  >Data Rapor Kinerja</nuxt-link
                >
              </li>
              <li>
                <nuxt-link class="sidebar-link" to="/rapor-kinerja/1/rank"
                  >Ranking Raport Kinerja</nuxt-link
                >
              </li>
              <li
                v-if="
                  ($role.isPemerintahDaerah() || $role.isPerangkatDaerah()) &&
                  $helper.getTahunKinerja() == 2023
                "
              >
                <nuxt-link
                  class="sidebar-link"
                  to="/rapor-kinerja/penambahan-jumlah-output"
                  >Penambahan Jumlah Output</nuxt-link
                >
              </li>
              <li>
                <nuxt-link
                  class="sidebar-link"
                  to="/rapor-kinerja/1/langkah-aksi-perbaikan"
                  >Langkah Aksi Perbaikan</nuxt-link
                >
              </li>
            </ul>
          </li>
        </template>

        <template v-else>
          <li class="nav-item dropdown">
            <a class="dropdown" href="javascript:void(0);">
              <span class="icon-holder">
               <i class="ti-list" style="color:black"></i>
              </span>
              <span  style="font-size: smaller;" class="font-weight-bold title badge badge-dark" 
                >Dashboard</span
              >
              <span class="arrow">
                <i class="ti-angle-right"></i>
              </span>
            </a>
            <ul class="dropdown-menu">
              <li>
                <nuxt-link class="sidebar-link" to="/dashboard"
                  >Dashboard Pemda</nuxt-link
                >
              </li>

            </ul>
          </li>
          
          <li class="nav-item dropdown" v-if="$role.isPerangkatDaerah()">
            <a class="dropdown" href="javascript:void(0);">
              <span class="icon-holder">
               <i class="ti-list" style="color:black"></i>
              </span>
              <span  style="font-size: smaller;" class="font-weight-bold title badge badge-dark" 
                >Master Data</span
              >
              <span class="arrow">
                <i class="ti-angle-right"></i>
              </span>
            </a>
            <ul class="dropdown-menu">
              <li>
                <nuxt-link class="sidebar-link" to="/admin/program">
                  Master Program
                </nuxt-link>
              </li>
              <li>
                <nuxt-link class="sidebar-link" to="/admin/pegawai">
                  Master Pegawai
                </nuxt-link>
              </li>
              <li>
                <nuxt-link class="sidebar-link" to="/admin/tim-kerja">
                  Master Tim Kerja / Pengampu Outcome
                </nuxt-link>
              </li>
            </ul>
          </li>

          <li class="nav-item dropdown">
            <a class="dropdown" href="javascript:void(0);">
              <span class="icon-holder">
               <i class="ti-list" style="color:black"></i>
              </span>
              <span  style="font-size: smaller;" class="font-weight-bold title badge badge-dark" 
                >Perencanaan</span
              >
              <span class="arrow">
                <i class="ti-angle-right"></i>
              </span>
            </a>
            <ul class="dropdown-menu">
              <li>
                <nuxt-link class="sidebar-link" to="/visi-misi-rpjmd"
                  >Visi, Misi & Tujuan RPJMD</nuxt-link
                >
              </li>
              <li>
                <nuxt-link class="sidebar-link" to="/sasaran-strategis-rpjmd"
                  >Sasaran Strategis RPJMD & <br />
                  IKU Bupati</nuxt-link
                >
              </li>
              <li>
                <nuxt-link class="sidebar-link" to="/sasaran-strategis-pd"
                  >Sasaran Strategis <br />
                  Perangkat Daerah</nuxt-link
                >
              </li>
              <li>
                <nuxt-link class="sidebar-link" to="/kinerja-program"
                  >Kinerja Program</nuxt-link
                >
              </li>
              <li>
                <nuxt-link class="sidebar-link" to="/kinerja-kegiatan"
                  >Kinerja Kegiatan</nuxt-link
                >
              </li>
              <li>
                <nuxt-link class="sidebar-link" to="/kinerja-sub-kegiatan"
                  >Kinerja Sub Kegiatan</nuxt-link
                >
              </li>
                <li>
                <nuxt-link class="sidebar-link" to="/kinerja-kab-kota"
                  >Kinerja Kabupaten Kota</nuxt-link
                >
              </li>
              <li>
                <nuxt-link class="sidebar-link" to="/kinerja-langkah-aksi"
                  >Kinerja Langkah Aksi</nuxt-link
                >
              </li>
              <li>
                <nuxt-link class="sidebar-link" to="/kamus-indikator"
                  >Kamus Indikator</nuxt-link
                >
              </li>
              <li>
                <nuxt-link class="sidebar-link" to="/kamus-indikator-fungsional"
                  >Kamus Indikator Fungsional</nuxt-link
                >
              </li>
               <li class="nav-item">
              <nuxt-link class="sidebar-link" to="/perjanjian-kinerja">
                Perjanjian Kinerja
              </nuxt-link>
            </li>
              <li class="nav-item dropdown">
                <a class="dropdown-toggle" href="javascript:void(0);">
                  <span class="icon-holder mr-2">
                    <i class="c-grey-800 ti-stats-up"></i>
                  </span>
                  <span class="font-weight-bold title badge badge-dark"
                    >Display Kinerja Makro</span
                  >
                  <span class="arrow">
                    <i class="ti-angle-right"></i>
                  </span>
                </a>
                <ul class="dropdown-menu">
                  <li>
                    <nuxt-link
                      class="sidebar-link ml-2"
                      to="/display-makro/cascading"
                      >Cascading</nuxt-link
                    >
                  </li>
                  <li>
                    <nuxt-link
                      class="sidebar-link ml-2"
                      to="/display-makro/rpjmd"
                      >RPJMD Kabupaten Kuningan</nuxt-link
                    >
                  </li>
                  <li>
                    <nuxt-link
                      class="sidebar-link ml-2"
                      to="/display-makro/rkpd"
                      >RKPD Kabupaten Kuningan</nuxt-link
                    >
                  </li>
                  <li>
                    <nuxt-link
                      class="sidebar-link ml-2"
                      to="/display-makro/perjanjian-kinerja"
                      >Perjanjian Kinerja Bupati Kabupaten Kuningan</nuxt-link
                    >
                  </li>
                  <li>
                    <nuxt-link
                      class="sidebar-link ml-2"
                      to="/display-makro/rencana-aksi"
                      >Rencana Aksi Bupati Kabupaten Kuningan</nuxt-link
                    >
                  </li>
                  <li>
                    <nuxt-link
                      class="sidebar-link ml-2"
                      to="/display-makro/capaian-kinerja-pemda"
                      >Capaian Kinerja Pemda</nuxt-link
                    >
                  </li>
                  <li>
                    <nuxt-link
                      class="sidebar-link ml-2"
                      to="/display-makro/capaian-kinerja-efisiensi-anggaran"
                      >Capaian Kinerja dan Efisiensi Anggaran</nuxt-link
                    >
                  </li>
                  <li>
                    <nuxt-link
                      class="sidebar-link ml-2"
                      to="/display-makro/capaian-kinerja-keuangan"
                      >Capaian Kinerja dan Keuangan</nuxt-link
                    >
                  </li>
                  <li>
                    <nuxt-link
                      class="sidebar-link ml-2"
                      to="/display-makro/program-inovatif"
                      >Program Inovatif</nuxt-link
                    >
                  </li>
                  <li>
                    <nuxt-link
                      class="sidebar-link ml-2"
                      to="/display-makro/capaian-kinerja-kegiatan"
                      >Capaian Kinerja Kegiatan</nuxt-link
                    >
                  </li>
                  <li>
                    <nuxt-link
                      class="sidebar-link ml-2"
                      to="/display-makro/capaian-kinerja-sub-kegiatan"
                      >Capaian Kinerja Sub Kegiatan</nuxt-link
                    >
                  </li>
                  <li>
                    <nuxt-link
                      class="sidebar-link ml-2"
                      to="/display-makro/capaian-kinerja-aktivitas"
                      >Capaian Kinerja Aktivitas</nuxt-link
                    >
                  </li>
                  
                </ul>
              </li>
              <li class="nav-item dropdown">
                <a class="dropdown-toggle" href="javascript:void(0);">
                  <span class="icon-holder mr-2">
                    <i class="c-grey-800 ti-stats-down"></i>
                  </span>
                  <span class="font-weight-bold title badge badge-dark"
                    >Display Kinerja Mikro</span
                  >
                  <span class="arrow">
                    <i class="ti-angle-right"></i>
                  </span>
                </a>
                <ul class="dropdown-menu">
                   <li>
                    <nuxt-link
                      class="sidebar-link ml-2"
                      to="/display-mikro/cascading"
                      >Cascading</nuxt-link
                    >
                  </li>
                  <li>
                    <nuxt-link
                      class="sidebar-link ml-2"
                      to="/display-mikro/renstra"
                      >Renstra</nuxt-link
                    >
                  </li>
                  <li>
                    <nuxt-link class="sidebar-link ml-2" to="/display-mikro/rkt"
                      >RKT</nuxt-link
                    >
                  </li>
                  <li>
                    <nuxt-link
                      class="sidebar-link ml-2"
                      to="/display-mikro/perjanjian-kinerja"
                      >Perjanjian Kinerja</nuxt-link
                    >
                  </li>
                  <li>
                    <nuxt-link
                      class="sidebar-link ml-2"
                      to="/display-mikro/rencana-aksi"
                      >Rencana Aksi Perangkat Daerah</nuxt-link
                    >
                  </li>
                  <li>
                    <nuxt-link
                      class="sidebar-link ml-2"
                      to="/display-mikro/capaian-kinerja-pd"
                      >Capaian Kinerja Perangkat Daerah</nuxt-link
                    >
                  </li>
                  <li>
                    <nuxt-link
                      class="sidebar-link ml-2"
                      to="/display-mikro/capaian-kinerja-efisiensi-anggaran"
                      >Capaian Kinerja dan Efisiensi Anggaran</nuxt-link
                    >
                  </li>
                  <li>
                    <nuxt-link
                      class="sidebar-link ml-2"
                      to="/display-mikro/capaian-kinerja-keuangan"
                      >Capaian Kinerja dan Keuangan Perangkat Daerah</nuxt-link
                    >
                  </li>
                  <li>
                    <nuxt-link
                      class="sidebar-link ml-2"
                      to="/display-mikro/program-inovatif"
                      >Program Inovatif</nuxt-link
                    >
                  </li>
                  <li>
                    <nuxt-link
                      class="sidebar-link ml-2"
                      to="/display-mikro/capaian-kinerja-kegiatan"
                      >Capaian Kinerja Kegiatan</nuxt-link
                    >
                  </li>
                  <li>
                    <nuxt-link
                      class="sidebar-link ml-2"
                      to="/display-mikro/capaian-kinerja-sub-kegiatan"
                      >Capaian Kinerja Sub Kegiatan</nuxt-link
                    >
                  </li>
                  <li>
                    <nuxt-link
                      class="sidebar-link ml-2"
                      to="/display-mikro/capaian-kinerja-langkah-aksi"
                      >Capaian Kinerja Langkah Aksi</nuxt-link
                    >
                  </li>
                 
                </ul>
              </li>
            </ul>
          </li>

          <li
            class="nav-item dropdown"
            v-if="!$role.isSuper() && !$role.isSetda()"
          >
            <a class="dropdown-toggle" href="javascript:void(0);">
              <span class="icon-holder">
               <i class="ti-list" style="color:black"></i>
              </span>
              <span class="font-weight-bold title badge badge-dark" style="font-size: smaller;"
                >Pelaporan</span
              >
              <span class="arrow">
                <i class="ti-angle-right"></i>
              </span>
            </a>
            <ul class="dropdown-menu">
              <li>
                <nuxt-link class="sidebar-link" to="/lkip/bab-1"
                  >LKIP BAB I</nuxt-link
                >
              </li>
              <li>
                <nuxt-link class="sidebar-link" to="/lkip/bab-2"
                  >LKIP BAB II</nuxt-link
                >
              </li>
              <li>
                <nuxt-link class="sidebar-link" to="/lkip/bab-3"
                  >LKIP BAB III</nuxt-link
                >
              </li>
              <li>
                <nuxt-link class="sidebar-link" to="/lkip/narasi-pemda"
                  >LKIP Narasi Pemda</nuxt-link
                >
              </li>
              <li>
                <nuxt-link class="sidebar-link" to="/lkip/narasi-pd"
                  >LKIP Narasi PD</nuxt-link
                >
              </li>
            </ul>
          </li>
               <li
            class="nav-item dropdown"
            v-if="!$role.isSetda()"
          >
            <a class="dropdown-toggle" href="javascript:void(0);">
              <span class="icon-holder">
               <i class="ti-list" style="color:black"></i>
              </span>
              <span class="font-weight-bold title badge badge-dark" style="font-size: smaller;"
                >Pengukuran</span
              >
              <span class="arrow">
                <i class="ti-angle-right"></i>
              </span>
            </a>
            <ul class="dropdown-menu">
              <li>
                    <nuxt-link
                      class="sidebar-link"
                      to="/rapor-kinerja/1/diagram"
                      >Diagram Rapor Kinerja</nuxt-link
                    >
                  </li>
                  <li>
                    <nuxt-link
                      class="sidebar-link"
                      to="/rapor-kinerja/1/diagram-external"
                      >Diagram Rapor Kinerja Eksternal</nuxt-link
                    >
                  </li>
                  <li>
                    <nuxt-link
                      class="sidebar-link"
                      to="/rapor-kinerja-sasaran-strategis-pd/1/diagram"
                      >Diagram Rapor Kinerja Sasaran Strategis</nuxt-link
                    >
                  </li>
                  <li>
                    <nuxt-link class="sidebar-link" to="/rapor-kinerja/1/data"
                      >Data Rapor Kinerja</nuxt-link
                    >
                  </li>
                  <li>
                    <nuxt-link
                      class="sidebar-link"
                      to="/rapor-kinerja/1/data-external"
                      >Data Rapor Kinerja Eksternal</nuxt-link
                    >
                  </li>
                  <li>
                    <nuxt-link class="sidebar-link" to="/rapor-kinerja/1/rank"
                      >Ranking Raport Kinerja</nuxt-link
                    >
                  </li>
                  <li
                    v-if="
                      ($role.isPemerintahDaerah() ||
                        $role.isPerangkatDaerah()) &&
                      $helper.getTahunKinerja() == 2023
                    "
                  >
                    <nuxt-link
                      class="sidebar-link"
                      to="/rapor-kinerja/penambahan-jumlah-output"
                      >Penambahan Jumlah Output</nuxt-link
                    >
                  </li>
                  <li>
                    <nuxt-link
                      class="sidebar-link"
                      to="/rapor-kinerja/1/langkah-aksi-perbaikan"
                      >Langkah Aksi Perbaikan</nuxt-link
                    >
                  </li>
                  <li>
                    <nuxt-link class="sidebar-link" to="/display-mikro/capaian-kinerja-program">
                      Capaian Kinerja Program
                    </nuxt-link>
                  </li>
                  <li>
                    <nuxt-link class="sidebar-link" to="/display-mikro/capaian-kinerja-kegiatan">
                      Capaian Kinerja Kegiatan
                    </nuxt-link>
                  </li>
                  <li>
                    <nuxt-link class="sidebar-link" to="/display-mikro/capaian-kinerja-sub-kegiatan">
                      Capaian Kinerja Sub Kegiatan
                    </nuxt-link>
                  </li>

            </ul>
          </li>
          <li class="nav-item dropdown" v-if="!$role.isSetda()">
            <a class="dropdown-toggle" href="javascript:void(0);">
              <span class="icon-holder">
                <i class="ti-list" style="color:black"></i>
              </span>
              <span class="font-weight-bold title badge badge-dark" style="font-size:smaller;"
                >Evaluasi Akunt. Internal</span
              >
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
                  <span class="font-weight-bold title badge badge-dark"
                    >LKE</span
                  >
                  <span class="arrow">
                    <i class="ti-angle-right"></i>
                  </span>
                </a>
                <ul class="dropdown-menu">
                  <li>
                    <nuxt-link class="sidebar-link ml-2" to="/lke/eviden"
                      >Input Evidence Self Assessment
                    </nuxt-link>
                  </li>
                  <li>
                    <nuxt-link class="sidebar-link ml-2" to="/lke/eviden-2"
                      >Input Eviden Perbaikan</nuxt-link
                    >
                  </li>
                  <li>
                    <nuxt-link
                      class="sidebar-link ml-2"
                      to="/lke/hasil-self-assessment"
                      >Hasil Self Assessment dan Perbaikan</nuxt-link
                    >
                  </li>
                
                  <li>
                    <nuxt-link class="sidebar-link ml-2" to="/lke/hasil-akhir"
                      >Hasil Evaluasi Akhir</nuxt-link
                    >
                  </li>
                    <li>
                      <nuxt-link class="sidebar-link ml-2" to="/lke/hasil"
                      >Hasil Penilaian</nuxt-link
                    >
                  </li>
                </ul>
              </li>
              <li>
                <nuxt-link
                  class="sidebar-link"
                to="/lke/lhe">
                  LHE</nuxt-link
                >
              </li>
              <li>
                <nuxt-link class="sidebar-link" to="/lke/rekomendasi"
                  >Tindak Lanjut Rekomendasi LHE PD dan Monev
                  Inspektorat</nuxt-link
                >
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
                  Daftar Pengguna
                </nuxt-link>
              </li>
              <li>
                <nuxt-link class="sidebar-link" to="/admin/visi-misi">
                  Master Perencanaan RPJMD
                </nuxt-link>
              </li>
              <li>
                <nuxt-link class="sidebar-link" to="/admin/sasaran-iku-rpjmd">
                  Master Sasaran & IKU RPJMD
                </nuxt-link>
              </li>
              <li>
                <nuxt-link class="sidebar-link" to="/admin/satuan-kerja">
                  Master Satuan Kerja / OPD
                </nuxt-link>
              </li>
              <li>
                <nuxt-link class="sidebar-link" to="/admin/program">
                  Master Program
                </nuxt-link>
              </li>
              <li>
                <nuxt-link class="sidebar-link" to="/admin/pegawai">
                  Master Pegawai
                </nuxt-link>
              </li>
              <li>
                <nuxt-link class="sidebar-link" to="/admin/tim-kerja">
                  Master Tim Kerja / Pengampu Outcome
                </nuxt-link>
              </li>
              <li>
                <nuxt-link class="sidebar-link" to="/admin/struktur-organisasi">
                  Master Unit Kerja / Struktur Organisasi
                </nuxt-link>
              </li>
              <li>
                <nuxt-link class="sidebar-link" to="/admin/banner">
                  Master Banner
                </nuxt-link>
              </li>
              <li>
                <nuxt-link class="sidebar-link" to="/anggaran-capaian-iku">
                  Anggaran Capaian IKU
                </nuxt-link>
              </li>
              <li>
                <nuxt-link class="sidebar-link" to="/nilai-sakip-pemda">
                  Nilai Sakip Pemda
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
