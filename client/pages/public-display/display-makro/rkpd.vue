<script>
import axios from 'axios'

export default {
  layout: 'guest',

  data() {
    return {
      data: [],
      isBusy: false,
      filter: {
        tahun_kinerja: this.$helper.getTahunKinerjaPublic(),
      }
    }
  },

  watch: {
    filter: {
      handler: function (val) {
        this.$helper.setTahunKinerjaPublic(val.tahun_kinerja)
        this.getData()
      },
      deep: true,
    },
  },

  mounted() {
    this.getData()
  },

  methods: {
    async getData() {
      this.isBusy = true

      const {data: {
        data,
      }} = await axios.get('/public-display/display-makro/rkpd', {
        params: this.filter
      })

      this.data = data
      this.isBusy = false
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
    <b-table-simple hover responsive bordered striped :aria-busy="isBusy" sticky-header="calc(100vh - 100px)">
      <b-thead class="text-center align-middle" head-variant="info">
        <b-tr>
          <b-th class="align-middle">No</b-th>
          <b-th class="align-middle">Sasaran</b-th>
          <b-th class="align-middle">IKU Bupati</b-th>
          <b-th class="align-middle">Target</b-th>
          <b-th class="align-middle">Program</b-th>
          <b-th class="align-middle">Kegiatan</b-th>
          <b-th class="align-middle">Sub Kegiatan</b-th>
          <b-th class="align-middle">Anggaran</b-th>
        </b-tr>
      </b-thead>
      <b-tbody>
        <tr v-if="isBusy">
          <td colspan="8" class="text-center"><b-spinner small></b-spinner> Sedang memuat data...</td>
        </tr>
        <tr v-else-if="!data.length">
          <td colspan="8" class="text-center">Tidak ada data</td>
        </tr>
        <template v-else v-for="(bySasaran, sasaranIndex) of data">
          <template v-for="(byIku, ikuIndex) of bySasaran">
            <template v-for="(byProgram, programIndex) of byIku">
              <template v-for="(byKegiatan, kegiatanIndex) of byProgram">
                <tr v-for="(val, index) of byKegiatan" :key="val.id">
                  <td v-if="ikuIndex == 0 && programIndex == 0 && kegiatanIndex == 0 && index == 0" :rowspan="bySasaran.flat(4).length" class="text-center">{{ sasaranIndex + 1 }}</td>
                  <td v-if="ikuIndex == 0 && programIndex == 0 && kegiatanIndex == 0 && index == 0" :rowspan="bySasaran.flat(4).length">{{ val.sasaran_strategis_rpjmd ? val.sasaran_strategis_rpjmd.sasaran_strategis.sasaran : '-' }}</td>
                  <td v-if="programIndex == 0 && kegiatanIndex == 0 && index == 0" :rowspan="byIku.flat(3).length">{{ val.sasaran_strategis_rpjmd ? val.sasaran_strategis_rpjmd.indikator_sasaran_strategis.indikator : '-' }}</td>
                  <td v-if="programIndex == 0 && kegiatanIndex == 0 && index == 0" :rowspan="byIku.flat(3).length" class="text-center">{{ val.sasaran_strategis_rpjmd ? val.sasaran_strategis_rpjmd[$helper.getKeyTahunDinamisPublic('target', filter.tahun_kinerja)] : '-' }}</td>
                  <td v-if="kegiatanIndex == 0 && index == 0" :rowspan="byProgram.flat(2).length">{{ val.kinerja_program ? val.kinerja_program.program.nama : '-'  }}</td>
                  <td v-if="index == 0" :rowspan="byKegiatan.flat(1).length">{{ val.kegiatan ? val.kegiatan.nama : '-'  }}</td>
                  <td>{{ val.sub_kegiatan?.nama || '-'  }}</td>
                  <td>
                    <span v-if="val.anggaran">{{ val.anggaran | rupiah }}</span>
                    <span v-else>-</span>
                  </td>
                </tr>
              </template>
            </template>
          </template>
        </template>
      </b-tbody>
    </b-table-simple>
  </b-card>
</template>
