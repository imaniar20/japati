<script>
import axios from 'axios';
import Swal from 'sweetalert2'

export default {
  props: {
    satuanKerjaId: {
      type: Number,
      required: true,
    },
    tahunKinerja: {
      type: [Number, null],
      required: false,
    }
  },

  data() {
    return {
      isBusy: false,
      data: []
    }
  },

  mounted() {
    this.getData()
  },

  methods: {
    async getData() {
      try {
        this.isBusy = true

        const { data } = await axios.get('rapor-kinerja-sasaran-strategis-pd/kinerja', {
          params: {
            satuan_kerja_id: this.satuanKerjaId,
            tahun_kinerja: this.tahunKinerja,
          }
        })

        this.data = data
      } catch (error) {
        Swal.fire('Error', 'Gagal mengambil data', 'error')
      } finally {
        this.isBusy = false
      }
    },
    calculateBarValue(data, triwulan) {
      const value = triwulan == 1
        ? data[1]
        : data[triwulan] - data[triwulan - 1]

      return Math.max(0, value)
    }
  }
}
</script>

<template>
  <b-card>
    <div v-if="isBusy" class="text-center my-5">
      <b-spinner></b-spinner>
    </div>

    <div v-for="(kinerja, index) in data" class="mb-3">
      <div class="mb-3 text-center">
        <h6 class="font-weight-bold">{{ kinerja.sasaran_strategis_satker }}</h6>
        <h6 class="font-weight-bold">{{ kinerja.iku }}</h6>
      </div>
      <b-row>
        <b-col cols="1">
          <b>Target</b>
        </b-col>
        <b-col>
          <b-progress :max="Math.max(kinerja.target_triwulan[4], kinerja.realisasi_triwulan[4])" height="50px">
            <b-progress-bar variant="success" :value="calculateBarValue(kinerja.target_triwulan, 1)">
              TW 1 <br>
              <b>{{ kinerja.target_triwulan[1] }}</b>
            </b-progress-bar>
            <b-progress-bar variant="warning" :value="calculateBarValue(kinerja.target_triwulan, 2)">
              TW 2 <br>
              <b>{{ kinerja.target_triwulan[2] }}</b>
            </b-progress-bar>
            <b-progress-bar variant="primary" :value="calculateBarValue(kinerja.target_triwulan, 3)">
              TW 3 <br>
              <b>{{ kinerja.target_triwulan[3] }}</b>
            </b-progress-bar>
            <b-progress-bar variant="secondary" :value="calculateBarValue(kinerja.target_triwulan, 4)">
              TW 4 <br>
              <b>{{ kinerja.target_triwulan[4] }}</b>
            </b-progress-bar>
          </b-progress>
        </b-col>
      </b-row>
      <b-row>
        <b-col cols="1">
          <b>Realisasi</b>
        </b-col>
        <b-col>
          <b-progress :max="Math.max(kinerja.target_triwulan[4], kinerja.realisasi_triwulan[4])" height="50px">
            <b-progress-bar variant="success" :value="calculateBarValue(kinerja.realisasi_triwulan, 1)">
              TW 1 <br>
              <b>{{ kinerja.realisasi_triwulan[1] }}</b>
            </b-progress-bar>
            <b-progress-bar variant="warning" :value="calculateBarValue(kinerja.realisasi_triwulan, 2)">
              TW 2 <br>
              <b>{{ kinerja.realisasi_triwulan[2] }}</b>
            </b-progress-bar>
            <b-progress-bar variant="primary" :value="calculateBarValue(kinerja.realisasi_triwulan, 3)">
              TW 3 <br>
              <b>{{ kinerja.realisasi_triwulan[3] }}</b>
            </b-progress-bar>
            <b-progress-bar variant="secondary" :value="calculateBarValue(kinerja.realisasi_triwulan, 4)">
              TW 4 <br>
              <b>{{ kinerja.realisasi_triwulan[4] }}</b>
            </b-progress-bar>
          </b-progress>
        </b-col>
      </b-row>
      <hr v-if="index != data.length - 1">
    </div>
  </b-card>
</template>

<style scoped>
.progress {
  line-height: 1rem;
}
</style>