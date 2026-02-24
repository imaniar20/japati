<template>
  <div>
    <b-navbar toggleable="lg" type="dark" variant="blue" class="shadow">
      <b-navbar-brand to="/">
        <img src="~/assets/images/japati.png" alt="japati" title="JAPATI" style="height:40px">
      </b-navbar-brand>

      <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

      <b-collapse id="nav-collapse" is-nav>
        <b-navbar-nav>
        <b-nav-item-dropdown class="kinerja-gubernur" :text="$helper.getTahunKinerjaPublic().toString()">
       <b-dropdown-item 
      v-for="tahun of $const.tahun_kinerja_list" 
      @click="setTahunKinerjaPublic(tahun.key)" 
      :key="tahun.key"
    
        >
          {{ tahun.display }}
        </b-dropdown-item>
      </b-nav-item-dropdown>
          <b-nav-item-dropdown class="kinerja-gubernur" text="Kinerja Gubernur">
            <b-dropdown-item to="/public-display/display-makro/dashboard">Dashboard</b-dropdown-item>
            <b-dropdown-divider></b-dropdown-divider>
            <b-dropdown-item to="/public-display/display-makro/rpjmd">RPJMD Provinsi Jawa Barat</b-dropdown-item>
            <b-dropdown-divider></b-dropdown-divider>
            <b-dropdown-item to="/public-display/display-makro/rkpd">RKPD Provinsi Jawa Barat</b-dropdown-item>
            <b-dropdown-divider></b-dropdown-divider>
            <b-dropdown-item to="/public-display/display-makro/perjanjian-kinerja">Perjanjian Kinerja Gubernur Jawa Barat</b-dropdown-item>
            <b-dropdown-divider></b-dropdown-divider>
            <b-dropdown-item to="/public-display/display-makro/rencana-aksi">Rencana Aksi Gubernur Jawa Barat</b-dropdown-item>
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
            <b-dropdown-item to="/public-display/display-makro/cascading">Cascading</b-dropdown-item>
          </b-nav-item-dropdown>
          <b-nav-item-dropdown class="kinerja-pd" text="Kinerja Perangkat Daerah">
            <b-dropdown-item to="/public-display/display-mikro/renstra">Renstra</b-dropdown-item>
            <b-dropdown-divider></b-dropdown-divider>
            <b-dropdown-item to="/public-display/display-mikro/rkt">RKT</b-dropdown-item>
            <b-dropdown-divider></b-dropdown-divider>
            <b-dropdown-item to="/public-display/display-mikro/perjanjian-kinerja">Perjanjian Kinerja</b-dropdown-item>
            <b-dropdown-divider></b-dropdown-divider>
            <!-- <b-dropdown-item to="/public-display/display-mikro/rencana-aksi">Rencana Aksi Perangkat Daerah</b-dropdown-item>
            <b-dropdown-divider></b-dropdown-divider> -->
            <b-dropdown-item to="/public-display/display-mikro/rencana-aksi-terintegrasi">Rencana Aksi Perangkat Daerah Terintegrasi</b-dropdown-item>
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
            <b-dropdown-item to="/public-display/display-mikro/cascading">Cascading</b-dropdown-item>
          </b-nav-item-dropdown>
          <!-- <b-nav-item to="/public-display/arsitektur-kinerja" style="font-size: 18px;">Arsitektur Kinerja</b-nav-item> -->
          <!-- <b-nav-item to="/public-display/arsitektur-kinerja-cross-cutting" style="font-size: 18px;">Arsitektur Kinerja Cross-Cutting</b-nav-item> -->
        </b-navbar-nav>

        <!-- Right aligned nav items -->
        <b-navbar-nav class="ml-auto">
          <b-nav-item to="/login" v-if="!authenticated">Login</b-nav-item>
          <b-nav-item to="/dashboard" v-else>Dashboard</b-nav-item>
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
  /deep/ .kinerja-gubernur .nav-link, /deep/ .kinerja-pd .nav-link {
    font-size: 18px;
  }
  /deep/ .kinerja-gubernur .dropdown-menu{
    background: #e1e1e1;
  }
  /deep/ .kinerja-pd .dropdown-menu{
    background: #e1e1e1;
  }
  /deep/ .dropdown-menu .router-link-active{
    background: #1E88E5;
    color:white;
  }
  .bg-blue{
    // background: linear-gradient(to bottom, #3277f8 0%, #4d73f0 50%, #0c71f6 51%, #176ef0 71%, #0b68e2 100%);
    background: #1E88E5;
  }
  /deep/ .dropdown-divider{
    margin:0;
    border-top: 1px solid #b5b5b5;
  }
  /deep/ .dropdown-item:hover, /deep/ .dropdown-item:focus {
    font-weight: bold;

  }
  /deep/ .navbar-nav .nav-link {
    color: white !important;
  }
</style>
