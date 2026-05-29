<script>
import axios from 'axios'

export default {
  layout: 'guest',

  data() {
    return {
      visi: null,
      sasaranStrategis: [],
      filter: {
        tahun_kinerja: this.$route.query?.tahun_kinerja || this.$helper.getTahunKinerjaPublic(),
        satuan_kerja_id: Number(this.$route.query.satuan_kerja_id) || null,
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

      const { data: {
        visi,
        sasaranStrategis,
      }} = await axios.get('/public-display/display-makro/rpjmd', {
        params: this.filter
      })

      this.visi = visi
      this.sasaranStrategis = sasaranStrategis
      this.isBusy.getData = false
    }
  }
}
</script>

<template>
  <b-card v-if="visi">
    <h4>Visi: {{ visi.visi }}</h4>
    <b-row>
      <b-col sm="6" md="4">
        <FilterTahunKinerja v-model="filter.tahun_kinerja" />
      </b-col>
    </b-row>
    <b-table-simple :aria-busy="isBusy.getData" hover responsive bordered striped sticky-header="calc(100vh - 100px)" class="mt-4">
      <b-thead class="text-center align-middle"  head-variant="info">
        <b-tr>
          <b-th rowspan="2" class="align-middle">No</b-th>
          <b-th rowspan="2" class="align-middle">Misi</b-th>
          <b-th rowspan="2" class="align-middle">Tujuan</b-th>
          <b-th rowspan="2" class="align-middle">Indikator</b-th>
          <b-th rowspan="2" class="align-middle">Target</b-th>
          <b-th rowspan="2" class="align-middle">Sasaran</b-th>
          <b-th rowspan="2" class="align-middle">IKU Bupati</b-th>
          <b-th colspan="5">Target Tahun ke-</b-th>
          <b-th rowspan="2" class="align-middle">Strategi</b-th>
        </b-tr>
        <b-tr>
          <b-th style="top:49px;">{{ tahunMulai }}</b-th>
          <b-th style="top:49px;">{{ tahunMulai + 1 }}</b-th>
          <b-th style="top:49px;">{{ tahunMulai + 2 }}</b-th>
          <b-th style="top:49px;">{{ tahunMulai + 3 }}</b-th>
          <b-th style="top:49px;">{{ tahunMulai + 4 }}</b-th>
        </b-tr>
      </b-thead>
      <b-tbody>
        <template v-for="(byMisi, misiIndex) of sasaranStrategis">
          <template v-for="(byTujuan, tujuanIndex) of byMisi">
            <template v-for="(byIndikatorTujuan, indikatorTujuanIndex) of byTujuan">
              <template v-for="(bySasaran, sasaranIndex) of byIndikatorTujuan">
                <tr v-for="(val, index) of bySasaran" :key="val.id">
                  <td v-if="tujuanIndex == 0 && indikatorTujuanIndex == 0 && sasaranIndex == 0 && index == 0" :rowspan="byMisi.flat(4).length">{{ misiIndex + 1 }}</td>
                  <td v-if="tujuanIndex == 0 && indikatorTujuanIndex == 0 && sasaranIndex == 0 && index == 0" :rowspan="byMisi.flat(4).length">{{ val.misi ? val.misi.misi : '-' }}</td>
                  <td v-if="indikatorTujuanIndex == 0 && sasaranIndex == 0 && index == 0" :rowspan="byTujuan.flat(3).length">{{ val.tujuan ? val.tujuan.tujuan : '-' }}</td>
                  <td v-if="sasaranIndex == 0 && index == 0" :rowspan="byIndikatorTujuan.flat(2).length">{{ val.indikator_tujuan ? val.indikator_tujuan.indikator : '-' }}</td>
                  <td v-if="sasaranIndex == 0 && index == 0" :rowspan="byIndikatorTujuan.flat(2).length">{{ val.target_visi_misi ? val.target_visi_misi[$helper.getKeyTahunDinamisPublic('target', filter.tahun_kinerja)] : '-' }}</td>
                  <td v-if="index == 0" :rowspan="bySasaran.flat(1).length">{{ val.sasaran_strategis.sasaran }}</td>
                  <td>{{ val.indikator_sasaran_strategis.indikator }}</td>
                  <td class="text-center">{{ val.target_1 }}</td>
                  <td class="text-center">{{ val.target_2 }}</td>
                  <td class="text-center">{{ val.target_3 }}</td>
                  <td class="text-center">{{ val.target_4 }}</td>
                  <td class="text-center">{{ val.target_5 }}</td>
                  <td>{{ val.strategi || '-' }}</td>
                </tr>
              </template>
            </template>
          </template>
        </template>
      </b-tbody>
    </b-table-simple>
  </b-card>
</template>
