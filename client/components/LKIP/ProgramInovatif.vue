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
      data: {
        data: [],
      },
      isBusy: false,
    }
  },

  mounted() {
    this.getData()
  },

  methods: {
    async getData(page = 1) {
      this.isBusy = true

      const { data } = await axios.get('/lkip/program-inovatif', {
        params: {
          page,
          satuan_kerja_id: this.satuanKerjaId,
        }
      })

      this.data = data
      this.isBusy = false
    },
  },
}
</script>

<template>
  <b-card>
    <b-table-simple :aria-busy="isBusy" hover responsive bordered striped>
      <b-thead class="text-center align-middle" head-variant="info">
        <b-tr>
          <b-th>No</b-th>
          <b-th>Sasaran</b-th>
          <b-th>Indikator</b-th>
          <b-th><span style="min-width:120px;display:inline-block;">% Capaian Kinerja</span></b-th>
          <b-th><span style="min-width:275px;display:inline-block;">Program/Kegiatan/Langkah Aksi Inovatif</span></b-th>
        </b-tr>
      </b-thead>
      <b-tbody>
        <b-tr class="text-center" v-if="isBusy && !data.data.length">
          <b-td colspan="5"><b-spinner small></b-spinner> Sedang memuat data...</b-td>
        </b-tr>
        <b-tr class="text-center" v-else-if="!data.data.length">
          <b-td colspan="5">There are no records to show</b-td>
        </b-tr>
        <b-tr v-else v-for="(item, index) of data.data" :key="item.id">
          <td>{{ data.from + index }}</td>
          <b-td>{{ item.sasaran_strategis_rpjmd ? item.sasaran_strategis_rpjmd.sasaran_strategis.sasaran : '-' }}</b-td>
          <b-td>{{ item.sasaran_strategis_rpjmd ? item.sasaran_strategis_rpjmd.indikator_sasaran_strategis.indikator : '-' }}</b-td>
          <b-td class="text-center">{{ item.sasaran_strategis_rpjmd ? item.sasaran_strategis_rpjmd[$helper.getKeyTahun('capaian')] || '-' : '-' }}</b-td>
          <b-td>{{ item.inovasi_uraian }}</b-td>
        </b-tr>
      </b-tbody>
    </b-table-simple>

    <div>
      <b-pagination
        v-model="data.current_page"
        :total-rows="data.total"
        :per-page="data.per_page"
        @change="getData($event)"
      >
        <template #page="{ page, active }">
          <i class="fa fa-spinner fa-pulse fa-fw" v-if="isBusy && active"></i>
          <template v-else>{{ page }}</template>
        </template>
      </b-pagination>
    </div>
  </b-card>
</template>
