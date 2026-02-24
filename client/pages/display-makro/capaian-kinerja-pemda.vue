<script>
import axios from 'axios'

export default {
  middleware: ['auth'],

  async asyncData() {
    const { data } = await axios.get('/display-makro/capaian-kinerja-pemda')

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

      const { data } = await axios.get('/display-makro/capaian-kinerja-pemda', {
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

    <b-table-simple sticky-header="calc(100vh - 100px)" :aria-busy="isBusy" hover responsive bordered striped>
      <b-thead class="text-center align-middle" head-variant="info">
        <b-tr>
          <b-th class="align-middle" rowspan="2">No</b-th>
          <b-th v-if="$role.isSuper() || $role.isSetda()" rowspan="2" class="align-middle">Satuan Kerja</b-th>
          <b-th class="align-middle" rowspan="2">Sasaran</b-th>
          <b-th class="align-middle" rowspan="2">Indikator</b-th>
          <b-th class="align-middle" colspan="3">Capaian Tahun {{ $helper.getTahunKinerja() }}</b-th>
          <b-th class="align-middle" colspan="6">Peningakatan dari Tahun Lalu</b-th>
          <b-th class="align-middle" colspan="2">Capaian Tahun {{ $helper.getTahunKinerja() }} Terhadap Target Akhir RPJMD</b-th>
          <b-th class="align-middle" colspan="2">Perbandingan Dengan Nasional</b-th>
          <b-th class="align-middle" rowspan="2">Raihan Penghargaan</b-th>
        </b-tr>
        <b-tr>
          <b-th class="align-top" style="top:79px;">Target</b-th>
          <b-th class="align-top" style="top:79px;">Realisasi</b-th>
          <b-th class="align-top" style="top:79px;">% Capaian</b-th>
          <b-th class="align-top" style="top:79px;">Realisasi {{ $helper.getTahunKinerja() }}</b-th>
          <b-th class="align-top" style="top:79px;">Realisasi {{ $helper.getTahunKinerja() - 1 }}</b-th>
          <b-th class="align-top" style="top:79px;">Perbandingan realisasi dari tahun lalu</b-th>
          <b-th class="align-top" style="top:79px;">Capaian {{ $helper.getTahunKinerja() }} (%)</b-th>
          <b-th class="align-top" style="top:79px;">Capaian {{ $helper.getTahunKinerja() - 1 }} (%)</b-th>
          <b-th class="align-top" style="top:79px;">Peningkatan Capaian dari Tahun Lalu (%)</b-th>
          <b-th class="align-top" style="top:79px;">Target akhir RPJMD</b-th>
          <b-th class="align-top" style="top:79px;"><span style="min-width: 125px;display: inline-block;">Realisasi Tahun {{ $helper.getTahunKinerja() }} Terhadap Target Akhir RJMD (%)</span></b-th>
          <b-th class="align-top" style="top:79px;">Rata-Rata Nasional</b-th>
          <b-th class="align-top" style="top:79px;">Peringkat di Tingkat Nasional</b-th>
        </b-tr>
        <b-tr>
          <b-th  style="top:190px;" v-for="n of $role.isSuper() || $role.isSetda() ? 18 : 17" :key="n">{{ n }}</b-th>
        </b-tr>
      </b-thead>
      <b-tbody>
        <b-tr class="text-center" v-if="!data.data.length">
          <b-td colspan="17">There are no records to show</b-td>
        </b-tr>
        <b-tr v-else v-for="(item, index) of data.data" :key="item.id">
          <td>{{ data.from + index }}</td>
          <td v-if="$role.isSuper() || $role.isSetda()">{{ item.satuan_kerja.satuan_kerja_nama }}</td>
          <b-td>{{ item.sasaran_strategis.sasaran }}</b-td>
          <b-td>{{ item.indikator_sasaran_strategis.indikator }}</b-td>

          <b-td class="text-center">{{ item[$helper.getKeyTahun('target')] }}</b-td>
          <b-td class="text-center">{{ item[$helper.getKeyTahun('realisasi')] || '-' }}</b-td>
          <b-td class="text-center">{{ item[$helper.getKeyTahun('capaian')] || '-' }}</b-td>

          <!-- tahun sekarang -->
          <b-td class="text-center">{{ item[$helper.getKeyTahun('realisasi')] || '-' }}</b-td>
          <!-- 1 tahun sebelumnya -->
          <b-td class="text-center">{{ item[$helper.getKeyTahun('realisasi', -1)] }}</b-td>
          <b-td class="text-center">{{ item[$helper.getKeyTahun('perbandingan_realisasi_tahun', -1)] || '-' }}</b-td>

          <!-- tahun sekarang -->
          <b-td class="text-center">{{ item[$helper.getKeyTahun('capaian')] || '-' }}</b-td>
          <!-- 1 tahun sebelumnya -->
          <b-td class="text-center">{{ item[$helper.getKeyTahun('capaian', -1)] }}</b-td>
          <b-td class="text-center">{{ (item[$helper.getKeyTahun('capaian')] || 0) - (item[$helper.getKeyTahun('capaian', -1)] || 0) }}</b-td>

          <b-td class="text-center">{{ item.target_5 }}</b-td>
          <b-td class="text-center">
            {{ (item.capaian_terhadap_target_akhir || '-') }}
          </b-td>

          <b-td class="text-center">{{ item.rata_nasional || '-' }}</b-td>
          <b-td class="text-center">{{ item.peringkat_nasional || '-' }}</b-td>

          <b-td class="text-center">{{ item.penghargaan || '-' }}</b-td>
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