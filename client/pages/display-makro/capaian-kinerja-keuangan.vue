<script>
import axios from 'axios';

export default {
  middleware: ['auth'],

  async asyncData() {
    const { data } = await axios.get('/display-makro/capaian-kinerja-keuangan')

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

      const { data } = await axios.get('/display-makro/capaian-kinerja-keuangan', {
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

    <b-table-simple sticky-header="calc(100vh - 150px)" :aria-busy="isBusy" hover responsive bordered striped>
      <b-thead class="text-center align-middle" head-variant="info">
        <b-tr>
          <b-th>No</b-th>
          <b-th v-if="$role.isSuper() || $role.isSetda()">Satuan Kerja</b-th>
          <b-th>Sasaran</b-th>
          <b-th>Indikator</b-th>
          <b-th>% Capaian Kinerja</b-th>
          <b-th>Program</b-th>
          <b-th>Realisasi Anggaran</b-th>
          <b-th>Kegiatan</b-th>
          <b-th>Realisasi Anggaran</b-th>
          <b-th>Sub Kegiatan</b-th>
          <b-th>Realisasi Anggaran</b-th>
          <b-th>Aktivitas</b-th>
          <b-th>Realisasi Anggaran</b-th>
        </b-tr>
        <b-tr>
          <b-th style="top:85px" v-for="n of $role.isSuper() || $role.isSetda() ? 13 : 12" :key="n">{{ n }}</b-th>
        </b-tr>
      </b-thead>
      <b-tbody>
        <b-tr class="text-center" v-if="!data.data.length">
          <b-td :colspan="$role.isSuper() || $role.isSetda() ? 13 : 12">There are no records to show</b-td>
        </b-tr>
        <b-tr v-else v-for="(item, index) of data.data" :key="item.id">
          <td>{{ data.from + index }}</td>
          <td v-if="$role.isSuper() || $role.isSetda()">{{ item.satuan_kerja.satuan_kerja_nama }}</td>
          <b-td>
            {{ item.sasaran_strategis_rpjmd ? item.sasaran_strategis_rpjmd.sasaran_strategis.sasaran : '-' }}
          </b-td>
          <b-td>
            {{ item.sasaran_strategis_rpjmd ? item.sasaran_strategis_rpjmd.indikator_sasaran_strategis.indikator : '-' }}
          </b-td>
          <b-td class="text-center">
            {{ item.sasaran_strategis_rpjmd ? item.sasaran_strategis_rpjmd[$helper.getKeyTahun('capaian')] || '-' : '-' }}
          </b-td>
          <b-td>
            {{ item.kinerja_program ? item.kinerja_program.program.nama : '-' }}
          </b-td>
          <b-td>
            <span v-if="item.kinerja_program">{{ item.kinerja_program.realisasi_anggaran | rupiah }}</span>
            <span v-else>-</span>
          </b-td>
          <b-td>
            {{ item.kinerja_kegiatan?.kegiatan?.nama || '-' }}
          </b-td>
          <b-td>
            <span v-if="item.kinerja_kegiatan">{{ item.kinerja_kegiatan.realisasi_anggaran | rupiah }}</span>
            <span v-else>-</span>
          </b-td>
          <b-td>{{ item.sub_kegiatan?.nama || '-' }}</b-td>
          <b-td>
            <span v-if="item.kinerja_kegiatan">{{ item.kinerja_kegiatan.realisasi_anggaran | rupiah }}</span>
            <span v-else>-</span>
          </b-td>
          <b-td>{{ item.sub_kegiatan?.nama || '-' }}</b-td>
          <b-td>{{ item.realisasi_anggaran | rupiah }}</b-td>
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