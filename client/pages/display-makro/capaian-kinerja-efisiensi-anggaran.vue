<script>
import axios from 'axios'

export default {
  middleware: ['auth'],

  async asyncData() {
    const { data } = await axios.get('/display-makro/capaian-kinerja-efisiensi-anggaran')

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

      const { data } = await axios.get('/display-makro/capaian-kinerja-efisiensi-anggaran', {
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

    <b-table-simple  sticky-header="calc(100vh - 150px)" :aria-busy="isBusy" hover responsive bordered striped>
      <b-thead class="text-center align-middle" head-variant="info">
        <b-tr>
          <b-th class="align-middle" rowspan="2">No</b-th>
          <b-th v-if="$role.isSuper() || $role.isSetda()" rowspan="2" class="align-middle">Satuan Kerja</b-th>
          <b-th class="align-middle" rowspan="2">Sasaran</b-th>
          <b-th class="align-middle" rowspan="2">Indikator</b-th>
          <b-th class="align-middle" rowspan="2">Target</b-th>
          <b-th class="align-middle" rowspan="2">Realisasi</b-th>
          <b-th class="align-middle" rowspan="2">% Capaian Kinerja</b-th>
          <b-th class="align-middle" colspan="4">Anggaran</b-th>
        </b-tr>
        <b-tr>
          <b-th style="top:49px;">Target</b-th>
          <b-th style="top:49px;">Realisasi</b-th>
          <b-th style="top:49px;">% Penyerapan Anggaran</b-th>
          <b-th style="top:49px;">Efisiensi</b-th>
        </b-tr>
      </b-thead>
      <b-tbody>
        <b-tr class="text-center" v-if="!data.data.length">
          <b-td colspan="24">There are no records to show</b-td>
        </b-tr>
        <b-tr v-else v-for="(item, index) of data.data" :key="item.id">
          <td>{{ data.from + index }}</td>
          <td v-if="$role.isSuper() || $role.isSetda()">{{ item.satuan_kerja.satuan_kerja_nama }}</td>
          <b-td>{{ item.sasaran_strategis.sasaran }}</b-td>
          <b-td>{{ item.indikator_sasaran_strategis.indikator }}</b-td>

          <!-- tahun saat ini -->
          <b-td class="text-center">{{ item[$helper.getKeyTahun('target')] }}</b-td>
          <b-td class="text-center">{{ item[$helper.getKeyTahun('realisasi')] || '-' }}</b-td>
          <b-td class="text-center">{{ item[$helper.getKeyTahun('capaian')] || '-' }}</b-td>
          <b-td class="text-center">{{ item.target_anggaran_program | rupiah }}</b-td>
          <b-td class="text-center">{{ item.realisasi_anggaran_program | rupiah }}</b-td>
          <b-td class="text-center">{{ Math.round((item.realisasi_anggaran_program / item.target_anggaran_program * 100 + Number.EPSILON) * 100 ) / 100 || '-' }}</b-td>
          <!-- <b-td class="text-center">{{ item.target_anggaran_program - item.realisasi_anggaran_program | rupiah }}</b-td> -->
          <b-td class="text-center">
            <span v-if="item[$helper.getKeyTahun('capaian')] != '-' && item[$helper.getKeyTahun('capaian')] >= 100">{{ item.target_anggaran_program - item.realisasi_anggaran_program | rupiah }}</span>
            <span v-else>-</span>
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
          <i class="fa fa-spinner fa-pulse fa-fw" v-if="isBusy && active"></i>
          <template v-else>{{ page }}</template>
        </template>
      </b-pagination>
    </div>
  </b-card>
</template>
