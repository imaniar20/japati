<script>
import axios from 'axios'

export default {
  layout: 'guest',

  data() {
    return {
      data: [],
      table: {
        fields: [
          { key: 'no', label: 'No' },
          { key: 'sasaran_strategis', label: 'Sasaran' },
          { key: 'indikator_sasaran_strategis', label: 'IKU Bupati' },
          { key: 'target', label: 'Target' },
        ]
      },
      filter: {
        tahun_kinerja: this.$helper.getTahunKinerjaPublic(),
      },
      isBusy: {
        getData: false,
      },
    }
  },

  computed: {
    tahunMulai() {
      return this.$helper.getTahunMulaiByTahunKinerja(this.filter.tahun_kinerja)
    },
  },

  watch: {
    'filter.tahun_kinerja'(val) {
      this.$helper.setTahunKinerjaPublic(val)
    },
    tahunMulai() {
      this.getData()
    }
  },

  mounted() {
    this.getData()
  },

  methods: {
    async getData() {
      this.isBusy.getData = true

      const { data: { data }} = await axios.get('/public-display/display-makro/perjanjian-kinerja', {
        params: this.filter
      })

      this.data = data
      this.isBusy.getData = false
    }
  }
}
</script>

<template>
  <b-card>
    <b-row>
      <b-col sm="6" md="4">
        <FilterTahunKinerja v-model="filter.tahun_kinerja" />
      </b-col>
    </b-row>
    <b-table-simple :aria-busy="isBusy.getData" hover responsive bordered striped sticky-header="calc(100vh - 100px)" class="mt-4">
      <b-thead class="text-center align-middle" head-variant="info">
        <b-tr>
          <b-th class="align-middle">No</b-th>
          <b-th class="align-middle">Sasaran</b-th>
          <b-th class="align-middle">IKU Bupati</b-th>
          <b-th class="align-middle">Target</b-th>
        </b-tr>
      </b-thead>
      <b-tbody>
        <template v-for="(bySasaran, sasaranIndex) of data">
          <tr v-for="(val, index) of bySasaran" :key="val.id">
            <td v-if="index == 0" :rowspan="bySasaran.flat(1).length" class="text-center">{{ sasaranIndex + 1 }}</td>
            <td v-if="index == 0" :rowspan="bySasaran.flat(1).length">{{ val.sasaran_strategis.sasaran }}</td>
            <td>{{ val.indikator_sasaran_strategis.indikator }}</td>
            <td>{{ val[$helper.getKeyTahunDinamisPublic('target', filter.tahun_kinerja)] }}</td>
          </tr>
        </template>
      </b-tbody>
    </b-table-simple>
  </b-card>
</template>
