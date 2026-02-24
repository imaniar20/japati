<script>
import axios from 'axios'

export default {
  middleware: ['auth'],

  async asyncData() {
     const { data } = await axios.get('/display-makro/program-inovatif')

    return {
      data,
    }
  },

  data() {
    return {
      filter: {
        satuan_kerja_id: null,
      },
      isBusy: false,
    }
  },

  watch: {
    filter: {
      handler: function () {
        this.doFilter()
      },
      deep: true,
    }
  },

  methods: {
    async doFilter(page = 1) {
      this.isBusy = true

      const { data } = await axios.get('/display-makro/program-inovatif', {
        params: {
          filter: this.filter,
          page,
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
    <div>
      <b-row>
        <b-col sm="6" md="4">
          <FilterSatuanKerja v-if="$role.isSuper() || $role.isSetda()" v-model="filter.satuan_kerja_id" :is-setda="$role.isSetda()" />
        </b-col>
      </b-row>
    </div>

    <b-table-simple sticky-header="calc(100vh - 200px)" :aria-busy="isBusy" hover responsive bordered striped>
      <b-thead class="text-center align-middle" head-variant="info">
        <b-tr>
          <b-th>No</b-th>
          <b-th v-if="$role.isSuper() || $role.isSetda()">Satuan Kerja</b-th>
          <b-th>Sasaran</b-th>
          <b-th>Indikator</b-th>
          <b-th><span style="min-width:120px;display:inline-block;">% Capaian Kinerja</span></b-th>
          <b-th><span style="min-width:275px;display:inline-block;">Program/Kegiatan/Langkah Aksi Inovatif</span></b-th>
        </b-tr>
        <b-tr>
          <b-th style="top:59px" v-for="n of $role.isSuper() || $role.isSetda() ? 6 : 5" :key="n">{{ n }}</b-th>
        </b-tr>
      </b-thead>
      <b-tbody>
        <b-tr class="text-center" v-if="!data.data.length">
          <b-td :colspan="$role.isSuper() || $role.isSetda() ? 6 : 5">There are no records to show</b-td>
        </b-tr>
        <b-tr v-else v-for="(item, index) of data.data" :key="item.id">
          <td>{{ data.from + index }}</td>
          <td v-if="$role.isSuper() || $role.isSetda()">{{ item.satuan_kerja.satuan_kerja_nama }}</td>
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
        @change="doFilter($event)"
      >
        <template #page="{ page, active }">
          <i class="fa fa-spinner fa-pulse fa-fw" v-if="isBusy && active"></i>
          <template v-else>{{ page }}</template>
        </template>
      </b-pagination>
    </div>
  </b-card>
</template>