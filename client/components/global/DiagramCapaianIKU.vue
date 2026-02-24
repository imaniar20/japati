<template>
  <div class="diagram-container">
    <div class="py-3 px-4 bg-success text-white text-center h3 mx-auto" style="max-width: 600px; border-radius:15px;">{{ data.sasaran_strategis_rpjmd.sasaran_strategis.sasaran }}</div>
    <div class="d-flex justify-content-center" style="font-family: 'Intro', sans-serif; display:inline-flex; flex-direction: column;margin: 15px;">
      <div class="d-flex justify-content-center pos-r">
        <div class="d-flex align-items-center" style="justify-content: flex-end;padding:50px 0;">
          <div class="d-flex align-items-center">

            <div class="bg-yellow-left">
              <img src="~/assets/images/capaian-iku/hand.png" style="height: 80px;" alt="">
            </div>
            <div class="yellow-outline-box" style="padding:15px">
              <p class="m-0 text-center mb-3">Efisiensi Sumber Daya {{ data.efisiensi_sumber_daya.persen || '-' }}% <br>
                <span v-if="data.efisiensi_sumber_daya.nominal">({{ data.efisiensi_sumber_daya.nominal | rupiah }})</span>
                <span v-else>-</span> </p>
              <div class="card card-shadow text-center text-blue mb-3">
                <div class="card-body">
                  <h4 class="m-0" style="font-size: 25px; font-weight: normal;">Capaian {{ tahunKinerja }}</h4>
                  <h1 class="m-0" style="font-size: 40px;">{{ data.sasaran_strategis_rpjmd[$helper.getKeyTahunDinamis('capaian', tahunKinerja)] || '-' }}%</h1>
                </div>
              </div>
              <div style="padding: 0 15px;">
                <div class="row" style="margin:0 -5px">
                  <div class="col-md-6" style="padding:0 5px;">
                    <div class="font-weight-bold">Target</div>
                    <div>{{ data.sasaran_strategis_rpjmd[$helper.getKeyTahunDinamis('target', tahunKinerja)] || '-' }}</div>
                  </div>
                  <div class="col-md-6" style="padding:0 5px;">
                    <div class="font-weight-bold">Realisasi</div>
                    <div>{{ data.sasaran_strategis_rpjmd[$helper.getKeyTahunDinamis('realisasi', tahunKinerja)] || '-' || '-' }}</div>
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
                              <p class="m-0">Capaian : {{ (data.sasaran_strategis_rpjmd[$helper.getKeyTahunDinamis('capaian', tahunKinerja)] || 0) - (data.sasaran_strategis_rpjmd[$helper.getKeyTahunDinamis('capaian', tahunKinerja - 1)]) || 0 }} </p>
                              <p class="m-0">Realisasi : {{ (data.sasaran_strategis_rpjmd[$helper.getKeyTahunDinamis('realisasi', tahunKinerja)] || 0) - (data.sasaran_strategis_rpjmd[$helper.getKeyTahunDinamis('realisasi', tahunKinerja - 1)]) || 0 }} </p>
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
                              <p class="m-0">Capaian terhadap target <br> akhir RPJMD <span class="text-green font-weight-bold"> {{ data.sasaran_strategis_rpjmd.capaian_terhadap_target_akhir || '-' }}</span>  </p>
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
                <p class="m-0">Capaian : {{ (data.sasaran_strategis_rpjmd[$helper.getKeyTahunDinamis('capaian', tahunKinerja)] || 0) - (data.sasaran_strategis_rpjmd[$helper.getKeyTahunDinamis('capaian', tahunKinerja - 1)]) || 0 | decimalDigit}}%</p>
                <p class="m-0">Realisasi : {{ data.sasaran_strategis_rpjmd[$helper.getKeyTahunDinamis('perbandingan_realisasi_tahun', tahunKinerja)] || '-' }} </p>
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
                <p class="m-0">Capaian terhadap target <br> akhir RPJMD <span class="text-green font-weight-bold"> {{ data.sasaran_strategis_rpjmd[$helper.getKeyTahunDinamis('perbandingan_realisasi_target', tahunKinerja)] || '-'}}%</span> </p>
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
                  <span v-if="!compareRataNasional().text">Belum ada data dari rata-rata nasional</span>
                  <span v-else>{{ compareRataNasional().text }} <span class="font-weight-bold" :class="compareRataNasional().class">{{ compareRataNasional().nilai | decimalDigit}}</span> dari <br> Rata-Rata Nasional </span>
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
          <h3 class="m-0">{{ data.indikator }}</h3>
        </div>
        <!-- <div style="border-left: 2px solid #aaaaaa;height: 20px;width:2px;"></div>
        <ul class="tree m-0">
          <li>
            <span class="text-blue font-weight-bold">DIDUKUNG</span>
            <ul class="row">
              <li class="col">
                <span class="lightblue-box">Presentasi Pengembangan Kebudayaan</span>
              </li>
              <li class="col">
                <span class="lightblue-box">Presentasi Pengembangan Kebudayaan</span>
              </li>
              <li class="col">
                <span class="lightblue-box">Presentasi Pengembangan Kebudayaan</span>
              </li>
              <li class="col">
                <span class="lightblue-box">Presentasi Pengembangan Kebudayaan</span>
              </li>
              <li class="col">
                <span class="lightblue-box">Presentasi Pengembangan Kebudayaan</span>
              </li>
              <li class="col">
                <span class="lightblue-box">Presentasi Pengembangan Kebudayaan</span>
              </li>
              <li class="col">
                <span class="lightblue-box">Presentasi Pengembangan Kebudayaan</span>
              </li>
              <li class="col">
                <span class="lightblue-box">Presentasi Pengembangan Kebudayaan</span>
              </li>
              <li class="col">
                <span class="yellow-box">Presentasi Pengembangan Kebudayaan</span>
              </li>
              <li class="col">
                <span class="green-box">Presentasi Pengembangan Kebudayaan</span>
              </li>
            </ul>
          </li>
        </ul>

        <div class="text-center text-italic">
          <span style="color:#adadaa;">Sumber: BPS, Bappenas RI, Kementrian Agama RI dan Perangkat Daerah/Biro</span>
        </div> -->
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    data: {
      type: Object,
    },
    tahunKinerja: {
      type: Number,
    }
  },
  methods: {
    compareRataNasional() {
      const rataNasional = this.data.sasaran_strategis_rpjmd.rata_nasional
      const realisasi = Number(this.data.sasaran_strategis_rpjmd[this.$helper.getKeyTahunDinamis('realisasi', this.tahunKinerja)])
      const indikatorId = this.data.sasaran_strategis_rpjmd.indikator_sasaran_strategis_id 

      if (!rataNasional || !realisasi || isNaN(realisasi)) return {
        text: null,
      }

      // lebih rendah adalah lebih baik
      // misal Persentase Penduduk Miskin (persen)
      const isReverse = [3].includes(indikatorId)

      if (realisasi > rataNasional) {
        return {
          text: 'Lebih tinggi',
          nilai: realisasi - rataNasional,
          class: isReverse
            ? 'text-danger'
            : 'text-success'
        }
      } else if (realisasi < rataNasional) {
        return {
          text: 'Lebih rendah',
          nilai: rataNasional - realisasi,
          class: isReverse
            ? 'text-success'
            : 'text-danger'
        }
      } else {
        return {
          text: 'Sama',
          nilai: null,
          class: 'text-secondary'
        }
      }
    }
  }
}
</script>

<style scoped>
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
  .diagram-container{

    -ms-transform: scale(0.55);
    -webkit-transform: scale(0.55);
    transform: scale(.55);
  }
}
</style>
