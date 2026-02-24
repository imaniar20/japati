<template>
  <b-card>
    <h6 class="text-center">Tahun {{ filter.tahun_kinerja - 1 }} - {{ filter.tahun_kinerja }}</h6>
    <b-row>
      <b-col sm="6" md="4">
        <FilterTahunKinerja v-model="filter.tahun_kinerja" />
      </b-col>
    </b-row>
    <b-table-simple :aria-busy="isBusy.getData" sticky-header="calc(100vh - 20px)" striped hover responsive bordered>
      <b-thead class="text-center align-middle" head-variant="info">
        <b-tr>
          <b-th class="align-middle" rowspan="2">No</b-th>
          <b-th class="align-middle" rowspan="2">Sasaran</b-th>
          <b-th class="align-middle" rowspan="2">Indikator</b-th>
          <b-th class="align-middle" colspan="3">Capaian Tahun {{ filter.tahun_kinerja }}</b-th>
          <b-th class="align-middle" colspan="6">Peningakatan dari Tahun Lalu</b-th>
          <b-th class="align-middle" colspan="2">Capaian Tahun {{ filter.tahun_kinerja }} Terhadap Target Akhir RPJMD</b-th>
          <b-th class="align-middle" colspan="3">Perbandingan Dengan Nasional</b-th>
          <b-th class="align-middle" rowspan="2">Raihan Penghargaan</b-th>
        </b-tr>
        <b-tr>
          <b-th class="align-top" style="top:79px;">Target</b-th>
          <b-th class="align-top" style="top:79px;">Realisasi</b-th>
          <b-th class="align-top" style="top:79px;">% Capaian</b-th>
          <b-th class="align-top" style="top:79px;">Realisasi {{ filter.tahun_kinerja }}</b-th>
          <b-th class="align-top" style="top:79px;">Realisasi {{ filter.tahun_kinerja - 1 }}</b-th>
          <b-th class="align-top" style="top:79px;">Perbandingan realisasi dari tahun lalu</b-th>
          <b-th class="align-top" style="top:79px;">Capaian {{ filter.tahun_kinerja }} (%)</b-th>
          <b-th class="align-top" style="top:79px;">Capaian {{ filter.tahun_kinerja - 1 }} (%)</b-th>
          <b-th class="align-top" style="top:79px;">Peningkatan Capaian dari Tahun Lalu (%)</b-th>
          <b-th class="align-top" style="top:79px;">Target akhir RPJMD</b-th>
          <b-th class="align-top" style="top:79px;"><span style="min-width: 125px;display: inline-block;">Realisasi Tahun {{ filter.tahun_kinerja }} Terhadap Target Akhir RJMD (%)</span></b-th>
          <b-th class="align-top" style="top:79px;">Rata-Rata Nasional</b-th>
          <b-th class="align-top" style="top:79px;">Perbandingan Nasional</b-th>
          <b-th class="align-top" style="top:79px;">Peringkat di Tingkat Nasional</b-th>
        </b-tr>
        <b-tr>
          <b-th  style="top:190px;" v-for="n of 6" :key="n">{{ n }}</b-th>
          <b-th  style="top:190px;">7=5</b-th>
          <b-th  style="top:190px;">8</b-th>
          <b-th  style="top:190px;">9</b-th>
          <b-th  style="top:190px;">10=6</b-th>
          <b-th  style="top:190px;">11</b-th>
          <b-th  style="top:190px;">12=(10-11)</b-th>
          <b-th  style="top:190px;">13</b-th>
          <b-th  style="top:190px;">14=(7/13)</b-th>
          <b-th  style="top:190px;">15</b-th>
          <b-th  style="top:190px;">16</b-th>
          <b-th  style="top:190px;">17</b-th>
          <b-th  style="top:190px;">18</b-th>
        </b-tr>
      </b-thead>
      <b-tbody>
        <b-tr class="text-center" v-if="!data.length">
          <b-td colspan="17">There are no records to show</b-td>
        </b-tr>
        <template v-else v-for="(bySasaran, sasaranIndex) of data">
          <tr v-for="(item, index) of bySasaran" :key="item.id">
            <b-td v-if="index == 0" :rowspan="bySasaran.flat(1).length">{{ sasaranIndex + 1 }}</b-td>
            <b-td v-if="index == 0" :rowspan="bySasaran.flat(1).length">{{ item.sasaran_strategis.sasaran }}</b-td>
            <b-td>{{ item.indikator_sasaran_strategis.indikator }}</b-td>

            <b-td class="text-center">{{ item[$helper.getKeyTahunDinamisPublic('target', filter.tahun_kinerja)] }}</b-td>
            <b-td class="text-center">{{ item[$helper.getKeyTahunDinamisPublic('realisasi', filter.tahun_kinerja)] || '-' }}</b-td>
            <b-td class="text-center" :style="{backgroundColor:colorCapaian(item[$helper.getKeyTahunDinamisPublic('capaian', filter.tahun_kinerja)])}">{{ item[$helper.getKeyTahunDinamisPublic('capaian', filter.tahun_kinerja)] || '-' }}</b-td>

            <!-- tahun sekarang -->
            <b-td class="text-center">{{ item[$helper.getKeyTahunDinamisPublic('realisasi', filter.tahun_kinerja)] || '-' }}</b-td>
            <!-- 1 tahun sebelumnya -->
            <b-td class="text-center">{{ item[$helper.getKeyTahunDinamisPublic('realisasi', filter.tahun_kinerja - 1)] }}</b-td>
            <b-td class="text-center">{{ item[$helper.getKeyTahunDinamisPublic('perbandingan_realisasi_tahun', filter.tahun_kinerja)] || '-' }}</b-td>

            <!-- tahun sekarang -->
            <b-td class="text-center"  :style="{backgroundColor:colorCapaian(item[$helper.getKeyTahunDinamisPublic('capaian', filter.tahun_kinerja)])}">{{ item[$helper.getKeyTahunDinamisPublic('capaian', filter.tahun_kinerja)] || '-' }}</b-td>
            <!-- 1 tahun sebelumnya -->
            <b-td class="text-center" :style="{backgroundColor:colorCapaian(item[$helper.getKeyTahunDinamisPublic('capaian', filter.tahun_kinerja - 1)] )}">{{ item[$helper.getKeyTahunDinamisPublic('capaian', filter.tahun_kinerja - 1)] }}</b-td>
            <b-td class="text-center"
              :style="{
                backgroundColor: colorPeningkatan(
                  (item[$helper.getKeyTahunDinamisPublic('capaian', filter.tahun_kinerja)] || 0)
                  - (item[$helper.getKeyTahunDinamisPublic('capaian', filter.tahun_kinerja - 1)] || 0)
                )
              }"
            >
              {{ 
                ((item[$helper.getKeyTahunDinamisPublic('capaian', filter.tahun_kinerja)] || 0) 
                - (item[$helper.getKeyTahunDinamisPublic('capaian', filter.tahun_kinerja - 1)] || 0)) 
                | decimalDigit
              }}
            </b-td>

            <b-td class="text-center">{{ item.target_5 }}</b-td>
            <b-td class="text-center">
              {{ item[$helper.getKeyTahunDinamisPublic('perbandingan_realisasi_target', filter.tahun_kinerja)] || '-' }}
            </b-td>

            <b-td class="text-center">{{ item.rata_nasional || '-' }}</b-td>
            <b-td class="text-center">
              <span v-if="!compareRataNasional(item.rata_nasional, item[$helper.getKeyTahunDinamisPublic('realisasi', filter.tahun_kinerja)], item.indikator_sasaran_strategis_id).text">Belum ada data dari rata-rata nasional atau realisasi</span>
              <span v-else>{{ compareRataNasional(item.rata_nasional, item[$helper.getKeyTahunDinamisPublic('realisasi', filter.tahun_kinerja)], item.indikator_sasaran_strategis_id).text }} <span class="font-weight-bold" :class="compareRataNasional(item.rata_nasional, item[$helper.getKeyTahunDinamisPublic('realisasi', filter.tahun_kinerja)], item.indikator_sasaran_strategis_id).class">{{ compareRataNasional(item.rata_nasional, item[$helper.getKeyTahunDinamisPublic('realisasi', filter.tahun_kinerja)], item.indikator_sasaran_strategis_id).nilai | decimalDigit}}</span> dari <br> Rata-Rata Nasional </span>
            </b-td>
            <b-td class="text-center">{{ item.peringkat_nasional || '-' }}</b-td>

            <b-td class="text-center">{{ item.penghargaan || '-' }}</b-td>
          </tr>
        </template>
      </b-tbody>
    </b-table-simple>
  </b-card>
</template>

<script>
import axios from 'axios'

export default {
  layout: 'guest',
  data() {
    return {
      data: [],
      filter: {
        tahun_kinerja: this.$helper.getTahunKinerjaPublic()
      },
      isBusy: {
        getData: false,
      },
    }
  },
  computed: {
    tahunMulai() {
      return this.$helper.getTahunMulaiByTahunKinerja(this.filter.tahun_kinerja)
    },
  },
  watch: {
    'filter.tahun_kinerja'(val) {
      this.$helper.setTahunKinerjaPublic(val)
    },
    tahunMulai() {
      this.getData()
    }
  },
  mounted() {
    this.getData()
  },
  methods:{
    async getData() {
      this.isBusy.getData = true

      const { data: { data }} = await axios.get('/public-display/display-makro/capaian-kinerja-pemda', {
        params: this.filter
      })

      this.data = data
      this.isBusy.getData = false
    },
    colorCapaian(value){
      if(!value) return 'unset'
      if(value > 90) return 'lightgreen'
      if(value > 80 && value <= 90) return 'aquamarine'
      if(value > 70 && value <= 80) return '#70dcff'
      if(value > 60 && value <= 70) return '#bababa'
      if(value > 50 && value <= 60) return 'yellow'
      if(value > 40 && value <= 50) return 'orange'
      return '#ff8a8a'
    },
    colorPeningkatan(value){
      if(value== 0) return '#bababa'
      if(value > 0) return '#00ff00'
      if(value < 0) return '#ff4747'
      return 'unset'
    },
    compareRataNasional(rataNasional, realisasi, indikatorId) {
      realisasi = Number(realisasi)

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
