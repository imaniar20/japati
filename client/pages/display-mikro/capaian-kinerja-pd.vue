<script>
import axios from 'axios';

export default {
  middleware: ['auth'],

  async asyncData() {
    const { data } = await axios.get('/display-mikro/capaian-kinerja-pd')

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
    parsePeningkatan(item, key) {
      const sekarang = item[this.$helper.getKeyTahun(key)]
      const sebelum = item[this.$helper.getKeyTahun(key, -1)]

      if (!sekarang || !sebelum) return '-';

      return (sekarang - sebelum).toFixed(2)
    },
    async doFilter(page = 1) {
      this.isBusy.doFilter = true

      const { data } = await axios.get('/display-mikro/capaian-kinerja-pd', {
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
        const { data } = await axios.get('/display-mikro/capaian-kinerja-pd/export', {
          params: {
            filter: this.filter,
          },
          responseType: 'blob'
        })

        const url = window.URL.createObjectURL(new Blob([data]))
        const link = document.createElement('a')

        link.href = url
        link.setAttribute('download', 'Capaian Kinerja Perangkat Daerah.xlsx')
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

    <b-table-simple sticky-header="calc(100vh - 0px)" :aria-busy="isBusy.doFilter" hover responsive bordered striped>
      <b-thead class="text-center align-middle" head-variant="info">
        <b-tr>
          <b-th class="align-middle" rowspan="3">No</b-th>
          <b-th class="align-middle" rowspan="3" v-if="$role.isSuper() || $role.isSetda()">Satuan Kerja</b-th>
          <b-th class="align-middle" rowspan="3">Sasaran</b-th>
          <b-th class="align-middle" rowspan="3">Indikator</b-th>
          <b-th class="align-middle" colspan="3">P1</b-th>
          <b-th class="align-middle" colspan="6">P2</b-th>
          <b-th class="align-middle" colspan="2">P3</b-th>
          <b-th class="align-middle" colspan="2">P4</b-th>
          <b-th class="align-middle" rowspan="3">Penghargaan</b-th>
        </b-tr>
        <b-tr>
          <b-th style="top:45px" class="align-middle" colspan="3">Capaian Tahun {{ $helper.getTahunKinerja() }}</b-th>
          <b-th style="top:45px" class="align-middle" colspan="6">Peningkatan dari Tahun Lalu</b-th>
          <b-th style="top:45px" class="align-middle" colspan="2"><span style="min-width:200px;display:inline-block;">Capaian Tahun {{ $helper.getTahunKinerja() }} Terhadap Target Akhir Renstra</span></b-th>
          <b-th style="top:45px" class="align-middle" colspan="2">Perbandingan Dengan Nasional</b-th>
        </b-tr>
        <b-tr>
          <b-th style="top:110px" class="align-middle">Target</b-th>
          <b-th style="top:110px" class="align-middle">Realisasi</b-th>
          <b-th style="top:110px" class="align-middle">% Capaian</b-th>

          <b-th style="top:110px" class="align-middle">Realisasi {{ $helper.getTahunKinerja() }}</b-th>
          <b-th style="top:110px" class="align-middle">Realisasi Tahun lalu</b-th>
          <b-th style="top:110px" class="align-middle">Perbandingan realisasi dari tahun lalu</b-th>
          <b-th style="top:110px" class="align-middle">Capaian {{ $helper.getTahunKinerja() }}</b-th>
          <b-th style="top:110px" class="align-middle">Capaian Tahun Lalu</b-th>
          <b-th style="top:110px" class="align-middle">Peningkatan Capaian dari Tahun Lalu</b-th>

          <b-th style="top:110px" class="align-middle">Target akhir Renstra</b-th>
          <b-th style="top:110px" class="align-middle"><span style="min-width:115px;display:inline-block;">Realisasi Tahun {{ $helper.getTahunKinerja() }} Terhadap Target Akhir Renstra</span></b-th>
          <b-th style="top:110px" class="align-middle">Rata-Rata Realisasi Nasional</b-th>
          <b-th style="top:110px" class="align-middle">Peringkat di Tingkat Nasional</b-th>
        </b-tr>
        <b-tr>
          <b-th style="top:210px" v-for="n of $role.isSuper() || $role.isSetda() ? 18 : 17" :key="n">{{ n }}</b-th>
        </b-tr>
      </b-thead>
      <b-tbody>
        <b-tr class="text-center" v-if="!data.data.length">
          <b-td :colspan="$role.isSuper() || $role.isSetda() ? 18 : 17">There are no records to show</b-td>
        </b-tr>
        <b-tr v-for="(item, index) of data.data" :key="item.id">
          <td>{{ data.from + index }}</td>
          <td v-if="$role.isSuper() || $role.isSetda()">{{ item.satuan_kerja.satuan_kerja_nama }}</td>
          <b-td>{{ item.sasaran_strategis_satker }}</b-td>
          <b-td>{{ item.iku }}</b-td>
          <b-td class="text-center">{{ item[$helper.getKeyTahun('target')] || '-' }}</b-td>
          <b-td class="text-center">{{ item[$helper.getKeyTahun('realisasi')] || '-' }}</b-td>
          <b-td class="text-center">{{ item[$helper.getKeyTahun('capaian')] || '-' }}</b-td>

          <!-- tahun sekarang -->
          <b-td class="text-center">{{ item[$helper.getKeyTahun('realisasi')] || '-' }}</b-td>
          <!-- 1 tahun sebelumnya -->
          <b-td class="text-center">{{ item[$helper.getKeyTahun('realisasi', -1)] || '-' }}</b-td>
          <b-td class="text-center">{{ parsePeningkatan(item, 'realisasi') }}</b-td>

          <!-- tahun sekarang -->
          <b-td class="text-center">{{ item[$helper.getKeyTahun('capaian')] || '-' }}</b-td>
          <!-- 1 tahun sebelumnya -->
          <b-td class="text-center">{{ item[$helper.getKeyTahun('capaian', -1)] || '-' }}</b-td>
          <b-td class="text-center">{{ parsePeningkatan(item, 'capaian') }}</b-td>

          <b-td class="text-center">{{ item.target_5 }}</b-td>
          <b-td class="text-center">
            <span v-if="item[$helper.getKeyTahun('realisasi')]">
              {{ (item[$helper.getKeyTahun('realisasi')] / item.target_5).toFixed(2) }}
            </span>
            <span v-else>-</span>
          </b-td>

          <b-td class="text-center">{{ item.rata_nasional }}</b-td>
          <b-td class="text-center">{{ item.peringkat_nasional }}</b-td>
          <b-td class="text-center">{{ item.redaksi }}</b-td>
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

<style scoped>
  .b-table-sticky-header > .table.b-table > thead > tr > th {
    font-size: inherit !important;
}
</style>
