<script>
import { BIconAward } from 'bootstrap-vue'

export default {
  props: {
    satuanKerjaNama: {
      required: true,
      type: String,
    },
    predikat: {
      required: true,
      type: Array,
    },
    skor: {
      required: true,
      type: Number,
    }
  },

  components: {
    BIconAward,
  },

  data() {
    return {
      chart: {
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
              text: 'Capaian',
            },
            min: 0,
            max: 100,
            labels: {
              show: true,
              offsetX: 10,
              formatter: (value) => {
                let label = ''

                if (value == 90) {
                  label = 'AA'
                } else if (value == 80) {
                  label = 'A'
                } else if (value == 70) {
                  label = 'BB'
                } else if (value == 60) {
                  label = 'B'
                } else if (value == 50) {
                  label = 'CC'
                } else if (value == 30) {
                  label = 'C'
                } else if (value == 0) {
                  label = 'D'
                }

                return `${label} ${value}`
              }
            }
          },
          plotOptions: {
            bar: {
              columnWidth: '10%',
            }
          },
        },
      }
    }
  },

  computed: {
    series() {
      return [
        { data: [this.skor], name: 'Nilai' }
      ]
    }
  }
}
</script>

<template>
  <b-card>
    <div class="border shadow bg-light mb-4 text-center px-2" style="border-radius:10px;">
      <h1 class="text-primary"><b>Selamat</b> <BIconAward /></h1>
      <h6 class="mt-n2">{{ satuanKerjaNama }} meraih</h6>
      <h4 class="text-success"><b>PREDIKAT SAKIP {{ predikat[0] }} ({{ predikat[1] }})</b></h4>
    </div>

    <apexchart type="bar" height="350" :options="chart.options" :series="series"></apexchart>
    <ul class="d-flex flex-wrap justify-content-center">
      <li class="mx-3"><b>AA</b> Sangat Memuaskan</li>
      <li class="mx-3"><b>A</b> Memuaskan</li>
      <li class="mx-3"><b>BB</b> Sangat Baik</li>
      <li class="mx-3"><b>B</b> Baik</li>
      <li class="mx-3"><b>CC</b> Cukup (Memadai)</li>
      <li class="mx-3"><b>C</b> Kurang</li>
      <li class="mx-3"><b>D</b> Sangat Kurang</li>
    </ul>
  </b-card>
</template>