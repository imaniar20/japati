<script>
import axios from 'axios'
import CatatanRekomendasi from '@/components/CatatanRekomendasi.vue'
import { mapGetters } from 'vuex'
import Swal from 'sweetalert2';

export default {
  middleware: ['role-validator-lke-pleno'],

  components: { CatatanRekomendasi },

  data() {
    return {
      isBusy: {
        getData: false,
        exportExcel: false,
      },
      success: false,
      komponenList: [],
      bobotTotal: 0,
      skorTotal: 0,
      skorTotal2: 0,
      predikat: [],
      predikat2: [],
      predikatKomponen: [],
      skorTotalPenilaianKomponen: 0,
      done1: false,
      done2: false,
      filter: {
        satuan_kerja_id: null,
      },
    }
  },

  computed: {
    ...mapGetters({
      user: 'auth/user'
    }),
    predikatAkhir() {
      if (this.skorTotalPenilaianKomponen) {
        return this.predikatKomponen;
      } else if (this.done2) {
        return this.predikat2;
      } else {
        return this.predikat;
      }
    },
  },

  watch: {
    filter: {
      handler: function () {
        if (this.filter.satuan_kerja_id) this.getData()
      },
      deep: true,
    }
  },

  methods: {
    async getData() {
      this.isBusy.getData = true

      const { data: { success, data: komponenList, bobotTotal, skorTotal, skorTotal2, predikat, predikat2, predikatKomponen, skorTotalPenilaianKomponen, done1, done2 } } = await axios.get('/lke/penilaian/hasil', {
        params: this.filter,
      })

      this.success = success
      this.komponenList = komponenList
      this.bobotTotal = bobotTotal
      this.skorTotal = skorTotal
      this.skorTotal2 = skorTotal2
      this.predikat = predikat
      this.predikat2 = predikat2
      this.predikatKomponen = predikatKomponen
      this.skorTotalPenilaianKomponen = skorTotalPenilaianKomponen
      this.done1 = done1
      this.done2 = done2

      this.isBusy.getData = false
    },
    async exportExcel() {
      this.isBusy.exportExcel = true

      try {
        const { data } = await axios.get('/lke/penilaian/hasil/export', {
          params: this.filter,
          responseType: 'blob',
        })
        
        const url = window.URL.createObjectURL(new Blob([data]))
        const link = document.createElement('a')

        link.href = url
        link.setAttribute('download', 'Hasil Penilaian LKE.xlsx')
        document.body.appendChild(link)
        link.click()
      } catch (error) {
        Swal.fire('Gagal!', 'Gagal export excel', 'error')
      } finally {
        this.isBusy.exportExcel = false
      }
    },
  }
}
</script>

<template>
  <div>
    <b-card>
      <div>
        <FilterSatuanKerja v-model="filter.satuan_kerja_id" :selectProps="{clearable: false}" :satuanKerjaIds="user.lke_penilaian_satuan_kerja_ids" />
      </div>

      <div v-if="isBusy.getData" class="text-center my-5">
        <b-spinner></b-spinner>
      </div>

      <div v-else-if="filter.satuan_kerja_id">
        <div v-if="!success" class="text-center my-5">
          <h5>Penilaian belum selesai</h5>
        </div>

        <template v-else>
          <div class="text-right mb-3">
            <b-button variant="success" @click="exportExcel()" :disabled="isBusy.exportExcel">
              <b-spinner small v-if="isBusy.exportExcel"></b-spinner>
              Export
            </b-button>
          </div>

          <b-table-simple responsive bordered hover>
            <b-thead class="text-center align-middle">
              <b-tr>
                <b-th rowspan="2" class="align-middle">No</b-th>
                <b-th rowspan="2" class="align-middle">Komponen/Sub Komponen</b-th>
                <b-th rowspan="2" class="align-middle">Bobot</b-th>
                <b-th colspan="3">Nilai</b-th>
              </b-tr>
              <b-tr>
                <b-th>Nilai Tahap Awal</b-th>
                <b-th>Nilai Tahap Akhir</b-th>
                <b-th>Nilai Pleno</b-th>
              </b-tr>
            </b-thead>
            <b-tbody>
              <template v-for="(komponen) in komponenList">
                <b-tr :key="komponen.id">
                  <b-td><b>{{ komponen.nomor }}</b></b-td>
                  <b-td><b>{{ komponen.nama }}</b></b-td>
                  <b-td><b>{{ komponen.bobot }}</b></b-td>
                  <b-td><b>{{ komponen.skor }}</b></b-td>
                  <b-td><b>{{ komponen.skor2 }}</b></b-td>
                  <b-td><b>{{ komponen.skor_penilaian }}</b></b-td>
                </b-tr>
  
                <template v-for="(subKomponen) in komponen.sub_komponen">
                  <b-tr :key="subKomponen.id">
                    <b-td>{{ komponen.nomor }}.{{ subKomponen.nomor }}</b-td>
                    <b-td>{{ subKomponen.nama }}</b-td>
                    <b-td>{{ subKomponen.bobot }}</b-td>
                    <b-td>{{ subKomponen.skor }}</b-td>
                    <b-td>{{ subKomponen.skor2 }}</b-td>
                    <b-td></b-td>
                  </b-tr>
                </template>
              </template>
  
              <b-tr>
                <b-td colspan="2" class="text-center"><b>NILAI TOTAL</b></b-td>
                <b-td><b>{{ bobotTotal }}</b></b-td>
                <b-td><b>{{ skorTotal }}</b></b-td>
                <b-td><b>{{ skorTotal2 }}</b></b-td>
                <b-td><b>{{ skorTotalPenilaianKomponen }}</b></b-td>
              </b-tr>
              <b-tr class="bg-yellow">
                <b-td colspan="2" class="text-center">
                  <b>NILAI SAKIP</b>
                  <b v-if="skorTotalPenilaianKomponen"></b>
                  <b v-else-if="done2">TAHAP AKHIR</b>
                  <b v-else>TAHAP AWAL</b>
                </b-td>
                <b-td colspan="4" class="text-center">
                  <b>{{ predikatAkhir[0] }} <br>{{ predikatAkhir[1] }}</b>
                </b-td>
              </b-tr>
              <b-tr>
                <b-td colspan="6">{{ predikatAkhir[2] }}</b-td>
              </b-tr>
            </b-tbody>
          </b-table-simple>
        </template>
      </div>
    </b-card>

    <CatatanRekomendasi v-if="done2" :satker-id="filter.satuan_kerja_id" :success="success" class="mt-3" />
  </div>
</template>

<style scoped>
.bg-yellow:hover, .bg-yellow {
  background-color: yellow;
}
</style>