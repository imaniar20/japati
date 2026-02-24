<script>
import axios from 'axios'
import CatatanRekomendasi from '@/components/CatatanRekomendasi.vue'
import { mapGetters } from 'vuex'
import Swal from 'sweetalert2';

export default {
  middleware: ['auth'],

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
     async printReport(){
      let satuanKerjaId = null
      if(this.user.satuan_kerja_id){
        satuanKerjaId = this.user.satuan_kerja_id
      }
      else{
        satuanKerjaId = this.filter.satuan_kerja_id
      }
      let tahunKinerja = this.$helper.getTahunKinerja();
      let path =
        process.env.apiUrl +
        "/laporan-lhe/export?satuan_kerja_id=" +
        satuanKerjaId +
        "&tahun_kinerja=" +
        tahunKinerja + "&format=docx";
      window.open(path, "_blank");
    },
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
         <FilterSatuanKerja v-if="$role.isValidatorLKE() || $role.isValidatorPleno()" v-model="filter.satuan_kerja_id" />
      </div>

      <b-button v-if="filter.satuan_kerja_id || this.user.satuan_kerja_id"   variant="success" @click="printReport">Cetak LHE</b-button>
      <div v-if="isBusy.getData" class="text-center my-5">
        <b-spinner></b-spinner>
      </div>

      <div v-else-if="filter.satuan_kerja_id">
        <div v-if="!success" class="text-center my-5">
          <!-- <h5>Penilaian belum selesai</h5> -->
        </div>

        <template v-else>
        </template>
      </div>
    </b-card>

    <!-- <CatatanRekomendasi v-if="done2" :satker-id="filter.satuan_kerja_id" :success="success" class="mt-3" /> -->
  </div>
</template>

<style scoped>
.bg-yellow:hover, .bg-yellow {
  background-color: yellow;
}
</style>