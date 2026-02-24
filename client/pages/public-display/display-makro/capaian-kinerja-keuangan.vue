<script>
import axios from 'axios';

export default {
  layout: 'guest',
  data() {
    return {
      data: {
        data: [],
      },
      isBusy: false,
      filter: {
        tahun_kinerja: this.$helper.getTahunKinerjaPublic(),
      },
    }
  },
  mounted() {
    this.getData()
  },
  methods: {
    async getData(page = 1) {
      this.isBusy = true

      const { data } = await axios.get('/public-display/display-makro/capaian-kinerja-keuangan', {
        params: {
          page,
          tahun_kinerja: this.filter.tahun_kinerja,
        }
      })

      this.data = data
      this.isBusy = false
    },
  },
  watch: {
    filter: {
      handler: function (val) {
        this.$helper.setTahunKinerjaPublic(val.tahun_kinerja)
        this.getData()
      },
      deep: true,
    }
  },
}
</script>

<template>
  <b-card>
    <b-row>
      <b-col sm="6" md="4">
        <FilterTahunKinerja v-model="filter.tahun_kinerja" />
      </b-col>
    </b-row>
    <b-table-simple sticky-header="calc(100vh - 150px)" :aria-busy="isBusy" hover responsive bordered striped>
      <b-thead class="text-center align-middle" head-variant="info">
        <b-tr>
          <b-th>No</b-th>
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
          <b-th style="top:85px" v-for="n of 12" :key="n">{{ n }}</b-th>
        </b-tr>
      </b-thead>
      <b-tbody>
        <b-tr class="text-center" v-if="isBusy && !data.data.length">
          <b-td :colspan="12"><b-spinner small></b-spinner> Sedang memuat data...</b-td>
        </b-tr>
        <b-tr class="text-center" v-else-if="!data.data.length">
          <b-td :colspan="12">There are no records to show</b-td>
        </b-tr>
        <b-tr v-else v-for="(item, index) of data.data" :key="item.id">
          <td>{{ data.from + index }}</td>
          <b-td>
            {{ item.sasaran_strategis_rpjmd ? item.sasaran_strategis_rpjmd.sasaran_strategis.sasaran : '-' }}
          </b-td>
          <b-td>
            {{ item.sasaran_strategis_rpjmd ? item.sasaran_strategis_rpjmd.indikator_sasaran_strategis.indikator : '-' }}
          </b-td>
          <b-td class="text-center">
            {{ item.sasaran_strategis_rpjmd ? item.sasaran_strategis_rpjmd[$helper.getKeyTahunDinamisPublic('capaian', filter.tahun_kinerja)] || '-' : '-' }}
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
