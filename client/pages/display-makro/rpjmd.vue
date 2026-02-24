<script>
import axios from 'axios'

export default {
  middleware: ['auth'],

  async asyncData() {
    const { data: {
      visi,
      sasaranStrategis,
    }} = await axios.get('/display-makro/rpjmd')

    return {
      visi,
      sasaranStrategis,
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

      const { data } = await axios.get('/display-makro/rpjmd', {
        params: {
          filter: this.filter,
          is_data_only: true,
          page,
        }
      })

      this.sasaranStrategis = data
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

    <h4>Visi: {{ visi.visi }}</h4>
    <b-table-simple hover striped responsive sticky-header="calc(100vh - 250px)" bordered class="mt-4" :aria-busy="isBusy">
      <b-thead class="text-center align-middle"  head-variant="info">
        <b-tr>
          <b-th rowspan="2" class="align-middle">No</b-th>
          <b-th v-if="$role.isSuper() || $role.isSetda()" rowspan="2" class="align-middle">Satuan Kerja</b-th>
          <b-th rowspan="2" class="align-middle">Misi</b-th>
          <b-th rowspan="2" class="align-middle">Tujuan</b-th>
          <b-th rowspan="2" class="align-middle">Indikator</b-th>
          <b-th rowspan="2" class="align-middle">Target</b-th>
          <b-th rowspan="2" class="align-middle">Sasaran</b-th>
          <b-th rowspan="2" class="align-middle">IKU Gubernur</b-th>
          <b-th colspan="5">Target Tahun ke-</b-th>
          <b-th rowspan="2" class="align-middle">Strategi</b-th>
        </b-tr>
        <b-tr>
          <b-th style="top:49px;">{{ $helper.getTahunMulai() }}</b-th>
          <b-th style="top:49px;">{{ $helper.getTahunMulai() + 1 }}</b-th>
          <b-th style="top:49px;">{{ $helper.getTahunMulai() + 2 }}</b-th>
          <b-th style="top:49px;">{{ $helper.getTahunMulai() + 3 }}</b-th>
          <b-th style="top:49px;">{{ $helper.getTahunMulai() + 4 }}</b-th>
        </b-tr>
      </b-thead>
      <b-tbody>
        <tr v-for="(val, i) of sasaranStrategis.data" :key="i">
          <td>{{ sasaranStrategis.from + i }}</td>
          <td v-if="$role.isSuper() || $role.isSetda()">{{ val.satuan_kerja.satuan_kerja_nama }}</td>
          <td>{{ val.misi ? val.misi.misi : '-' }}</td>
          <td>{{ val.tujuan ? val.tujuan.tujuan : '-' }}</td>
          <td>{{ val.indikator_tujuan ? val.indikator_tujuan.indikator : '-' }}</td>
          <td>{{ val.target_visi_misi ? val.target_visi_misi[$helper.getKeyTahun('target')] : '-' }}</td>
          <td>{{ val.sasaran_strategis.sasaran }}</td>
          <td>{{ val.indikator_sasaran_strategis.indikator }}</td>
          <td class="text-center">{{ val.target_1 }}</td>
          <td class="text-center">{{ val.target_2 }}</td>
          <td class="text-center">{{ val.target_3 }}</td>
          <td class="text-center">{{ val.target_4 }}</td>
          <td class="text-center">{{ val.target_5 }}</td>
          <td>{{ val.strategi || '-' }}</td>
        </tr>
      </b-tbody>
    </b-table-simple>

    <div>
      <b-pagination
        v-model="sasaranStrategis.current_page"
        :total-rows="sasaranStrategis.total"
        :per-page="sasaranStrategis.per_page"
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
