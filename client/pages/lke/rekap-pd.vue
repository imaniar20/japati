<script>
import axios from 'axios'

export default {
  middleware({ $role }) {
    if (!$role.isSuper() && !$role.isValidatorLKE()) {
      return $nuxt.error({ statusCode: 403, message: 'Anda tidak memiliki hak akses ke halaman ini' })
    }
  },
  
  async asyncData() {
    const { data } = await axios.get('lke/penilaian/rekap')

    const series = data.map(_ => {
      return {
        data: [Number(_.penilaian_komponen_sum_nilai)],
        name: _.satuan_kerja.satuan_kerja_nama_alias
      }
    })

    return {
      series,
      chart: {
        options: {
          chart: {
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
          colors: [
              '#006400', '#006900', '#006E00', '#007300', '#007800', '#007D00', '#008200',
              '#008700', '#008C00', '#009100', '#009600', '#009B00', '#00A000', '#00A500',
              '#00AA00', '#00AF00', '#00B400', '#00B900', '#00BE00', '#00C300', '#00C800',
              '#00CD00', '#00D200', '#00D700', '#00DC00', '#00E100', '#00E600', '#00EB00',
              '#00F000', '#00F500', '#00FA00', '#00FF00', '#05FF00', '#0AFF00', '#0FFF00',
              '#14FF00', '#19FF00', '#1EFF00', '#23FF00', '#28FF00', '#2DFF00', '#32FF00',
              '#37FF00', '#3CFF00', '#41FF00', '#46FF00', '#4BFF00', '#50FF00', '#55FF00',
              '#5AFF00'
          ],
          stroke: {
            show: true,
            curve: 'smooth',
            lineCap: 'butt',
            colors: ['#FFD700'],
            width: 1,
            dashArray: 0, 
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
              columnWidth: '98%',
            }
          },
        },
      }
    }
  }
}
</script>

<template>
  <b-card>
    <h5 class="text-center">Rekapitulasi Nilai SAKIP Perangkat Daerah <br>Tahun {{ $helper.getTahunKinerja() }}</h5>

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