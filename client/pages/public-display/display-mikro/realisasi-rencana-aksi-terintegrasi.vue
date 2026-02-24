<script>
import axios from 'axios';

export default {
  layout: 'guest',

  data() {
    return {
      data: {
        data: [],
        from: 1,
        current_page: 1,
        total: 0,
        per_page: 20,
      },
      filter: {
        satuan_kerja_id: null,
      },
      isBusy: {
        doFilter: false,
      },

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

  mounted() {
    this.doFilter()
  },

  methods: {
    async doFilter(page = 1) {
      this.isBusy.doFilter = true

      const { data } = await axios.get('/public-display/display-mikro/rencana-aksi-terintegrasi', {
        params: {
          filter: this.filter,
          page,
        }
      })

      this.data = data
      this.isBusy.doFilter = false
    },
  },
}
</script>

<template>
  <b-card>
    <div>
      <b-row>
        <b-col sm="6" md="4">
          <FilterSatuanKerja v-model="filter.satuan_kerja_id" />
        </b-col>
      </b-row>
    </div>

    <b-table-simple sticky-header="calc(100vh - 200px)" :aria-busy="isBusy.doFilter" hover responsive bordered striped>
      <b-thead class="text-center align-middle" head-variant="info">
        <b-tr>
          <b-th class="align-middle" rowspan="2">No</b-th>
          <b-th class="align-middle" rowspan="2">Satuan Kerja</b-th>
          <b-th class="align-middle" rowspan="2">Program</b-th>
          <b-th class="align-middle" rowspan="2">Kegiatan</b-th>
          <b-th class="align-middle" rowspan="2">Sub Kegiatan</b-th>
          <b-th class="align-middle" rowspan="2">Sasaran Sub Kegiatan</b-th>
          <b-th class="align-middle" rowspan="2">Langkah Aksi</b-th>
          <b-th class="align-middle" colspan="12">Realisasi Bulan Ke</b-th>
        </b-tr>
        <b-tr>
          <b-th style="top:45px" v-for="n of 12" :key="n">{{ n }}</b-th>
        </b-tr>
      </b-thead>
      <b-tbody>
        <b-tr class="text-center" v-if="!data.data.length">
          <b-td colspan="20">There are no records to show</b-td>
        </b-tr>
        <b-tr v-else v-for="(item, index) of data.data" :key="index">
          <td>{{ data.from + index }}</td>
          <td>{{ item.satuan_kerja }}</td>
          <b-td>{{ item.program }}</b-td>
          <b-td>{{ item.kegiatan }}</b-td>
          <b-td>{{ item.sub_kegiatan }}</b-td>
          <b-td>{{ item.sasaran_sub_kegiatan }}</b-td>
          <b-td>{{ item.indikator_langkah_aksi }}</b-td>

          <b-td v-for="n in 12" :key="n">
            {{ item.data[n] && item.data[n].realisasi ? `${item.data[n].realisasi} ${item.data[n].satuan}` : '-' }}
          </b-td>
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
          <i class="fa fa-spinner fa-pulse fa-fw" v-if="isBusy.doFilter && active"></i>
          <template v-else>{{ page }}</template>
        </template>
      </b-pagination>
    </div>
  </b-card>
</template>
