<template>
  <div>
    <b-navbar toggleable="lg" type="dark" variant="blue" class="shadow">
      <b-navbar-brand to="/">
        <img src="~/assets/logo/sakti_logo.png" alt="SAKTI" title="SAKTI"
          style="height:32px; width:auto; object-fit:contain; margin-top:-3px;">
      </b-navbar-brand>
      <span class="text-white"
        style="font-size: 30px; color: aliceblue; padding-left: 10px; padding-right: 10px;">|</span>
      <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

      <b-collapse id="nav-collapse" is-nav>
        <b-navbar-nav>
          <b-nav-item-dropdown class="kinerja-tahun">
            <template #button-content>
              <i class="ti-calendar mr-1"></i> Kinerja Bupati {{ $helper.getTahunKinerjaPublic() }}
            </template>
            <b-dropdown-item v-for="tahun of $const.tahun_kinerja_list" @click="setTahunKinerjaPublic(tahun.key)"
              :key="tahun.key">
              {{ tahun.display }}
            </b-dropdown-item>
          </b-nav-item-dropdown>
          <!-- <b-nav-item-dropdown class="kinerja-gubernur"> -->
          <b-nav-item-dropdown class="kinerja-tahun">
            <template #button-content>
              <i class="ti-crown mr-1"></i> Kinerja Bupati
            </template>
            <b-dropdown-item to="/public-display/display-makro/dashboard"><i
                class="ti-layout-grid2 mr-2"></i>Dashboard</b-dropdown-item>
            <b-dropdown-divider></b-dropdown-divider>
            <b-dropdown-item to="/public-display/display-makro/rpjmd"><i class="ti-map mr-2"></i>RPJMD Kabupaten
              Kuningan</b-dropdown-item>
            <b-dropdown-divider></b-dropdown-divider>
            <b-dropdown-item to="/public-display/display-makro/rkpd"><i class="ti-map-alt mr-2"></i>RKPD Kabupaten
              Kuningan</b-dropdown-item>
            <b-dropdown-divider></b-dropdown-divider>
            <b-dropdown-item to="/public-display/display-makro/perjanjian-kinerja"><i
                class="ti-notepad mr-2"></i>Perjanjian
              Kinerja Bupati Kabupaten
              Kuningan</b-dropdown-item>
            <b-dropdown-divider></b-dropdown-divider>
            <b-dropdown-item to="/public-display/display-makro/rencana-aksi"><i class="ti-write mr-2"></i>Rencana Aksi
              Bupati Kabupaten
              Kuningan</b-dropdown-item>
            <!-- <b-dropdown-divider></b-dropdown-divider>
            <b-dropdown-item to="/public-display/display-makro/capaian-kinerja-pemda">Capaian Kinerja Pemda</b-dropdown-item>
            <b-dropdown-divider></b-dropdown-divider>
            <b-dropdown-item to="/public-display/display-makro/capaian-kinerja-efisiensi-anggaran">Capaian Kinerja dan Efisiensi Anggaran</b-dropdown-item>
            <b-dropdown-divider></b-dropdown-divider>
            <b-dropdown-item to="/public-display/display-makro/capaian-kinerja-keuangan">Capaian Kinerja dan Keuangan</b-dropdown-item>
            <b-dropdown-divider></b-dropdown-divider>
            <b-dropdown-item to="/public-display/display-makro/program-inovatif">Program Inovatif</b-dropdown-item>
            <b-dropdown-divider></b-dropdown-divider>
            <b-dropdown-item to="/public-display/display-makro/capaian-kinerja-kegiatan">Capaian Kinerja Kegiatan</b-dropdown-item>
            <b-dropdown-divider></b-dropdown-divider>
            <b-dropdown-item to="/public-display/display-makro/capaian-kinerja-sub-kegiatan">Capaian Kinerja Sub Kegiatan</b-dropdown-item>
            <b-dropdown-divider></b-dropdown-divider>
            <b-dropdown-item to="/public-display/display-makro/capaian-kinerja-aktivitas">Capaian Kinerja Aktivitas</b-dropdown-item> -->
            <b-dropdown-divider></b-dropdown-divider>
            <b-dropdown-item to="/public-display/display-makro/cascading"><i
                class="ti-layers mr-2"></i>Cascading</b-dropdown-item>
          </b-nav-item-dropdown>
          <b-nav-item-dropdown class="kinerja-tahun">
            <template #button-content>
              <i class="ti-briefcase mr-1"></i> Kinerja Perangkat Daerah
            </template>
            <b-dropdown-item to="/public-display/display-mikro/renstra"><i
                class="ti-map mr-2"></i>Renstra</b-dropdown-item>
            <b-dropdown-divider></b-dropdown-divider>
            <b-dropdown-item to="/public-display/display-mikro/rkt"><i class="ti-map-alt mr-2"></i>RKT</b-dropdown-item>
            <b-dropdown-divider></b-dropdown-divider>
            <b-dropdown-item to="/public-display/display-mikro/perjanjian-kinerja"><i
                class="ti-notepad mr-2"></i>Perjanjian
              Kinerja</b-dropdown-item>
            <b-dropdown-divider></b-dropdown-divider>
            <!-- <b-dropdown-item to="/public-display/display-mikro/rencana-aksi">Rencana Aksi Perangkat Daerah</b-dropdown-item>
            <b-dropdown-divider></b-dropdown-divider> -->
            <b-dropdown-item to="/public-display/display-mikro/rencana-aksi-terintegrasi" disabled
              title="Belum Terintegrasi dengan e-Kinerja"><i class="ti-write mr-2"></i>Rencana Aksi
              Perangkat Daerah Terintegrasi</b-dropdown-item>
            <!-- <b-dropdown-divider></b-dropdown-divider>
            <b-dropdown-item to="/public-display/display-mikro/realisasi-rencana-aksi-terintegrasi">Realisasi Rencana Aksi Perangkat Daerah Terintegrasi</b-dropdown-item>
            <b-dropdown-divider></b-dropdown-divider>
            <b-dropdown-item to="/public-display/display-mikro/capaian-kinerja-pd">Capaian Kinerja Perangkat Daerah</b-dropdown-item>
            <b-dropdown-divider></b-dropdown-divider>
            <b-dropdown-item to="/public-display/display-mikro/capaian-kinerja-efisiensi-anggaran">Capaian Kinerja dan Efisiensi Anggaran</b-dropdown-item>
            <b-dropdown-divider></b-dropdown-divider>
            <b-dropdown-item to="/public-display/display-mikro/capaian-kinerja-keuangan">Capaian Kinerja dan Keuangan Perangkat Daerah</b-dropdown-item>
            <b-dropdown-divider></b-dropdown-divider>
            <b-dropdown-item to="/public-display/display-mikro/program-inovatif">Program Inovatif</b-dropdown-item>
            <b-dropdown-divider></b-dropdown-divider>
            <b-dropdown-item to="/public-display/display-mikro/capaian-kinerja-program">Capaian Kinerja Program</b-dropdown-item>
            <b-dropdown-divider></b-dropdown-divider>
            <b-dropdown-item to="/public-display/display-mikro/capaian-kinerja-kegiatan">Capaian Kinerja Kegiatan</b-dropdown-item>
            <b-dropdown-divider></b-dropdown-divider>
            <b-dropdown-item to="/public-display/display-mikro/capaian-kinerja-sub-kegiatan">Capaian Kinerja Sub Kegiatan</b-dropdown-item> -->
            <b-dropdown-divider></b-dropdown-divider>
            <!-- <b-dropdown-item to="/public-display/display-mikro/capaian-kinerja-langkah-aksi">Capaian Kinerja Langkah Aksi</b-dropdown-item>
            <b-dropdown-divider></b-dropdown-divider> -->
            <b-dropdown-item to="/public-display/display-mikro/cascading"><i
                class="ti-layers mr-2"></i>Cascading</b-dropdown-item>
          </b-nav-item-dropdown>
          <!-- <b-nav-item to="/public-display/arsitektur-kinerja" style="font-size: 18px;">Arsitektur Kinerja</b-nav-item> -->
          <!-- <b-nav-item to="/public-display/arsitektur-kinerja-cross-cutting" style="font-size: 18px;">Arsitektur Kinerja Cross-Cutting</b-nav-item> -->
        </b-navbar-nav>

        <!-- Right aligned nav items -->
        <b-navbar-nav class="ml-auto">
          <b-nav-item to="/login" v-if="!authenticated"><i class="ti-key mr-2"></i>Login</b-nav-item>
          <b-nav-item to="/dashboard" v-else><i class="ti-layout-grid2 mr-2"></i>Dashboard</b-nav-item>
        </b-navbar-nav>
      </b-collapse>
    </b-navbar>

    <b-card v-if="$route.meta && $route.meta.label && $route.path.match('public-display*')" class="text-center">
      <h3>{{ $route.meta.label }}</h3>
    </b-card>

    <Nuxt />
  </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  name: 'GuestLayout',

  computed: {
    ...mapGetters({
      authenticated: 'auth/check'
    }),
  },

  methods: {
    setTahunKinerjaPublic(tahun) {
      this.$store.dispatch('tahun-kinerja/setTahunKinerjaPublic', tahun)
      this.$store.dispatch('tahun-kinerja/setTahunKinerja', tahun)
      location.reload()
    }
  }
}
</script>
<style scoped lang="scss">
/deep/ .kinerja-gubernur .nav-link,
/deep/ .kinerja-pd .nav-link {
  font-size: 18px;
}

/deep/ .kinerja-gubernur .dropdown-menu {
  background: #e1e1e1;
}

/deep/ .kinerja-pd .dropdown-menu {
  background: #e1e1e1;
}

/deep/ .dropdown-menu .router-link-active {
  background: #1E88E5;
  color: white;
}

.bg-blue {
  // background: linear-gradient(to bottom, #3277f8 0%, #4d73f0 50%, #0c71f6 51%, #176ef0 71%, #0b68e2 100%);
  background: #1E88E5;
}

/deep/ .dropdown-divider {
  margin: 0;
  border-top: 1px solid #b5b5b5;
}

/deep/ .dropdown-item:hover,
/deep/ .dropdown-item:focus {
  font-weight: bold;

}

/deep/ .navbar-nav .nav-link {
  color: white !important;
  position: relative;
  padding-bottom: 6px !important;
  transition: opacity 0.2s ease;

  &::before {
    content: "";
    position: absolute;
    bottom: 0;
    left: 50%;
    width: 0;
    height: 2px;
    background-color: #ffffff;
    transform: translateX(-50%);
    transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  }
}

/* Aktifkan garis bawah saat hover link, hover container dropdown, atau menu aktif */
/deep/ .navbar-nav .nav-link:hover::before,
/deep/ .navbar-nav .dropdown:hover .nav-link::before,
/deep/ .navbar-nav .show>.nav-link::before,
/deep/ .navbar-nav .nav-link.router-link-active::before,
/deep/ .navbar-nav .nav-link.router-link-exact-active::before {
  width: 100%;
}

// Open dropdown on hover (desktop viewport) with fluid slide & fade transition
@media (min-width: 992px) {
  /deep/ .navbar-nav .dropdown .dropdown-menu {
    display: block !important;
    margin-top: 0 !important;
    opacity: 0;
    visibility: hidden;
    transform: translateY(10px);
    transition: opacity 0.25s cubic-bezier(0.4, 0, 0.2, 1), transform 0.25s cubic-bezier(0.4, 0, 0.2, 1), visibility 0.25s;
    pointer-events: none;
  }

  /deep/ .navbar-nav .dropdown:hover .dropdown-menu {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
    pointer-events: auto;
  }

  /deep/ .navbar-nav .dropdown-toggle {
    pointer-events: none;
  }
}
</style>
