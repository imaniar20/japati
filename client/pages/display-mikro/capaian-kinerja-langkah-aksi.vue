<script>
import axios from 'axios';

export default {
  middleware: ['auth'],

  async asyncData() {
    const { data } = await axios.get('/display-mikro/capaian-kinerja-langkah-aksi')

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

      const { data } = await axios.get('/display-mikro/capaian-kinerja-langkah-aksi', {
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
        const { data } = await axios.get('/display-mikro/capaian-kinerja-langkah-aksi/export', {
          params: {
            filter: this.filter,
          },
          responseType: 'blob'
        })

        const url = window.URL.createObjectURL(new Blob([data]))
        const link = document.createElement('a')

        link.href = url
        link.setAttribute('download', 'Capaian Kinerja Langkah Aksi.xlsx')
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
          <b-th class="align-middle" rowspan="2">No</b-th>
          <b-th class="align-middle" rowspan="2" v-if="$role.isSuper() || $role.isSetda()">Satuan Kerja</b-th>
          <b-th class="align-middle" rowspan="2">Sasaran</b-th>
          <b-th class="align-middle" rowspan="2">Indikator</b-th>
          <b-th class="align-middle" rowspan="2">% Capaian Kinerja</b-th>
          <b-th class="align-middle" rowspan="2">Program</b-th>
          <b-th class="align-middle" rowspan="2">Kegiatan</b-th>
          <b-th class="align-middle" rowspan="2">Sub Kegiatan</b-th>
          <b-th class="align-middle" rowspan="2">Langkah Aksi</b-th>
          <b-th class="align-middle" rowspan="2">Indikator Kinerja</b-th>
          <b-th class="align-middle" rowspan="2">Target</b-th>
          <b-th class="align-middle" colspan="12">Realisasi Bulan Ke</b-th>
          <b-th class="align-middle" rowspan="2">Capaian</b-th>
          <b-th class="align-middle" colspan="3">Anggaran</b-th>
        </b-tr>
        <b-tr>
          <b-th style="top:45px" v-for="n of 12" :key="n">{{ n }}</b-th>
          <b-th style="top:45px" class="align-middle">Target (Rp)</b-th>
          <b-th style="top:45px" class="align-middle">Realiasasi (Rp)</b-th>
          <b-th style="top:45px" class="align-middle">Capaian (%)</b-th>
        </b-tr>
        <b-tr>
          <b-th style="top:110px" v-for="n of $role.isSuper() || $role.isSetda() ? 27 : 26" :key="n">{{ n }}</b-th>
        </b-tr>
      </b-thead>
      <b-tbody>
        <b-tr class="text-center" v-if="!data.data.length">
          <b-td :colspan="$role.isSuper() || $role.isSetda() ? 27 : 26">There are no records to show</b-td>
        </b-tr>
        <b-tr v-else v-for="(item, index) of data.data" :key="item.id">
          <td>{{ data.from + index }}</td>
          <td v-if="$role.isSuper() || $role.isSetda()">{{ item.satuan_kerja.satuan_kerja_nama }}</td>
          <b-td>{{ item.sasaran_strategis_pd ? item.sasaran_strategis_pd.sasaran_strategis_satker : '-' }}</b-td>
          <b-td>{{ item.sasaran_strategis_pd ? item.sasaran_strategis_pd.iku : '-' }}</b-td>
          <b-td class="text-center">{{ item.sasaran_strategis_pd ? item.sasaran_strategis_pd[$helper.getKeyTahun('capaian')] || '-' : '-' }}</b-td>
          <b-td>{{ item.kinerja_program ? item.kinerja_program.program.nama : '-' }}</b-td>
          <b-td>{{ item.kinerja_kegiatan?.kegiatan?.nama || '-' }}</b-td>
          <b-td>{{ item.kinerja_sub_kegiatan?.sub_kegiatan?.nama || '-' }}</b-td>
          <b-td>{{ item.langkah_aksi }}</b-td>
          <b-td>{{ item.indikator }}</b-td>
          <b-td class="text-center">{{ item.target }}</b-td>

          <b-td v-for="(month, index) of $const.months" :key="index">
            {{ item.realisasi_bulanan[month[0]] || '-' }}
          </b-td>

          <b-td class="text-center">{{ item.capaian }}</b-td>
          <b-td class="text-center">{{ item.anggaran | rupiah }}</b-td>
          <b-td class="text-center">{{ item.realisasi_anggaran | rupiah }}</b-td>
          <b-td class="text-center">{{ item.capaian_anggaran || '-' }}</b-td>
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
