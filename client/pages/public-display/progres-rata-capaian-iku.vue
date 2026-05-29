<script>
import axios from 'axios'
import Vue from 'vue';

export default {
  layout: 'guest',

  data() {
    return {
      tahunKinerja: this.$helper.getTahunKinerjaPublic(),
      data: [],
      chartOptions: {
        chart: {
          type: 'bar',
          height: 430,
          toolbar: {
            show: false
          }
        },
        plotOptions: {
          bar: {
            borderRadius: 6,
            borderRadiusApplication: 'around',
            dataLabels: {
              position: 'top',
            },
          }
        },
        dataLabels: {
          enabled: false,
          style: {
            fontSize: '12px',
            colors: ['#fff']
          }
        },
        stroke: {
          show: true,
          width: 2,
          colors: ['#fff']
        },
        tooltip: {
          shared: true,
          intersect: false,
          theme: 'dark'
        },
        grid: {
          show: false,
        },
        xaxis: {
          categories: [],
          axisBorder: {
            show: false
          },
          axisTicks: {
            show: false
          }
        },
        yaxis: {
          show: true,
          tickAmount: 10,
        },
        legend: {
          markers: {
            shape: 'circle'
          }
        }
      },
      series: [],
      chartDonut: {
        chart: {
          height: 400,
          type: 'donut',
          sparkline: {
            enabled: true
          }
        },
        colors: ['#E5E7EB', '#1C64F2', '#00809D'],
        plotOptions: {
          pie: {
            inverseOrder: false,
            donut: {
              size: '60%',
              total: {
                // show: true,
                // showAlways: true,
              },
              labels: {
                show: true,
                value: {
                  show: true,
                  formatter: (value) => Vue.filter('rupiah')(value),
                },
                // total: {
                //   show: true,
                //   formatter: (w) => {
                //     const total = w.globals.seriesTotals.reduce((a, b) => a + b, 0)

                //     return Vue.filter('rupiah')(total)
                //   }
                // }
              }
            },
          }
        },
        tooltip: {
          enabled: true,
          fillSeriesColor: true,
          theme: 'dark',
          y: {
            formatter: function(value) {
              return Vue.filter('rupiah')(value)
            }
          }
        },
        legend: {
          show: true,
          position: 'bottom',
        },
        labels: ['Realisasi', 'Efisinesi'],
        dataLabels: {
          enabled: true,
        }
      },
      seriesDonut: [0, 0,0],
      isBusy: {
        getData: false,
      },
      capaianSummary: '',
    }
  },

  mounted() {
    this.getData()
  },

  methods: {
    async getData() {
      this.isBusy.getData = true

      const { data } = await axios.get('/public-display/progres-rata-capaian-iku', {
        params: this.filter
      })

      this.data = data.iku
      this.isBusy.getData = false

      if (this.data.length) {
        this.chartOptions.xaxis.categories = [
          String(this.tahunKinerja - 1),
          String(this.tahunKinerja),
        ]

        this.series = this.data.map(item => {
          return {
            name: item.indikator_sasaran_strategis.indikator,
            data: [
              item[this.$helper.getKeyTahunPublic('capaian', -1)] || 0,
        //      item[this.$helper.getKeyTahunPublic('capaian')] || 0,
            ]
          }
        })

        const summary = {
          1: [],
          // 2: [],
          // 3: [],
          // 4: [],
          // 5: []
        }

        this.data.forEach(item => {
          summary[1].push(item.capaian_1 || 0)
          // summary[2].push(item.capaian_2 || 0)
          // summary[3].push(item.capaian_3 || 0)
          // summary[4].push(item.capaian_4 || 0)
          // summary[5].push(item.capaian_5 || 0)
        });

        const tahunMulai = this.$helper.getTahunMulaiPublic()

        const calculateAverage = (arr) => {
          const total = arr.reduce((acc, val) => acc + val, 0);
          return ((total / arr.length) || 0).toFixed(2);
        }
        
        // this.capaianSummary = `${tahunMulai}: ${calculateAverage(summary[1])}%,
        //   ${tahunMulai + 1}: ${calculateAverage(summary[2])}%,
        //   ${tahunMulai + 2}: ${calculateAverage(summary[3])}%,
        //   ${tahunMulai + 3}: ${calculateAverage(summary[4])}%,
        //   ${tahunMulai + 4}: ${calculateAverage(summary[5])}%`

          this.capaianSummary = `${tahunMulai}: ${calculateAverage(summary[1])}%`
      }

      if (data.anggaran) {
        this.seriesDonut = [
          data.anggaran.tidak_terpakai,
          data.anggaran.efisiensi
        ]
      }
    }
  }
}
</script>

<template>
  <div class="px-4 py-4">
    <b-card>
      <b-table-simple
        :aria-busy="isBusy.getData"
        hover
        responsive
        bordered
        striped
        sticky-header="calc(100vh - 100px)"
        class="mt-4"
      >
        <b-thead class="text-center align-middle" head-variant="info">
          <b-tr>
            <b-th class="align-middle">Indikator Sasaran Strategis</b-th>
            <b-th class="align-middle">Target</b-th>
            <b-th class="align-middle">Realisasi</b-th>
            <b-th class="align-middle">Capaian</b-th>
          </b-tr>
        </b-thead>
        <b-tbody>
          <tr v-for="item of data" :key="item.id">
            <td>
              {{ item.indikator_sasaran_strategis.nomor }}.
              {{ item.indikator_sasaran_strategis.indikator }}
            </td>
            <td>
              <div class="text-nowrap">
                {{ item.tahun_mulai }}: <b>{{ item.target_1 }}</b> <br />
                {{ item.tahun_mulai + 1 }}: <b>{{ item.target_2 }}</b> <br />
                {{ item.tahun_mulai + 2 }}: <b>{{ item.target_3 }}</b> <br />
                {{ item.tahun_mulai + 3 }}: <b>{{ item.target_4 }}</b> <br />
                {{ item.tahun_mulai + 4 }}: <b>{{ item.target_5 }}</b>
              </div>
            </td>
            <td>
              <div class="text-nowrap">
                {{ item.tahun_mulai }}: <b>{{ item.realisasi_1 }}</b> <br />
                {{ item.tahun_mulai + 1 }}: <b>{{ item.realisasi_2 }}</b> <br />
                {{ item.tahun_mulai + 2 }}: <b>{{ item.realisasi_3 }}</b> <br />
                {{ item.tahun_mulai + 3 }}: <b>{{ item.realisasi_4 }}</b> <br />
                {{ item.tahun_mulai + 4 }}: <b>{{ item.realisasi_5 }}</b>
              </div>
            </td>
            <td>
              <div class="text-nowrap">
                {{ item.tahun_mulai }}: <b>{{ item.capaian_1 || "-" }}%</b>
                <br />
                {{ item.tahun_mulai + 1 }}: <b>{{ item.capaian_2 || "-" }}%</b>
                <br />
                {{ item.tahun_mulai + 2 }}: <b>{{ item.capaian_3 || "-" }}%</b>
                <br />
                {{ item.tahun_mulai + 3 }}: <b>{{ item.capaian_4 || "-" }}%</b>
                <br />
                {{ item.tahun_mulai + 4 }}: <b>{{ item.capaian_5 || "-" }}%</b>
              </div>
            </td>
          </tr>
        </b-tbody>
      </b-table-simple>
    </b-card>

    <b-card class="mt-4">
      <b-row>
        <b-col md="4">
          <div class="d-flex flex-column">
            <h5 class="subtitle text-black text-center">
              Efisiensi Anggaran untuk capaian IKU Bupati
            </h5>
            <b-card style="padding: 24px 0">
              <apexchart
                v-if="seriesDonut.length"
                height="400"
                type="donut"
                :options="chartDonut"
                :series="seriesDonut"
              ></apexchart>
            </b-card>
          </div>
        </b-col>
        <b-col md="8">
          <div class="d-flex flex-column">
            <h5 class="subtitle text-black text-center">
              <span>Rata-Rata Capaian IKU</span>
              <span>{{ capaianSummary }}</span>
            </h5>
            <b-card>
              <apexchart
                v-if="series.length"
                height="430"
                type="bar"
                :options="chartOptions"
                :series="series"
              ></apexchart>
            </b-card>
          </div>
        </b-col>
      </b-row>
    </b-card>
  </div>
</template>