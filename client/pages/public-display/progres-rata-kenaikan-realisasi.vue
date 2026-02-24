<script>
import axios from 'axios'

export default {
  layout: 'guest',

  data() {
    return {
      data: [],
      isBusy: {
        getData: false
      }
    }
  },

  computed: {
    tahunPerbandingan() {
      const tahunKinerja = this.$helper.getTahunKinerjaPublic()

      return {
        sebelum: {
          tahun: tahunKinerja - 1,
          realisasi_key: this.$helper.getKeyTahunPublic('realisasi', -1),
          capaian_key: this.$helper.getKeyTahunPublic('capaian', -1),
        },
        sekarang: {
          tahun: tahunKinerja,
          realisasi_key: this.$helper.getKeyTahunPublic('realisasi'),
          capaian_key: this.$helper.getKeyTahunPublic('capaian'),
        }
      }
    }
  },

  mounted() {
    this.getData()
  },

  methods: {
    async getData() {
      this.isBusy.getData = true

      const { data } = await axios.get('/public-display/progres-rata-kenaikan-realisasi')

      this.data = data
      this.isBusy.getData = false
    },

    getPerbandingan(item) {
      const sebelum = {
        realisasi: item[this.tahunPerbandingan.sebelum.realisasi_key] || '-',
        capaian: item[this.tahunPerbandingan.sebelum.capaian_key] || 0,
      }

      const sekarang = {
        realisasi: item[this.tahunPerbandingan.sekarang.realisasi_key] || '-',
        capaian: item[this.tahunPerbandingan.sekarang.capaian_key] || 0,
      }

      return {
        sebelum,
        sekarang,
        kenaikan: sekarang.capaian - sebelum.capaian
      }
    }
  }
}
</script>

<template>
  <b-card>
    <b-table-simple :aria-busy="isBusy.getData" hover responsive bordered striped sticky-header="calc(100vh - 100px)"
      class="mt-4">
      <b-thead class="text-center align-middle" head-variant="info">
        <b-tr>
          <b-th class="align-middle">IKU</b-th>
          <b-th class="align-middle">Realisasi Tahun {{ tahunPerbandingan.sebelum.tahun }}</b-th>
          <b-th class="align-middle">Realisasi Tahun {{ tahunPerbandingan.sekarang.tahun }}</b-th>
          <b-th class="align-middle">Capaian Tahun {{ tahunPerbandingan.sebelum.tahun }} (%)</b-th>
          <b-th class="align-middle">Capaian Tahun {{ tahunPerbandingan.sekarang.tahun }} (%)</b-th>
          <b-th class="align-middle">Kenaikan (%)</b-th>
        </b-tr>
      </b-thead>
      <b-tbody>
        <tr v-for="item in data" :key="item.id">
          <td>{{ item.indikator_sasaran_strategis.nomor }}. {{ item.indikator_sasaran_strategis.indikator }}</td>
          <td>{{ getPerbandingan(item).sebelum.realisasi }}</td>
          <td>{{ getPerbandingan(item).sekarang.realisasi }}</td>
          <td>{{ getPerbandingan(item).sebelum.capaian }}</td>
          <td>{{ getPerbandingan(item).sekarang.capaian }}</td>
          <td>{{ getPerbandingan(item).kenaikan }}</td>
        </tr>
      </b-tbody>
    </b-table-simple>
  </b-card>
</template>