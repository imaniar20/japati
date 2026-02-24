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
    }
  },

  components: {
    BIconAward,
  },

  data() {
    return {
      isBusy: false,
      diagram: null,
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
    await this.getData()

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
        }
      })

      this.diagram = data
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
        <b-card class="shadow bg-success text-white" style="border-radius:10px;">
          <div class="text-center h-100">
            <h4>RAPORT KINERJA</h4>
            <h6>{{ diagram.satuan_kerja_nama }}</h6> TRIWULAN {{ triwulanText }} TAHUN {{ diagram.tahun_kinerja }}
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
      </b-col>
    </b-row>
  </b-card>
</template>