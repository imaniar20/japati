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
  watch: {
    'filter.tahun_kinerja'(val) {
      this.$helper.setTahunKinerjaPublic(val)
      this.getData()
    },
  },
  mounted() {
    this.getData()
  },
  methods: {
    async getData() {
      this.isBusy.getData = true

      const { data: { data }} = await axios.get('/public-display/display-makro/capaian-kinerja-efisiensi-anggaran', {
        params: this.filter
      })

      this.data = data
      this.isBusy.getData = false
    },
    calculateEfisiensiAnggaran(pagu, realisasi) {
      const nominal = pagu - realisasi
      const persen = Math.round(((100 - (realisasi / pagu * 100)) + Number.EPSILON) * 100) / 100

      return {
        nominal,
        persen,
      }
    }
  }
}
</script>

<template>
  <b-card>
    <b-row>
      <b-col sm="6" md="4">
        <FilterTahunKinerja v-model="filter.tahun_kinerja" />
      </b-col>
    </b-row>
    <b-table-simple :aria-busy="isBusy.getData" hover responsive bordered striped sticky-header="calc(100vh - 100px)">
      <b-thead class="text-center align-middle" head-variant="info">
        <b-tr>
          <b-th class="align-middle" rowspan="2">No</b-th>
          <b-th class="align-middle" rowspan="2">Sasaran</b-th>
          <b-th class="align-middle" rowspan="2">Indikator</b-th>
          <b-th class="align-middle" rowspan="2">Target</b-th>
          <b-th class="align-middle" rowspan="2">Realisasi</b-th>
          <b-th class="align-middle" rowspan="2">% Capaian Kinerja</b-th>
          <b-th class="align-middle" colspan="5">Anggaran</b-th>
        </b-tr>
        <b-tr>
          <b-th style="top:49px;">Target</b-th>
          <b-th style="top:49px;">Realisasi</b-th>
          <b-th style="top:49px;">% Penyerapan Anggaran</b-th>
          <b-th style="top:49px;">Nominal Efisiensi</b-th>
          <b-th style="top:49px;">% Efisiensi</b-th>
        </b-tr>
      </b-thead>
      <b-tbody>
        <b-tr class="text-center" v-if="!data.length">
          <b-td colspan="11">There are no records to show</b-td>
        </b-tr>
        <template v-else v-for="(bySasaran, sasaranIndex) of data">
          <tr v-for="(item, index) of bySasaran" :key="item.id">
            <b-td v-if="index == 0" :rowspan="bySasaran.flat(1).length">{{ sasaranIndex + 1 }}</b-td>
            <b-td v-if="index == 0" :rowspan="bySasaran.flat(1).length">{{ item.sasaran_strategis.sasaran }}</b-td>
            <b-td>{{ item.indikator_sasaran_strategis.indikator }}</b-td>

            <!-- tahun saat ini -->
            <b-td class="text-center">{{ item[$helper.getKeyTahunDinamisPublic('target', filter.tahun_kinerja)] }}</b-td>
            <b-td class="text-center">{{ item[$helper.getKeyTahunDinamisPublic('realisasi', filter.tahun_kinerja)] || '-' }}</b-td>
            <b-td class="text-center">{{ item[$helper.getKeyTahunDinamisPublic('capaian', filter.tahun_kinerja)] || '-' }}</b-td>
            <b-td class="text-center">{{ item.target_anggaran_program | rupiah }}</b-td>
            <b-td class="text-center">{{ item.realisasi_anggaran_program | rupiah }}</b-td>
            <b-td class="text-center">{{ Math.round((item.realisasi_anggaran_program / item.target_anggaran_program * 100 + Number.EPSILON) * 100 ) / 100 || '-' }}</b-td>
            <!-- <b-td class="text-center">{{ item.target_anggaran_program - item.realisasi_anggaran_program | rupiah }}</b-td> -->
            <b-td class="text-center">{{ calculateEfisiensiAnggaran(item.target_anggaran_program, item.realisasi_anggaran_program).nominal | rupiah }}</b-td>
            <b-td class="text-center">
              <span v-if="item[$helper.getKeyTahunDinamisPublic('capaian', filter.tahun_kinerja)] != '-' && item[$helper.getKeyTahunDinamisPublic('capaian', filter.tahun_kinerja)] >= 100">{{ calculateEfisiensiAnggaran(item.target_anggaran_program, item.realisasi_anggaran_program).persen }}</span>
              <span v-else>-</span>
            </b-td>
          </tr>
        </template>
      </b-tbody>
    </b-table-simple>
  </b-card>
</template>
