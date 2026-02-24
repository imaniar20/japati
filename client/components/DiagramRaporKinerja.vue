<script>
import axios from 'axios';
import { BIconAward } from 'bootstrap-vue'

export default {
  props: {
    satuanKerjaId: {
      type: Number,
      required: true,
    },
    triwulan: {
      type: Number,
      required: true,
      default: 1,
      validator: function (value) {
        return [1, 2, 3, 4].indexOf(value) !== -1
      }
    },
    tahunKinerja: {
      type: [Number, null],
      required: false,
    },
    isExternal: {
      type: Boolean,
      required: false,
      default: null,
    }
  },

  components: {
    BIconAward,
  },

  data() {
    return {
      isBusy: false,
      diagram: null,
      diagramHasil: null,
      chart: {
        enabled: false,
        options: {
          chart: {
            height: 350,
            zoom: {
              enabled: false,
            },
            toolbar: {
              show: true,
              tools: {
                download: false,
              },
            },
          },
          dataLabels: {
            enabled: true,
            offsetY: -10,
            formatter: function(value, { seriesIndex, dataPointIndex, w }) {
              if (w.config.series[seriesIndex].type != 'scatter') return;

              const data = w.config.series[seriesIndex].data[0]

              return `(${data[0].toFixed(1)}, ${data[1].toFixed(1)})`
            }
          },
          tooltip: {
            enabled: false,
          },
          stroke: {
            curve: 'straight'
          },
          yaxis: {
            title: {
              text: 'Kinerja (%)'
            },
            min: 0,
            max: 120,
          },
          xaxis: {},
        },
        series: [],
      },
      chartKinerjaAnggaran: {
        enabled: false,
        options: {
          chart: {
            type: 'bar',
            height: 350,
            zoom: {
              enabled: false,
            },
            toolbar: {
              show: true,
              tools: {
                download: false,
              },
            },
          },
          xaxis: {
            categories: ['Capaian'],
            labels: {
              show: false,
            },
          },
          yaxis: {
            title: {
              text: 'Capaian (%)'
            },
            min: 0,
            max: 100
          },
        },
        series: [],
      }
    }
  },

  computed: {
    triwulanText: function () {
      switch (this.triwulan) {
        case 1:
          return 'I'
          break;
        case 2:
          return 'II'
          break;
        case 3:
          return 'III'
          break;
        case 4:
          return 'IV'
          break;
      
        default:
          return '-'
          break;
      }
    },
    nextTriwulanText: function () {
      switch (this.triwulan) {
        case 1:
          return 'II, III, & IV'
          break;
        case 2:
          return 'III & IV'
          break;
        case 3:
          return 'IV'
          break;
      
        default:
          return 'Tahun Selanjutnya'
          break;
      }
    }
  },

  async mounted() {
    await this.getData();
    await this.getDataPenyebabKegagalan();

    const anggaranTerserap = this.diagram.anggaran_terserap / this.diagram.anggaran * 100

    this.chart.series = [
      {
        name: 'Kinerja',
        type: 'scatter',
        data: [[anggaranTerserap, this.diagram.capaian]]
      },
      {
        name: 'Garis Normal',
        type: 'line',
        data: [[0, 0], [120, 120]]
      }
    ]

    this.chart.options.xaxis = {
      type: 'numeric',
      title: {
        text: 'Anggaran (%)'
      },
      min: 0,
      max: 120,
    }

    this.chartKinerjaAnggaran.series = [
      {
        name: 'Kinerja',
        data: [this.diagram.capaian]
      },
      {
        name: 'Anggaran',
        data: [anggaranTerserap.toFixed(2)]
      },
    ]

    this.chart.enabled = true
    this.chartKinerjaAnggaran.enabled = true
  },

  methods: {
    async getData() {
      this.isBusy = true

      const { data } = await axios.get(`/rapor-kinerja/${this.triwulan}/diagram`, {
        params: {
          satuan_kerja_id: this.satuanKerjaId,
          tahun_kinerja: this.tahunKinerja,
          is_external: this.isExternal ? 1 : 0,
        }
      })

      this.diagram = data
      this.isBusy = false
    },
     async getDataPenyebabKegagalan() {
      this.isBusy = true
      const { data } = await axios.get(`/rapor-kinerja/${this.triwulan}/langkah-aksi-perbaikan`, {
        params: {
          satuan_kerja_id: this.satuanKerjaId,
          tahun_kinerja: this.tahunKinerja,
          is_external: this.isExternal ? 1 : 0,
        }
      })

      this.diagramHasil = data
      this.isBusy = false
    }
  }
}
  
 

</script>

<template>
  <b-card>
    <div v-if="isBusy || !diagram" class="text-center my-5">
      <b-spinner v-if="isBusy"></b-spinner>
      <span v-else>Tidak ada data</span>
    </div>
    <b-row v-else>
      <b-col md="7">
        <b-card class="mb-4 shadow bg-success text-white" style="border-radius:10px;">
          <div class="text-center">
            <h4>RAPORT KINERJA</h4>
            <h6>{{ diagram.satuan_kerja_nama }}</h6> TRIWULAN {{ triwulanText }} TAHUN {{ diagram.tahun_kinerja }}
          </div>
        </b-card>

        <b-card  class="mb-4 shadow bg-success text-white" style="border-radius:10px;" >
          <b-row class="px-2">
            <b-col md="6" lg="3" class="text-white text-center p-1">
              <div class="d-flex justify-content-between flex-column bg-secondary rounded-lg p-4 mx-1 h-100">
                <div>Capaian Kinerja Sasaran Sub Kegiatan</div>
                <h6><b>{{ diagram.capaian }}%</b></h6>
              </div>
            </b-col>
            <b-col md="6" lg="3" class="text-white text-center p-1">
              <div class="d-flex justify-content-between flex-column bg-warning rounded-lg p-4 mx-1 h-100 text-dark">
                <div>Jumlah Sasaran Sub Kegiatan</div>
                <h6><b>{{ diagram.jumlah }}</b></h6>
              </div>
            </b-col>
            <b-col md="6" lg="3" class="text-white text-center p-1">
              <div class="d-flex justify-content-between flex-column bg-warning rounded-lg p-4 mx-1 h-100 text-dark">
                <div>Jumlah yang Tercapai</div>
                <h6><b>{{ diagram.tercapai }}</b></h6>
              </div>
            </b-col>
            <b-col md="6" lg="3" class="text-white text-center p-1">
              <div class="d-flex justify-content-between flex-column bg-warning rounded-lg p-4 mx-1 h-100 text-dark">
                <div>Jumlah yang tidak Tercapai</div>
                <h6><b>{{ diagram.tidak_tercapai }}</b></h6>
              </div>
            </b-col>
          </b-row>
          <b-row class="px-2 mt-2">
            <b-col md="6" lg="3" class="text-white text-center p-1">
              <div class="d-flex justify-content-between flex-column bg-secondary rounded-lg p-4 mx-1 h-100">
                Efisiensi Anggaran <br>
                <h6><b>{{ diagram.efisiensi_anggaran }}%</b></h6>
              </div>
            </b-col>
            <b-col md="6" lg="3" class="text-white text-center p-1">
              <div class="d-flex justify-content-between flex-column bg-warning rounded-lg p-4 mx-1 h-100 text-dark">
                Jumlah Anggaran <br>
                <h6><b>{{ diagram.anggaran | rupiah }}</b></h6>
              </div>
            </b-col>
            <b-col md="6" lg="3" class="text-white text-center p-1">
              <div class="d-flex justify-content-between flex-column bg-warning rounded-lg p-4 mx-1 h-100 text-dark">
                Jumlah yang Terserap <br>
                <h6><b>{{ diagram.anggaran_terserap | rupiah }}</b></h6>
              </div>
            </b-col>
            <b-col md="6" lg="3" class="text-white text-center p-1">
              <div class="d-flex justify-content-between flex-column bg-warning rounded-lg p-4 mx-1 h-100 text-dark">
                Jumlah yang tidak Terserap <br>
                <h6><b>{{ diagram.anggaran_tidak_terserap | rupiah }}</b></h6>
              </div>
            </b-col>
          </b-row>
        </b-card>

        <b-card class="mb-4 shadow bg-success text-white" style="border-radius:10px;">
          <h6 class="mb-2"><b>Penyebab Kegagalan</b></h6>
          <b-table-simple hover responsive table-variant="success">
            <b-thead head-variant="light">
              <b-tr>
                <b-th class="text-center">Jumlah Sasaran Sub Kegiatan tidak Tercapai</b-th>
                <b-th class="text-center">Jumlah Penyebab Kegagalan</b-th>
              </b-tr>
            </b-thead>
            <b-tbody>
              <b-tr>
                <b-td class="text-center"><b>{{ diagram.tidak_tercapai }}</b></b-td>
                <b-td class="text-center"><b>{{ diagram.penyebab_tidak_tercapai }}</b></b-td>
              </b-tr>
            </b-tbody>
          </b-table-simple>
        </b-card>

        <b-card class="mb-4 shadow bg-success text-white" style="border-radius:10px;">
          <h6 class="mb-2"><b>Penyesuaian Intervensi Untuk Triwulan {{ nextTriwulanText }}</b></h6>
          <b-table-simple hover responsive table-variant="success">
            <b-thead head-variant="light">
              <b-tr>
                <b-th class="text-center">Jumlah Langkah Aksi sasaran sub kegiatan yang tidak tercapai (semula)</b-th>
                <b-th class="text-center">Jumlah Langkah Aksi (menjadi)</b-th>
                <b-th class="text-center">Jumlah Langkah Aksi yang mengalami perubahan jadwal</b-th>
              </b-tr>
            </b-thead>
            <b-tbody>
              <b-tr>
                <b-td class="text-center"><b>{{ diagram.langkah_aksi_tidak_tercapai_awal }}</b></b-td>
                <b-td class="text-center"><b>{{ diagram.langkah_aksi_tidak_tercapai_akhir }}</b></b-td>
                <b-td class="text-center"><b>-</b></b-td>
              </b-tr>
            </b-tbody>
          </b-table-simple>
        </b-card>

        <b-card class="mb-4 shadow bg-success text-white" style="border-radius:10px;">
          <h6 class="mb-2"><b>Penyesuaian Anggaran Untuk Triwulan {{ nextTriwulanText }}</b></h6>
          <b-table-simple hover responsive table-variant="success">
            <b-thead head-variant="light">
              <b-tr>
                <b-th class="text-center">Jumlah Anggaran Sub Kegiatan tidak tercapai (semula)</b-th>
                <b-th class="text-center">Jumlah Anggaran Sub Kegiatan (menjadi)</b-th>
                <b-th class="text-center">Usulan perubahan anggaran</b-th>
              </b-tr>
            </b-thead>
            <b-tbody>
              <b-tr>
                <b-td class="text-center"><b>{{ diagram.anggaran_tidak_tercapai | rupiah }}</b></b-td>
                <b-td class="text-center"><b>{{ diagram.anggaran_tidak_tercapai + diagram.usulan_anggaran | rupiah }}</b></b-td>
                <b-td class="text-center"><b>{{ diagram.usulan_anggaran | rupiah }}</b></b-td>
              </b-tr>
            </b-tbody>
          </b-table-simple>
        </b-card>


           <b-card class="mb-4 shadow bg-primary text-white" style="border-radius:10px;">
          <h6 class="mb-2 text-center"><b>Hasil Review</b></h6>
          <div v-if="diagram.tidak_tercapai != 0">
            <p>
              {{ diagram.satuan_kerja_nama }} pada triwulan {{ this.triwulan }} Tahun 2025 memiliki
              output yang harus dicapai dengan target sebanyak {{ diagram.jumlah }} output
              dan terealisasi sebanyak {{ diagram.tercapai }} output sehingga ada output
              yang tidak Tercapai sebanyak {{ diagram.tidak_tercapai }} Adapun output yang
              tidak tercapai, penyebab kegagalan, dan rekomendasi perbaikan langkah
              aksinya yaitu:
            </p>
            <ul v-for="item of diagramHasil" :key="item.sasaran">
              <li>
                <p>
                  {{ item.sasaran }} dengan faktor penyebab kegagalan:
                  <b>{{ item.penyebab.map((itemPenyebab) => itemPenyebab.penyebab).join(", ") }}</b>.
                  Rekomendasi Perbaikan intervensinya adalah sebagai adalah
                  <b>{{ item.langkah_aksi.map((itemLangkahAksi) => itemLangkahAksi.langkah_aksi).join(", ") }}</b>.
                </p>
                </li>
            </ul>
          </div>
          <div v-else>
             <p>
              {{ diagram.satuan_kerja_nama }} pada {{ this.triwulan }} Tahun 2025 memiliki
              output yang harus dicapai dengan target sebanyak {{ diagram.jumlah }} output
              dan terealisasi sebanyak {{ diagram.tercapai }} output sehingga capaikan output adalah sebesar 100%
            </p>
          </div>
       
        </b-card>
      </b-col>

      <b-col md="5">
        <div class="border shadow bg-light mb-4 text-center px-2" style="border-radius:10px;">
          <h1 class="text-primary"><b>Selamat</b> <BIconAward /></h1>
          <h6 class="mt-n2">{{ diagram.satuan_kerja_nama }} meraih Kinerja:</h6>
          <h4 class="text-success"><b>TERBAIK {{ diagram.rank.rank }}</b></h4>
          <h6 class="mt-n2">DARI {{ diagram.rank.total }} PD DAN BIRO</h6>
        </div>

        <b-card class="shadow bg-light mb-4" style="border-radius:10px">
          <div class="mb-2 text-primary text-center font-weight-bold">
            Capaian Kinerja & Anggaran
          </div>
          <apexchart v-if="chartKinerjaAnggaran.enabled" type="bar" height="350" :options="chartKinerjaAnggaran.options" :series="chartKinerjaAnggaran.series"></apexchart>
        </b-card>

        <b-card class="shadow bg-light" style="border-radius:10px">
          <div class="mb-2 text-primary text-center font-weight-bold">
            GRAFIK PROGRESS CAPAIAN KINERJA <br>
            SASARAN SUB KEGIATAN
          </div>
          <apexchart v-if="chart.enabled" :options="chart.options" :series="chart.series"></apexchart>
        </b-card>
      </b-col>
    </b-row>
  </b-card>
</template>