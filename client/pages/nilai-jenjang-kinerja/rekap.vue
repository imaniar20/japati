<script>
import axios from 'axios'

export default {
  layout ({ store }) {
    return store.state.auth.user ? 'default' : 'guest'
  },

  async asyncData() {
    const { data } = await axios.get('nilai-jenjang-kinerja/rekap')

    return {
      data,
    }
  }
}
</script>

<template>
  <b-card>
    <b-table-simple responsive bordered striped hover>
      <b-thead class="text-center align-middle" head-variant="info">
        <b-tr>
          <b-th>No</b-th>
          <b-th>Satuan Kerja</b-th>
          <b-th>Nilai Akhir</b-th>
        </b-tr>
      </b-thead>
      <b-tbody>
        <b-tr v-for="(item, index) of data" :key="index">
          <b-th>{{ index + 1 }}</b-th>
          <b-td>{{ item.satuan_kerja_nama }}</b-td>
          <b-td>{{ item.nilai_akhir }}</b-td>
        </b-tr>
      </b-tbody>
    </b-table-simple>
  </b-card>
</template>