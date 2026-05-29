<script>
import axios from 'axios'

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

      const { data } = await axios.get('/public-display/display-makro/rencana-aksi', {
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
        
        this.getData(1)
      },
      deep: true,
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
    <b-table-simple sticky-header="calc(100vh - 100px)" :aria-busy="isBusy" hover responsive bordered striped>
      <b-thead class="text-center align-middle" head-variant="info">
        <b-tr>
          <b-th class="align-middle" rowspan="2">No</b-th>
          <b-th class="align-middle" rowspan="2">Sasaran Strategis RJMD</b-th>
          <b-th class="align-middle" rowspan="2">IKU Bupati</b-th>
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
          <b-th style="top:90px" v-for="n of 24" :key="n">{{ n }}</b-th>
        </b-tr>
      </b-thead>
      <b-tbody>
        <b-tr class="text-center" v-if="isBusy">
          <b-td colspan="24"><b-spinner small></b-spinner> Sedang memuat data...</b-td>
        </b-tr>
        <b-tr class="text-center" v-else-if="!data.data.length">
          <b-td colspan="24">There are no records to show</b-td>
        </b-tr>
        <template v-else v-for="(bySasaran, sasaranIndex) of data.data">
          <template v-for="(byIku, ikuIndex) of bySasaran">
            <template v-for="(bySatkerSasaran, satkerSasaranIndex) of byIku">
              <template v-for="(bySatkerIku, satkerIkuIndex) of bySatkerSasaran">
                <template v-for="(byProgram, programIndex) of bySatkerIku">
                  <template v-for="(byKegiatan, kegiatanIndex) of byProgram">
                    <template v-for="(bySubKegiatan, subKegiatanIndex) of byKegiatan">
                      <tr v-for="(val, index) of bySubKegiatan" :key="val.id">
                        <td v-if="ikuIndex == 0 && satkerSasaranIndex == 0 && satkerIkuIndex == 0 && programIndex == 0 && kegiatanIndex == 0 && subKegiatanIndex == 0 && index == 0" :rowspan="bySasaran.flat(7).length">{{ data.from + sasaranIndex }}</td>
                        <td v-if="ikuIndex == 0 && satkerSasaranIndex == 0 && satkerIkuIndex == 0 && programIndex == 0 && kegiatanIndex == 0 && subKegiatanIndex == 0 && index == 0" :rowspan="bySasaran.flat(7).length">{{ val.sasaran_strategis_rpjmd ? val.sasaran_strategis_rpjmd.sasaran_strategis.sasaran : '-' }}</td>
                        <td v-if="satkerSasaranIndex == 0 && satkerIkuIndex == 0 && programIndex == 0 && kegiatanIndex == 0 && subKegiatanIndex == 0 && index == 0" :rowspan="byIku.flat(6).length">{{ val.sasaran_strategis_rpjmd ? val.sasaran_strategis_rpjmd.indikator_sasaran_strategis.indikator : '-' }}</td>
                        <td v-if="satkerSasaranIndex == 0 && satkerIkuIndex == 0 && programIndex == 0 && kegiatanIndex == 0 && subKegiatanIndex == 0 && index == 0" :rowspan="byIku.flat(6).length">{{ val.sasaran_strategis_rpjmd ? val.sasaran_strategis_rpjmd[$helper.getKeyTahunDinamisPublic('target', filter.tahun_kinerja)] : '-' }}</td>
                        <td v-if="satkerIkuIndex == 0 && programIndex == 0 && kegiatanIndex == 0 && subKegiatanIndex == 0 && index == 0" :rowspan="bySatkerSasaran.flat(5).length">{{ val.sasaran_strategis_pd ? val.sasaran_strategis_pd.sasaran_strategis_satker : '-' }}</td>
                        <td v-if="programIndex == 0 && kegiatanIndex == 0 && subKegiatanIndex == 0 && index == 0" :rowspan="bySatkerIku.flat(4).length">{{ val.sasaran_strategis_pd ? val.sasaran_strategis_pd.iku : '-' }}</td>
                        <td v-if="kegiatanIndex == 0 && subKegiatanIndex == 0 && index == 0" :rowspan="byProgram.flat(3).length">{{ val.kinerja_program ? val.kinerja_program.program.nama : '-' }}</td>
                        <td v-if="subKegiatanIndex == 0 && index == 0" :rowspan="byKegiatan.flat(2).length">{{ val.kinerja_kegiatan?.kegiatan?.nama || '-' }}</td>
                        <td v-if="index == 0" :rowspan="bySubKegiatan.flat(1).length">{{ val.kinerja_sub_kegiatan?.sub_kegiatan?.nama || '-' }}</td>
                        <td>{{ val.langkah_aksi }}</td>
                        <td>{{ val.indikator }}</td>

                        <b-td class="text-center" v-for="(month, index) of $const.months" :key="index">
                          {{ val.target_bulanan[month[0]] || '-' }}
                        </b-td>

                        <b-td class="text-center">{{ val.target }}</b-td>
                      </tr>
                    </template>
                  </template>
                </template>
              </template>
            </template>
          </template>
        </template>
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
