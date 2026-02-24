<script>
import axios from 'axios';

export default {
  layout: 'guest',

  data() {
    return {
      data: {
        data: [],
        from: 0,
        current_page: 1,
        total: 0,
        per_page: 0,
      },
      filter: {
        satuan_kerja_id: null,
      },
      isBusy: false,
    }
  },

  mounted() {
    if (this.$route.query.tahun_kinerja) {
      this.$helper.setTahunKinerjaPublic(this.$route.query.tahun_kinerja)
    }

    this.filter.satuan_kerja_id = Number(this.$route.query.satuan_kerja_id) || null

    this.doFilter()
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

      const { data } = await axios.get('/public-display/display-mikro/rencana-aksi', {
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
          <FilterSatuanKerja v-model="filter.satuan_kerja_id" />
        </b-col>
      </b-row>
    </div>

    <b-table-simple sticky-header="calc(100vh - 200px)" :aria-busy="isBusy" hover responsive bordered striped>
      <b-thead class="text-center align-middle" head-variant="info">
        <b-tr>
          <b-th class="align-middle" rowspan="2">No</b-th>
          <b-th class="align-middle" rowspan="2">Satuan Kerja</b-th>
          <b-th class="align-middle" rowspan="2">Sasaran Strategis RPJMD</b-th>
          <b-th class="align-middle" rowspan="2">IKU Gubernur</b-th>
          <b-th class="align-middle" rowspan="2">Target</b-th>
          <b-th class="align-middle" rowspan="2">Sasaran Strategis PD</b-th>
          <b-th class="align-middle" rowspan="2">Indikator Kinerja</b-th>
          <b-th class="align-middle" rowspan="2">Program</b-th>
          <b-th class="align-middle" rowspan="2">Kegiatan</b-th>
          <b-th class="align-middle" rowspan="2">Sub Kegiatan</b-th>
          <b-th class="align-middle" rowspan="2">Aktivitas</b-th>
          <b-th class="align-middle" rowspan="2">Indikator Aktivitas</b-th>
          <b-th class="align-middle" colspan="12">Target</b-th>
          <b-th class="align-middle" rowspan="2">Jumlah</b-th>
        </b-tr>
        <b-tr>
          <b-th style="top:45px" v-for="n of 12" :key="n">{{ n }}</b-th>
        </b-tr>
        <b-tr>
          <b-th style="top:90px" v-for="n of 25" :key="n">{{ n }}</b-th>
        </b-tr>
      </b-thead>
      <b-tbody>
        <b-tr class="text-center" v-if="!data.data.length">
          <b-td colspan="25">There are no records to show</b-td>
        </b-tr>
        <b-tr v-else v-for="(item, index) of data.data" :key="item.id">
          <td>{{ data.from + index }}</td>
          <td>{{ item.satuan_kerja.satuan_kerja_nama }}</td>
          <b-td>{{ item.sasaran_strategis_rpjmd ? item.sasaran_strategis_rpjmd.sasaran_strategis.sasaran : '-' }}</b-td>
          <b-td>{{ item.sasaran_strategis_rpjmd ? item.sasaran_strategis_rpjmd.indikator_sasaran_strategis.indikator : '-' }}</b-td>
          <b-td>{{ item.sasaran_strategis_rpjmd ? item.sasaran_strategis_rpjmd[$helper.getKeyTahunPublic('target')] : '-' }}</b-td>
          <b-td>{{ item.sasaran_strategis_pd ? item.sasaran_strategis_pd.sasaran_strategis_satker : '-' }}</b-td>
          <b-td>{{ item.sasaran_strategis_pd ? item.sasaran_strategis_pd.iku : '-' }}</b-td>
          <b-td>{{ item.kinerja_program ? item.kinerja_program.program.nama : '-' }}</b-td>
          <b-td>{{ item.kinerja_kegiatan?.kegiatan?.nama || '-' }}</b-td>
          <b-td>{{ item.kinerja_sub_kegiatan?.sub_kegiatan?.nama || '-' }}</b-td>
          <b-td>{{ item.langkah_aksi }}</b-td>
          <b-td>{{ item.indikator }}</b-td>

          <b-td v-for="(month, index) of $const.months" :key="index">
            {{ item.target_bulanan[month[0]] }}
          </b-td>

          <b-td>
            {{ item.target }}
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
