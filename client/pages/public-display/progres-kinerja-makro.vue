<script>
import axios from 'axios'

export default {
  layout: 'guest',

  data() {
    return {
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
      isBusy: {
        getData: false,
      },
    }
  },

  mounted() {
    this.getData()
  },

  methods: {
    async getData() {
      this.isBusy.getData = true

      const { data } = await axios.get('/public-display/progres-kinerja-makro')

      this.data = data
      this.isBusy.getData = false

      if (this.data.length) {
        this.chartOptions.xaxis.categories = [
          String(this.data[0].tahun_mulai),
          String(this.data[0].tahun_mulai + 1),
          String(this.data[0].tahun_mulai + 2),
          String(this.data[0].tahun_mulai + 3),
          String(this.data[0].tahun_mulai + 4)
        ]

        this.series = this.data.map(item => {
          return {
            name: item.indikator,
            data: [
              item.capaian_1 || 0,
              item.capaian_2 || 0,
              item.capaian_3 || 0,
              item.capaian_4 || 0,
              item.capaian_5 || 0
            ]
          }
        })
      }
    }
  }
}
</script>

<template>
  <div class="px-4 py-4">
    <b-card>
      <b-table-simple :aria-busy="isBusy.getData" hover responsive bordered striped sticky-header="calc(100vh - 100px)">
        <b-thead class="text-center align-middle" head-variant="info">
          <b-tr>
            <b-th class="align-middle">Indikator Tujuan</b-th>
            <b-th class="align-middle">Target</b-th>
            <b-th class="align-middle">Realisasi</b-th>
            <b-th class="align-middle">Capaian</b-th>
          </b-tr>
        </b-thead>
        <b-tbody>
          <tr v-for="(item, index) of data" :key="index">
            <td>{{ item.indikator_nomor }}. {{ item.indikator }}</td>
            <td>
              <div class="text-nowrap">
                {{ item.tahun_mulai }}: <b>{{ item.target_1 }}</b> <br>
                {{ item.tahun_mulai + 1 }}: <b>{{ item.target_2 }}</b> <br>
                {{ item.tahun_mulai + 2 }}: <b>{{ item.target_3 }}</b> <br>
                {{ item.tahun_mulai + 3 }}: <b>{{ item.target_4 }}</b> <br>
                {{ item.tahun_mulai + 4 }}: <b>{{ item.target_5 }}</b>
              </div>
            </td>
            <td>
              <div class="text-nowrap">
                {{ item.tahun_mulai }}: <b>{{ item.realisasi_1 }}</b> <br>
                {{ item.tahun_mulai + 1 }}: <b>{{ item.realisasi_2 }}</b> <br>
                {{ item.tahun_mulai + 2 }}: <b>{{ item.realisasi_3 }}</b> <br>
                {{ item.tahun_mulai + 3 }}: <b>{{ item.realisasi_4 }}</b> <br>
                {{ item.tahun_mulai + 4 }}: <b>{{ item.realisasi_5 }}</b>
              </div>
            </td>
            <td>
              <div class="text-nowrap">
                {{ item.tahun_mulai }}: <b>{{ item.capaian_1 || '-' }}%</b> <br>
                {{ item.tahun_mulai + 1 }}: <b>{{ item.capaian_2 || '-' }}%</b> <br>
                {{ item.tahun_mulai + 2 }}: <b>{{ item.capaian_3 || '-' }}%</b> <br>
                {{ item.tahun_mulai + 3 }}: <b>{{ item.capaian_4 || '-' }}%</b> <br>
                {{ item.tahun_mulai + 4 }}: <b>{{ item.capaian_5 || '-' }}%</b>
              </div>
            </td>
          </tr>
        </b-tbody>
      </b-table-simple>
    </b-card>
    <b-card class="mt-4">
      <h5 class="subtitle text-black text-center">Diagram Capaian</h5>
      <apexchart v-if="series.length" height="430" type="bar" :options="chartOptions" :series="series"></apexchart>
      <div v-else class="text-center">Tidak ada data</div>
    </b-card>
  </div>
</template>