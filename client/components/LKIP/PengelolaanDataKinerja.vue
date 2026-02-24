<script>
import axios from 'axios'

export default {
  props: {
    satuanKerjaId: {
      required: true,
    }
  },

  data() {
    return {
      isBusy: false,
      data: [],
    }
  },

  mounted() {
    this.getData()
  },

  methods: {
    async getData(page = 1) {
      this.isBusy = true

      const { data } = await axios.get('/lkip/pengelolaan-data-kinerja', {
        params: {
          satuan_kerja_id: this.satuanKerjaId,
          page,
        }
      })

      this.data = data
      this.isBusy = false
    }
  }
}
</script>

<template>
  <div>
    <b-table-simple class="mt-3" :aria-busy="isBusy" responsive bordered striped hover>
      <b-thead class="text-center align-middle" head-variant="info">
        <b-tr>
          <b-th rowspan="2">NO.</b-th>
          <b-th rowspan="2">SASARAN</b-th>
          <b-th rowspan="2">INDIKATOR KINERJA UTAMA</b-th>
          <b-th colspan="3">CAPAIAN KINERJA TAHUN {{ $helper.getTahunKinerja() }}</b-th>
          <b-th colspan="6">PENINGKATAN DARI TAHUN LALU</b-th>
          <b-th colspan="3">CAPAIAN TAHUN {{ $helper.getTahunKinerja() }} TERHADAP TARGET AKHIR RPJMD/Renstra</b-th>
          <b-th colspan="2">PERBANDINGAN DENGAN NASIONAL</b-th>
          <b-th rowspan="2">PERINGKAT JAWA BARAT DI LEVEL PULAU JAWA</b-th>
          <b-th rowspan="2">PERINGKAT JAWA BARAT DI LEVEL NASIONAL</b-th>
          <b-th rowspan="2">IKU PD YANG TERKAIT</b-th>
          <b-th rowspan="2">TARGET TAHUN {{ $helper.getTahunKinerja() }}</b-th>
          <b-th rowspan="2">REALIASI TAHUN {{ $helper.getTahunKinerja() }}</b-th>
          <b-th rowspan="2">CAPAIAN (%)</b-th>
          <b-th rowspan="2">FAKTOR KEBERHASILAN/KEGAGALAN</b-th>
          <b-th rowspan="2">NAMA PROGRAM</b-th>
          <b-th rowspan="2">PAGU ANGGARAN</b-th>
          <b-th rowspan="2">REALISASI ANGGARAN</b-th>
          <b-th colspan="2">EFISIENSI</b-th>
        </b-tr>
        <b-tr>
          <b-th>TARGET TAHUN {{ $helper.getTahunKinerja() }}</b-th>
          <b-th>REALISASI TAHUN {{ $helper.getTahunKinerja() }}</b-th>
          <b-th>CAPAIAN TAHUN {{ $helper.getTahunKinerja() }} (%)</b-th>

          <b-th>REALISASI {{ $helper.getTahunKinerja() - 1 }}</b-th>
          <b-th>REALISASI {{ $helper.getTahunKinerja() }}</b-th>
          <b-th>PENINGKATAN ATAU PENURUNAN REALISASI DARI TAHUN {{ $helper.getTahunKinerja() - 1 }}</b-th>
          <b-th>CAPAIAN KINERJA TAHUN {{ $helper.getTahunKinerja() - 1 }} (%)</b-th>
          <b-th>CAPAIAN KINERJA TAHUN {{ $helper.getTahunKinerja() }} (%)</b-th>
          <b-th>PENINGKATAN ATAU PENURUNAN CAPAIAN DARI TAHUN {{ $helper.getTahunKinerja() - 1 }}</b-th>

          <b-th>REALISASI TAHUN {{ $helper.getTahunKinerja() }}</b-th>
          <b-th>TARGET AKHIR RPJMD/Renstra</b-th>
          <b-th>CAPAIAN</b-th>

          <b-th>RATA-RATA REALISASI NASIONAL</b-th>
          <b-th>PERBANDINGAN REALISASI TAHUN {{ $helper.getTahunKinerja() }} DENGAN RATA-RATA NASIONAL</b-th>

          <b-th>ANGGARAN</b-th>
          <b-th>(%)</b-th>
        </b-tr>
        <b-tr>
          <b-th v-for="n in 29" :key="n">{{ n }}</b-th>
        </b-tr>
      </b-thead>
      <b-tbody>
        <b-tr v-if="isBusy">
          <b-td colspan="28" class="text-center"><b-spinner /></b-td>
        </b-tr>
        <tr v-for="(item, index) of data.data" :key="item.id">
          <td>{{ data.from + index }}</td>
          <td>{{ item.sasaran_strategis_pd.sasaran_strategis_satker }}</td>
          <td>{{ item.sasaran_strategis_pd.iku }}</td>

          <td>{{ item.sasaran_strategis_pd[$helper.getKeyTahun('target')] }}</td>
          <td>{{ item.sasaran_strategis_pd[$helper.getKeyTahun('realisasi')] || '-' }}</td>
          <td>{{ item.sasaran_strategis_pd[$helper.getKeyTahun('capaian')] || '-' }}</td>

          <td>{{ item.sasaran_strategis_pd[$helper.getKeyTahun('realisasi', -1)] || '-' }}</td>
          <td>{{ item.sasaran_strategis_pd[$helper.getKeyTahun('realisasi')] || '-' }}</td>
          <td>
            <template v-if="item.sasaran_strategis_pd[$helper.getKeyTahun('realisasi')] && item.sasaran_strategis_pd[$helper.getKeyTahun('realisasi', -1)]">
              {{ item.sasaran_strategis_pd[$helper.getKeyTahun('realisasi')] - item.sasaran_strategis_pd[$helper.getKeyTahun('realisasi', -1)] }}
            </template>
            <template v-else>-</template>
          </td>
          <td>{{ item.sasaran_strategis_pd[$helper.getKeyTahun('capaian', -1)] }}</td>
          <td>{{ item.sasaran_strategis_pd[$helper.getKeyTahun('capaian')] }}</td>
          <td>
            <template v-if="item.sasaran_strategis_pd[$helper.getKeyTahun('capaian')] && item.sasaran_strategis_pd[$helper.getKeyTahun('capaian', -1)]">
              {{ item.sasaran_strategis_pd[$helper.getKeyTahun('capaian')] - item.sasaran_strategis_pd[$helper.getKeyTahun('capaian', -1)] }}
            </template>
            <template v-else>-</template>
          </td>

          <td>{{ item.sasaran_strategis_pd[$helper.getKeyTahun('realisasi')] || '-' }}</td>
          <td>{{ item.sasaran_strategis_pd.target_5 }}</td>
          <td>{{ item.sasaran_strategis_pd[$helper.getKeyTahun('capaian')] || '-' }}</td>

          <td>{{ item.satker_iku.rata_nasional || '-' }}</td>
          <td>
            <template v-if="item.satker_iku[$helper.getKeyTahun('realisasi')] && item.satker_iku.rata_nasional">
              {{ item.satker_iku[$helper.getKeyTahun('realisasi')] / item.satker_iku.rata_nasional }}
            </template>
            <template v-else>-</template>
          </td>

          <td>-</td>
          <td>{{ item.satker_iku.peringkat_nasional }}</td>
          <td>{{ item.satker_iku.iku }}</td>
          <td>{{ item.satker_iku[$helper.getKeyTahun('target')] }}</td>
          <td>{{ item.satker_iku[$helper.getKeyTahun('realisasi')] || '-' }}</td>
          <td>{{ item.satker_iku[$helper.getKeyTahun('capaian')] || '-' }}</td>
          <td><nuxt-link to="/display-mikro/cascading">Cascading</nuxt-link></td>
          <td>{{ item.program.nama }}</td>
          <td>{{ item.anggaran | rupiah }}</td>
          <td>
            <template v-if="item.realisasi_anggaran">{{ item.realisasi_anggaran | rupiah }}</template>
            <template v-else>-</template>
          </td>

          <td>
            <template v-if="item.realisasi_anggaran">{{ (item.anggaran - item.realisasi_anggaran) | rupiah }}</template>
            <template v-else>-</template>
          </td>
          <td>
            <template v-if="item.realisasi_anggaran">{{ Math.round(((item.anggaran - item.realisasi_anggaran) / item.anggaran * 100 + Number.EPSILON) * 100 ) / 100 }}</template>
            <template v-else>-</template>
          </td>
        </tr>
      </b-tbody>
    </b-table-simple>
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
</template>
