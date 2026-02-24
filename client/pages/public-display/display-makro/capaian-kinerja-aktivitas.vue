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

      const { data } = await axios.get('/public-display/display-makro/capaian-kinerja-aktivitas', {
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

    <b-table-simple sticky-header="calc(100vh - 200px)" :aria-busy="isBusy" hover responsive bordered striped>
      <b-thead class="text-center align-middle" head-variant="info">
        <b-tr>
          <b-th class="align-middle" rowspan="2">No</b-th>
          <b-th class="align-middle" rowspan="2">Sasaran</b-th>
          <b-th class="align-middle" rowspan="2">Indikator</b-th>
          <b-th class="align-middle" rowspan="2">% Capaian Kinerja</b-th>
          <b-th class="align-middle" rowspan="2">Program</b-th>
          <b-th class="align-middle" rowspan="2">Kegiatan</b-th>
          <b-th class="align-middle" rowspan="2">Sub Kegiatan</b-th>
          <b-th class="align-middle" rowspan="2">Aktivitas</b-th>
          <b-th class="align-middle" rowspan="2">Indikator Kinerja</b-th>
          <b-th class="align-middle" rowspan="2">Target</b-th>
          <b-th class="align-middle" colspan="12">Realisasi Bulan Ke</b-th>
          <b-th class="align-middle" rowspan="2">Capaian</b-th>
          <b-th class="align-middle" colspan="3">Anggaran</b-th>
        </b-tr>
        <b-tr>
          <b-th style="top:45px" v-for="n of 12" :key="n">{{ n }}</b-th>
          <b-th style="top:45px">Target (Rp)</b-th>
          <b-th style="top:45px">Realiasasi (Rp)</b-th>
          <b-th style="top:45px">Capaian (%)</b-th>
        </b-tr>
        <b-tr>
          <b-th style="top:110px" v-for="n of 26" :key="n">{{ n }}</b-th>
        </b-tr>
      </b-thead>
      <b-tbody>
        <b-tr class="text-center" v-if="isBusy && !data.data.length">
          <b-td colspan="26"><b-spinner small></b-spinner> Sedang memuat data...</b-td>
        </b-tr>
        <b-tr class="text-center" v-else-if="!data.data.length">
          <b-td colspan="26">There are no records to show</b-td>
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
            {{ item.kinerja_kegiatan?.kegiatan?.nama || '-' }}
          </b-td>
          <b-td>
            {{ item.kinerja_sub_kegiatan?.sub_kegiatan?.nama || '-' }}
          </b-td>
          <b-td>{{ item.langkah_aksi }}</b-td>
          <b-td>{{ item.indikator }}</b-td>
          <b-td class="text-center">{{ item.target }}</b-td>

          <b-td class="text-center" v-for="(month, index) of $const.months" :key="index">
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
