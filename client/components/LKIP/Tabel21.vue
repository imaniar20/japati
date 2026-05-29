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

      const { data } = await axios.get('/lkip/tabel21', {
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
  <div>
    <b-table-simple class="mt-3" :aria-busy="isBusy" responsive bordered striped>
      <b-thead  class="text-center align-middle" head-variant="info">
        <b-tr>
          <b-th>TUJUAN</b-th>
          <b-th>SASARAN</b-th>
          <b-th>INDIKATOR (IKU BUPATI)</b-th>
          <b-th>TARGET</b-th>
        </b-tr>
      </b-thead>
      <b-tbody>
        <b-tr v-if="isBusy && !data.length">
          <b-td colspan="4" class="text-center"><b-spinner /></b-td>
        </b-tr>
        <template v-else v-for="byTujuan of data">
          <tr v-for="(val, index) of byTujuan" :key="val.id">
            <td v-if="index == 0" :rowspan="byTujuan.flat(1).length">{{ val.tujuan.tujuan }}</td>
            <td>{{ val.sasaran_strategis.sasaran }}</td>
            <td>{{ val.indikator_sasaran_strategis.indikator }}</td>
            <td>{{ val[$helper.getKeyTahun('target')] }}</td>
          </tr>
        </template>
      </b-tbody>
    </b-table-simple>
  </div>
</template>
