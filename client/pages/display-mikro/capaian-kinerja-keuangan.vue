<script>
import axios from 'axios';

export default {
  middleware: ['auth'],

  async asyncData() {
    const { data } = await axios.get('/display-mikro/capaian-kinerja-keuangan')

    return {
      data,
    }
  },

  data() {
    return {
      filter: {
        satuan_kerja_id: null,
      },
      isBusy: {
        doFilter: false,
        exportExcel: false,
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

  methods: {
    async doFilter(page = 1) {
      this.isBusy.doFilter = true

      const { data } = await axios.get('/display-mikro/capaian-kinerja-keuangan', {
        params: {
          filter: this.filter,
          page,
        }
      })

      this.data = data
      this.isBusy.doFilter = false
    },
    async exportExcel() {
      this.isBusy.exportExcel = true

      try {
        const { data } = await axios.get('/display-mikro/capaian-kinerja-keuangan/export', {
          params: {
            filter: this.filter,
          },
          responseType: 'blob'
        })

        const url = window.URL.createObjectURL(new Blob([data]))
        const link = document.createElement('a')

        link.href = url
        link.setAttribute('download', 'Capaian Kinerja dan Keuangan Perangkat Daerah.xlsx')
        document.body.appendChild(link)
        link.click()
      } catch (error) {
        throw error
      } finally {
        this.isBusy.exportExcel = false
      }
    }
  },
}
</script>

<template>
  <b-card>
    <div class="text-right mb-2">
      <b-button variant="success" @click="exportExcel" :disabled="isBusy.exportExcel">
        <b-spinner small v-if="isBusy.exportExcel"></b-spinner>
          <i v-else class="ti-download" aria-hidden="true"></i>
          Export
      </b-button>
    </div>
    <div>
      <b-row>
        <b-col sm="6" md="4">
          <FilterSatuanKerja v-if="$role.isSuper() || $role.isSetda()" v-model="filter.satuan_kerja_id" :is-setda="$role.isSetda()" />
        </b-col>
      </b-row>
    </div>

    <b-table-simple sticky-header="calc(100vh - 200px)" :aria-busy="isBusy.doFilter" hover responsive bordered striped>
      <b-thead class="text-center align-middle" head-variant="info">
        <b-tr>
          <b-th class="align-middle">No</b-th>
          <b-th class="align-middle" v-if="$role.isSuper() || $role.isSetda()">Satuan Kerja</b-th>
          <b-th class="align-middle">Sasaran</b-th>
          <b-th class="align-middle">Indikator</b-th>
          <b-th class="align-middle">% Capaian Kinerja</b-th>
          <b-th class="align-middle">Program</b-th>
          <b-th class="align-middle">Realisasi Anggaran</b-th>
          <b-th class="align-middle">Kegiatan</b-th>
          <b-th class="align-middle">Realisasi Anggaran</b-th>
          <b-th class="align-middle">Sub Kegiatan</b-th>
          <b-th class="align-middle">Realisasi Anggaran</b-th>
          <b-th class="align-middle">Aktivitas</b-th>
          <b-th class="align-middle">Realisasi Anggaran</b-th>
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
          <b-td>{{ item.sasaran_strategis_pd ? item.sasaran_strategis_pd.sasaran_strategis_satker : '-' }}</b-td>
          <b-td>{{ item.sasaran_strategis_pd ? item.sasaran_strategis_pd.iku : '-' }}</b-td>
          <b-td class="text-center">{{ item.sasaran_strategis_pd ? item.sasaran_strategis_pd[$helper.getKeyTahun('capaian')] || '-' : '-' }}</b-td>
          <b-td>{{ item.kinerja_program ? item.kinerja_program.program.nama : '-' }}</b-td>
          <b-td>
            <span v-if="item.kinerja_program">{{ item.kinerja_program.realisasi_anggaran | rupiah }}</span>
            <span v-else>-</span>
          </b-td>
          <b-td>{{ item.kinerja_kegiatan?.kegiatan?.nama || '-' }}</b-td>
          <b-td>
            <span v-if="item.kinerja_kegiatan">{{ item.kinerja_kegiatan.realisasi_anggaran | rupiah }}</span>
            <span v-else>-</span>
          </b-td>
          <b-td>{{ item.kinerja_sub_kegiatan?.sub_kegiatan?.nama || '-' }}</b-td>
          <b-td>
            <span v-if="item.kinerja_sub_kegiatan">{{ item.kinerja_sub_kegiatan.realisasi_anggaran | rupiah }}</span>
            <span v-else>-</span>
          </b-td>
          <b-td>{{ item.langkah_aksi }}</b-td>
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
          <i class="fa fa-spinner fa-pulse fa-fw" v-if="isBusy.doFilter && active"></i>
          <template v-else>{{ page }}</template>
        </template>
      </b-pagination>
    </div>
  </b-card>
</template>
