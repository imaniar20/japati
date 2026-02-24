<script>
import axios from 'axios';
import { mapGetters } from 'vuex'

export default {
  middleware: 'auth',
  
  data() {
    return {
      filter: {
        satuan_kerja_id: null,
      },
      data: [],
      isBusy: false,
    }
  },

  computed: {
    ...mapGetters({
      user: 'auth/user'
    }),
  },

  watch: {
    filter: {
      handler: function () {
        if (this.filter.satuan_kerja_id) this.getData()
      },
      deep: true,
    }
  },

  mounted() {
    if (!this.$role.isSuper() && !this.$role.isSetda()) {
      this.filter.satuan_kerja_id = this.user.satuan_kerja_id
    }
  },

  methods: {
    async getData() {
      this.isBusy = true

      const { data } = await axios.get(`diagram-iku-gubernur/${this.filter.satuan_kerja_id}`, this.filter)

      this.data = data
      this.isBusy = false
    },
    compareRataNasional(iku) {
      const rataNasional = iku.sasaran_strategis_rpjmd.rata_nasional
      const capaian = iku.sasaran_strategis_rpjmd[this.$helper.getKeyTahunDinamis('capaian', this.tahunKinerja)]

      if (!rataNasional || !capaian) return {
        text: null,
      }

      if (capaian > rataNasional) {
        return {
          text: 'Lebih tinggi',
          nilai: capaian - rataNasional,
          class: 'text-success'
        }
      } else if (capaian < rataNasional) {
        return {
          text: 'Lebih rendah',
          nilai: rataNasional - capaian,
          class: 'text-danger'
        }
      } else {
        return {
          text: 'Sama',
          nilai: null,
          class: 'text-secondary'
        }
      }
    },
  },
};
</script>

<template>
  <b-card>
    <div>
      <b-row>
        <b-col sm="6" md="4">
          <FilterSatuanKerja v-if="$role.isSuper() || $role.isSetda()" v-model="filter.satuan_kerja_id" :is-setda="$role.isSetda()" />
        </b-col>
      </b-row>
    </div>

    <div v-if="!filter.satuan_kerja_id || isBusy" class="text-center my-5">
      <h4>
        <span v-if="isBusy">Memuat data...</span>
        <span v-else>Pilih satuan kerja terlebih dahulu</span>
      </h4>
    </div>
    <div v-for="iku of data" :key="iku.id" v-else>
      <div class="iku-container">
        <div class="py-3 px-4 bg-success text-white text-center h3 mx-auto" style="max-width: 600px; border-radius:15px;">{{ iku.sasaran_strategis_rpjmd.sasaran_strategis.sasaran }}</div>
        <div class="d-flex justify-content-center" style="font-family: 'Intro', sans-serif; display:inline-flex; flex-direction: column;margin: 15px;">
          <div class="d-flex justify-content-center pos-r">
            <div class="d-flex align-items-center" style="justify-content: flex-end;padding:50px 0;">
              <div class="d-flex align-items-center">
                <div class="bg-yellow-left">
                  <img src="~/assets/images/capaian-iku/hand.png" style="height: 80px;" alt="">
                </div>
                <div class="yellow-outline-box" style="padding:15px">
                  <p class="m-0 text-center mb-3">Efisiensi Sumber Daya {{ iku.efisiensi_sumber_daya.persen }}% <br>
                  ({{ iku.efisiensi_sumber_daya.nominal | rupiah }}) </p>
                  <div class="card card-shadow text-center text-blue mb-3">
                    <div class="card-body">
                      <h4 class="m-0" style="font-size: 25px; font-weight: normal;">Capaian {{ $helper.getTahunKinerja() }}</h4>
                      <h1 class="m-0" style="font-size: 40px;">{{ iku.sasaran_strategis_rpjmd[$helper.getKeyTahunDinamis('capaian', $helper.getTahunKinerja())] || '-' }}%</h1>
                    </div>
                  </div>
                  <div style="padding: 0 15px;">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="font-weight-bold">Target</div>
                        <div>{{ iku.sasaran_strategis_rpjmd[$helper.getKeyTahunDinamis('target', $helper.getTahunKinerja())] || '-' }}</div>
                      </div>
                      <div class="col-md-6">
                        <div class="font-weight-bold">Realisasi</div>
                        <div>{{ iku.sasaran_strategis_rpjmd[$helper.getKeyTahunDinamis('realisasi', $helper.getTahunKinerja())] || '-' || '-' }}</div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div style="position: relative; width:50px;flex:0 0 50px;">
              <div id="div2" style="width: 10px; height: 10px; top:50%; transform:translateY(-50%); right:-5px; border-radius:50%;background:#aaaaaa; position:absolute;"></div>
              <svg width="50" height="100%" style="position: absolute;top:0;left:0;">
                <line x1="0" y1="45%" x2="50" y2="50%" stroke-width="2" stroke="#aaaaaa" />
              </svg>
              <svg width="50" height="100%" style="position: absolute;top:0;left:0;">
                <line x1="50" y1="100%" x2="50" y2="50%" stroke-width="4" stroke="#aaaaaa" />
              </svg>
            </div>
            <!-- ! bagian kanan diagram dikondisikan saja kalau ada 2 item atau 3 item -->
            <!-- kanan dengan 2 item -->
            <!-- <div>
                      <div class="d-flex row-2 mb-1" style="align-items: center;">
                          <svg width="50" height="100%" style="position: absolute;top:0;"><line x1="0" y1="50%" x2="50" y2="25%"  stroke-width="2" stroke="#aaaaaa"/></svg>
                          <div class="d-flex align-items-center" style="margin-left: 50px;">
                              <div class="yellow-outline-box">
                                  <h5 class="m-0 text-green" style="font-size:16px">Pertumbuhan</h5>
                                  <p class="m-0">Capaian : 7,55%  </p>
                                  <p class="m-0">Realisasi : 0,02 </p>
                              </div>
                              <div class="bg-yellow-right">
                                  <img src="~/assets/images/capaian-iku/icon.png" style="height: 30px;" alt="">
                              </div>
                          </div>
                      </div>
                      <div class="d-flex row-2 mb-1" style="align-items: center;">
                          <svg width="50" height="100%" style="position: absolute;top:0;"><line x1="0" y1="50%" x2="50" y2="75%"  stroke-width="2" stroke="#aaaaaa"/></svg>
                          <div class="d-flex align-items-center" style="margin-left: 50px;">
                              <div class="yellow-outline-box">
                                  <p class="m-0">Capaian terhadap target <br> akhir RPJMD <span class="text-green font-weight-bold"> 99,52%</span>  </p>
                              </div>
                              <div class="bg-yellow-right">
                                  <img src="~/assets/images/capaian-iku/capaian.png" style="height: 30px;" alt="">
                              </div>
                          </div>
                      </div>
                  </div> -->
            <!-- kanan dengan 3 item -->
            <div>
              <div class="d-flex row-3 mb-1" style="align-items: center;">
                <svg width="50" height="100%" style="position: absolute;top:0;">
                  <line x1="0" y1="50%" x2="50" y2="15%" stroke-width="2" stroke="#aaaaaa" />
                </svg>
                <div class="d-flex align-items-center" style="margin-left: 50px;">
                  <div class="yellow-outline-box">
                    <h5 class="m-0 text-green" style="font-size:16px">Pertumbuhan</h5>
                    <p class="m-0">Capaian : {{ (iku.sasaran_strategis_rpjmd[$helper.getKeyTahunDinamis('capaian', $helper.getTahunKinerja())] || 0) - (iku.sasaran_strategis_rpjmd[$helper.getKeyTahunDinamis('capaian', $helper.getTahunKinerja() - 1)]) || 0 }}% </p>
                    <p class="m-0">Realisasi : {{ (iku.sasaran_strategis_rpjmd[$helper.getKeyTahunDinamis('realisasi', $helper.getTahunKinerja())] || 0) - (iku.sasaran_strategis_rpjmd[$helper.getKeyTahunDinamis('realisasi', $helper.getTahunKinerja() - 1)]) || 0 }} </p>
                  </div>
                  <div class="bg-yellow-right">
                    <img src="~/assets/images/capaian-iku/icon.png" style="height: 30px;" alt="">
                  </div>
                </div>
              </div>
              <div class="d-flex row-3 mb-1" style="align-items: center;">
                <svg width="50" height="100%" style="position: absolute;top:0;">
                  <line x1="0" y1="50%" x2="50" y2="45%" stroke-width="2" stroke="#aaaaaa" />
                </svg>
                <div class="d-flex align-items-center" style="margin-left: 50px;">
                  <div class="yellow-outline-box">
                    <p class="m-0">Capaian terhadap target <br> akhir RPJMD <span class="text-green font-weight-bold"> {{ iku.sasaran_strategis_rpjmd.capaian_terhadap_target_akhir || '-' }}%</span> </p>
                  </div>
                  <div class="bg-yellow-right">
                    <img src="~/assets/images/capaian-iku/capaian.png" style="height: 30px;" alt="">
                  </div>
                </div>
              </div>
              <div class="d-flex row-3 mb-1" style="align-items: center;">
                <svg width="50" height="100%" style="position: absolute;top:0;">
                  <line x1="0" y1="50%" x2="50" y2="80%" stroke-width="2" stroke="#aaaaaa" />
                </svg>
                <div class="d-flex align-items-center" style="margin-left: 50px;">
                  <div class="yellow-outline-box">
                    <p class="m-0">
                      <span v-if="!compareRataNasional(iku).text">Belum ada data dari rata-rata nasional</span>
                      <span v-else>{{ compareRataNasional(iku).text }} <span class="font-weight-bold" :class="compareRataNasional(iku).class">{{ compareRataNasional(iku).nilai }}</span> dari <br> Rata-Rata Nasional </span>
                    </p>
                  </div>
                  <div class="bg-yellow-right">
                    <img src="~/assets/images/capaian-iku/rata2.png" style="height: 30px;" alt="">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="text-center align-items-center" style="display: inline-flex;flex-direction: column;">
            <div class="main-box blue-box" style="margin: 0 auto;">
              <h1 class="m-0">{{ iku.indikator }}</h1>
            </div>
            <div style="border-left: 2px solid #aaaaaa;height: 20px;width:2px;"></div>
            <ul class="tree m-0">
              <li>
                <span class="text-blue font-weight-bold">DIDUKUNG</span>
                <ul class="row">
                  <li class="col" v-for="(sasaranSatker, index) of iku.sasaran_strategis_satker" :key="index">
                    <span class="lightblue-box">{{ sasaranSatker }}</span>
                  </li>
                </ul>
              </li>
            </ul>
            <div class="text-center text-italic">
              <span style="color:#adadaa;">Sumber: BPS, Bappenas RI, Kementrian Agama RI dan Perangkat Daerah/Biro</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </b-card>
</template>

<style scoped>
.d-flex {
  display: flex;
}
*,
::before,
::after {
  box-sizing: border-box;
}
@font-face {
  font-family: "Intro";
  src: url("~assets/fonts/Intro-Bold.woff2") format("woff2"),
    url("~assets/fonts/Intro-Bold.woff") format("woff");
  font-weight: bold;
  font-style: normal;
  font-display: swap;
}

@font-face {
  font-family: "Intro";
  src: url("~assets/fonts/Intro-Regular.woff2") format("woff2"),
    url("~assets/fonts/Intro-Regular.woff") format("woff");
  font-weight: normal;
  font-style: normal;
  font-display: swap;
}
body {
  font-family: "Intro", sans-serif;
  margin: 0;
}

.card {
  position: relative;
  display: flex;
  flex-direction: column;
  min-width: 0;
  word-wrap: break-word;
  background-color: #fff;
  background-clip: border-box;
  border: 0px solid rgba(0, 0, 0, 0.125);
  border-radius: 0.25rem;

  box-shadow: 0 2px 10px -1px rgba(69, 90, 50, 0.3);
  margin-bottom: 30px;
  transition: box-shadow 0.2s ease-in-out;
}
.card-body {
  padding: 10px;
}
.row {
  display: flex;
  flex-wrap: wrap;
  margin-right: -15px;
  margin-left: -15px;
}
.col-1,
.col-2,
.col-3,
.col-4,
.col-5,
.col-6,
.col-7,
.col-8,
.col-9,
.col-10,
.col-11,
.col-12,
.col,
.col-auto,
.col-sm-1,
.col-sm-2,
.col-sm-3,
.col-sm-4,
.col-sm-5,
.col-sm-6,
.col-sm-7,
.col-sm-8,
.col-sm-9,
.col-sm-10,
.col-sm-11,
.col-sm-12,
.col-sm,
.col-sm-auto,
.col-md-1,
.col-md-2,
.col-md-3,
.col-md-4,
.col-md-5,
.col-md-6,
.col-md-7,
.col-md-8,
.col-md-9,
.col-md-10,
.col-md-11,
.col-md-12,
.col-md,
.col-md-auto,
.col-lg-1,
.col-lg-2,
.col-lg-3,
.col-lg-4,
.col-lg-5,
.col-lg-6,
.col-lg-7,
.col-lg-8,
.col-lg-9,
.col-lg-10,
.col-lg-11,
.col-lg-12,
.col-lg,
.col-lg-auto,
.col-xl-1,
.col-xl-2,
.col-xl-3,
.col-xl-4,
.col-xl-5,
.col-xl-6,
.col-xl-7,
.col-xl-8,
.col-xl-9,
.col-xl-10,
.col-xl-11,
.col-xl-12,
.col-xl,
.col-xl-auto {
  position: relative;
  width: 100%;
  padding-right: 15px;
  padding-left: 15px;
}
@media (min-width: 768px) {
  .col-md-4 {
    flex: 0 0 33.33333%;
    max-width: 33.33333%;
  }
  .col-md-6 {
    flex: 0 0 50%;
    max-width: 50%;
  }
  .col-md-8 {
    flex: 0 0 66.66666%;
    max-width: 66.66666%;
  }
  .d-md-flex {
    display: flex;
  }
}
.mb-1 {
  margin-bottom: 5px !important;
}
.mb-3 {
  margin-bottom: 15px !important;
}
.card-shadow {
  border-radius: 1em;
  box-shadow: 0 0.125rem 0.75rem #2f72ac;
}
.yellow-outline-box {
  border: 2px solid #fccd29;
  border-radius: 20px;
  padding: 15px 10px;
  position: relative;
  z-index: 2;
  background-color: white;
}
.text-green {
  color: #0a9348;
}
.m-0 {
  margin: 0;
}
.align-items-center {
  align-items: center;
}
.justify-content-center {
  justify-content: center;
}
.bg-yellow-left {
  background-color: #fccd29;
  padding: 5px 10px;
  border-top-left-radius: 10px;
  border-bottom-left-radius: 10px;
  padding-right: 10px;
  margin-right: -5px;
  position: relative;
  z-index: 0;
}
.bg-yellow-right {
  background-color: #fccd29;
  padding: 5px 10px;
  border-top-right-radius: 10px;
  border-bottom-right-radius: 10px;
  padding-left: 10px;
  margin-left: -5px;
  position: relative;
  z-index: 0;
}
.font-weight-bold {
  font-weight: bold;
}
.text-center {
  text-align: center;
}
.text-blue {
  color: #2f72ac;
}
.pos-r {
  position: relative;
}
.main-box {
  padding: 15px;
  border-radius: 15px;
  font-size: 15px;
  display: inline-block;
}
.green-box {
  background-color: #0a9348;
  color: white;
}
.blue-box {
  background-color: #0e5da0;
  color: white;
}
.yellow-box {
  background-color: #fbcc28;
  color: black;
}
.w-100 {
  width: 100%;
}
.tree,
.tree ul,
.tree li {
  list-style: none;
  margin: 0;
  padding: 0;
  position: relative;
}

.tree {
  margin: 0 0 1em;
  text-align: center;
}
.tree ul {
  width: 100%;
}
.tree li {
  display: table-cell;
  padding: 20px 0 0;
  vertical-align: top;
}
/* _________ */
.tree li:before {
  outline: solid 1px #aaa;
  content: "";
  left: 0;
  position: absolute;
  right: 0;
  top: 0;
}
.tree li:first-child:before {
  left: 50%;
}
.tree li:last-child:before {
  right: 50%;
}

.tree > li > code,
.tree > li > span {
  border: none !important;
  border-radius: 0.2em;
  display: inline-block;
  margin: 0 0.5em 20px;
  padding: 0.2em 20px;
  position: relative;
  max-width: 250px;
}
.tree code,
.tree span {
  /* border: solid .1em #aaa; */
  border-radius: 0.5em;
  display: inline-block;
  margin: 0 0.5em 20px;
  padding: 0.5em 20px;
  position: relative;
  max-width: 250px;
}

/* | */
.tree ul:before,
.tree code:before,
.tree span:before {
  outline: solid 1px #aaa;
  content: "";
  height: 20px;
  left: 50%;
  position: absolute;
}
.tree ul:before {
  top: -20px;
}
.tree code:before,
.tree span:before {
  top: -21px;
}

/* The root node doesn't connect upwards */
.tree > li {
  margin-top: 0;
  padding-top: 0;
}
.tree > li:before,
.tree > li:after,
.tree > li > code:before,
.tree > li > span:before {
  outline: none;
}
.lightblue-box {
  background-color: #21a2dc;
  color: white;
}
.col {
  flex-basis: 0;
  flex-grow: 1;
  max-width: 100%;
}
.row-2 {
  min-height: 50%;
}
.row-3 {
  min-height: 33%;
}
.text-italic {
  font-style: italic;
}
@media (max-width:767px) {
  .iku-container{
    transform:scale(.6);
    transform-origin: top center;
    margin-bottom:-400px;
  }
}
</style>
