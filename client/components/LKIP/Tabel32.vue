<script>
import axios from 'axios'

export default {
  props: {
    satuanKerjaId: {
      required: true,
    }
  },

  data() {
    return {
      isBusy: false,
      data: [],
    }
  },

  mounted() {
    this.getData()
  },

  methods: {
    async getData() {
      this.isBusy = true

      const { data } = await axios.get('/lkip/tabel32', {
        params: {
          satuan_kerja_id: this.satuanKerjaId,
        }
      })

      this.data = data
      this.isBusy = false
    }
  }
}
</script>

<template>
  <b-table-simple class="mt-3" :aria-busy="isBusy" responsive bordered striped hover>
    <b-thead class="text-center align-middle" head-variant="info">
      <b-tr>
        <b-th rowspan="2">NO.</b-th>
        <b-th rowspan="2">SASARAN</b-th>
        <b-th rowspan="2">CAPAIAN KINERJA</b-th>
        <b-th rowspan="2">PAGU ANGGARAN</b-th>
        <b-th rowspan="2">REALISASI ANGGARAN</b-th>
        <b-th rowspan="2">CAPAIAN (%)</b-th>
        <b-th colspan="2">EFISIENSI</b-th>
      </b-tr>
      <b-tr>
        <b-th>ANGGARAN</b-th>
        <b-th>(%)</b-th>
      </b-tr>
    </b-thead>
    <b-tbody>
      <b-tr v-if="isBusy">
        <b-td colspan="8" class="text-center"><b-spinner /></b-td>
      </b-tr>
      <template v-else v-for="(bySasaran, sasaranIndex) of data">
        <tr v-for="(val, index) of bySasaran.sasaran_strategis_pd" :key="val.id">
          <td v-if="index == 0" :rowspan="bySasaran.sasaran_strategis_pd.flat(1).length" class="text-center">{{ sasaranIndex + 1 }}</td>
          <td v-if="index == 0" :rowspan="bySasaran.sasaran_strategis_pd.flat(1).length">{{ bySasaran.sasaran }}</td>
          <td>{{ val[$helper.getKeyTahun('capaian')] || '-' }}</td>
          <td>{{ val.pagu_anggaran | rupiah }}</td>
          <td>{{ val.realisasi_anggaran | rupiah }}</td>
          <td>
            <span v-if="val.capaian_anggaran">{{ val.capaian_anggaran }}</span>
            <span v-else>-</span>
          </td>
          <td>
            <span v-if="val.efisiensi_nominal">{{ val.efisiensi_nominal | rupiah }}</span>
            <span v-else>-</span>
          </td>
          <td>
            <span v-if="val.efisiensi_persen">{{ val.efisiensi_persen }}</span>
            <span v-else>-</span>
          </td>
        </tr>
      </template>
    </b-tbody>
  </b-table-simple>
</template>
