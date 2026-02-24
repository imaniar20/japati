<script>
import axios from 'axios'
import { mapGetters } from 'vuex'
import CatatanRekomendasi from '@/components/CatatanRekomendasi.vue'
export default {
  components: { CatatanRekomendasi },
  data() {
    return {
      filter: {
        satuan_kerja_id: this.$store.getters['auth/user'].satuan_kerja_id,
      },
      isBusy: {
        getData: false,
      },
      komponenList: [],
      bobotTotal: 0,
      skorTotalBobot:0,
      skorTotalSelftAsessment: 0,
      skorTotalPerbaikan: 0,
      skorTotalPenilaianAwal: 0,
      skorTotalPenilaianAkhir: 0,
      skorTotalPleno: 0,
      predikat: ['-', '-', '-'],
      predikat2: ['-', '-', '-'],
      done2:true,
      success: true
    }
  },

  computed: {
    ...mapGetters({
      user: 'auth/user'
    }),
    predikatAkhir() {
      if (this.skorTotal2) {
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

  mounted() {
    if (this.filter.satuan_kerja_id) {
      this.getData()
    }
  },

  methods: {
    async getData() {
      this.isBusy.getData = true

      const {  data } = await axios.get('/lke/penilaian/hasil-akhir', {
        params: {
          satuan_kerja_id: this.filter.satuan_kerja_id,
        }
      })

      console.log(data)

      this.komponenList = data
      this.bobotTotal = data.bobot
      this.skorSelfAssesment = data.skorSelfAssesment
      this.skorPerbaikan = data.skorPerbaikan
      this.skorPenilaianAwal = data.skorPenilaianAwal
      this.skorPenilaianAkhir = data.skorPenilaianAkhir

      this.skorTotalSelftAsessment = this.komponenList.reduce((total, komponen) => {
        return total + (komponen.skorSelfAssesment || 0);
      }, 0);

      this.skorTotalPerbaikan = this.komponenList.reduce((total, komponen) => {
        return total + (komponen.skorPerbaikan || 0);
      }, 0);

      this.skorTotalPenilaianAwal = this.komponenList.reduce((total, komponen) => {
        return total + (komponen.skorPenilaianAwal || 0);
      }, 0);

      this.skorTotalPenilaianAkhir = this.komponenList.reduce((total, komponen) => {
        return total + (komponen.skorPenilaianAkhir || 0);
      }, 0);

      this.skorTotalPleno = this.komponenList.reduce((total, komponen) => {
        return total + (komponen.skorPleno || 0);
      }, 0);

      this.skorTotalBobot = this.komponenList.reduce((total, komponen) => {
        return total + (komponen.bobot || 0);
      }, 0);

      this.isBusy.getData = false
    },
    getPredikat(skorTotal) {
      if (skorTotal >= 90) {
        return [
          'AA',
          'Sangat Memuaskan',
          'Telah terwujud Good Governance. Seluruh kinerja dikelola dengan sangat memuaskan di seluruh unit kerja. Telah terbentuk pemerintah yang yang dinamis, adaptif, dan efisien (Reform). Pengukuran kinerja telah dilakukan sampai ke level individu.',
        ];
      } else if (skorTotal >= 80) {
        return [
          'A',
          'Memuaskan',
          'Terdapat gambaran bahwa instansi pemerintah/unit kerja dapat memimpin perubahan dalam mewujudkan pemerintahan berorientasi hasil, karena pengukuran kinerja telah dilakukan sampai ke level eselon 4/Pengawas/Subkoordinator.',
        ];
      } else if (skorTotal >= 70) {
        return [
          'BB',
          'Sangat Baik',
          'Terdapat gambaran bahwa AKIP sangat baik pada 2/3 unit kerja, baik itu unit kerja utama, maupun unit kerja pendukung. Akuntabilitas yang sangat baik ditandai dengan mulai memiliki sistem manajemen kinerja yang andal dan terwujudnya efisiensi penggunaan anggaran dalam mencapai kinerja, berbasis teknologi informasi, serta pengukuran 3/koordinator. kinerja telah dilakukan sampai ke level eselon',
        ];
      } else if (skorTotal >= 60) {
        return [
          'B',
          'Baik',
          'Terdapat gambaran bahwa AKIP sudah baik pada 1/3 unit kerja, khususnya pada unit kerja utama. Terlihat masih perlu adanya sedikit perbaikan pada unit kerja, serta komitmen dalam manajemen kinerja. Pengukuran kinerja baru dilaksanakan sampai dengan level eselon 2/unit kerja.',
        ];
      } else if (skorTotal >= 50) {
        return [
          'CC',
          'Cukup (Memadai)',
          'Terdapat gambaran bahwa AKIP cukup baik. Namun demikian, masih perlu banyak perbaikan walaupun tidak mendasar khususnya akuntabilitas kinerja pada unit kerja.',
        ];
      } else if (skorTotal >= 30) {
        return [
          'C',
          'Kurang',
          'Sistem dan tatanan dalam AKIP kurang dapat diandalkan. Belum terimplementasi sistem manajemen kinerja sehingga masih perlu banyak perbaikan mendasar di level pusat.',
        ];
      } else {
        return [
          'D',
          'Sangat Kurang',
          'Sistem dan tatanan dalam AKIP sama sekali tidak dapat diandalkan. Sama sekali belum terdapat penerapan manajemen kinerja sehingga masih perlu banyak mendasar, khususnya dalam implementasi SAKIP.perbaikan/perubahan yang sifatnya sangat',
        ];
      }
    }
  }
}
</script>

<template>
  <div> <b-card>
    <div v-if="$role.isValidatorLKE()">
      <FilterSatuanKerja v-model="filter.satuan_kerja_id" :selectProps="{clearable: false}" :satuanKerjaIds="user.lke_penilaian_satuan_kerja_ids" />
    </div>

    <b-table-simple v-if="komponenList.length && !isBusy.getData" responsive bordered hover>
      <b-thead>
        <b-tr>
          <b-th class="align-middle">No</b-th>
          <b-th class="align-middle">Komponen/Sub Komponen</b-th>
          <b-th class="align-middle">Bobot</b-th>
          <b-th class="align-middle">Self Asessment</b-th>
          <b-th class="align-middle">Penilaian Awal</b-th>
          <b-th class="align-middle">Perbaikan</b-th>
          <b-th class="align-middle">Penilaian Akhir</b-th>
          <b-th class="align-middle">Pleno</b-th>
        </b-tr>
      </b-thead>
      <b-tbody>
        <template v-for="(komponen) in komponenList">
          <b-tr :key="komponen.id">
            <b-td><b>{{ komponen.nomor }}</b></b-td>
            <b-td><b>{{ komponen.nama }}</b></b-td>
            <b-td><b>{{ komponen.bobot }}</b></b-td>
            <b-td><b>{{ komponen.skorSelfAssesment }}</b></b-td>
            <b-td><b>{{ komponen.skorPenilaianAwal }}</b></b-td>
            <b-td><b>{{ komponen.skorPerbaikan }}</b></b-td>
            <b-td><b>{{ komponen.skorPenilaianAkhir }}</b></b-td>
            <b-td><b>{{ komponen.skorPleno }}</b></b-td>
          </b-tr>

          <template v-for="(subKomponen) in komponen.subKomponen">
            <b-tr :key="subKomponen.id">
              <b-td>{{ komponen.nomor }}.{{ subKomponen.nomor }}</b-td>
              <b-td>{{ subKomponen.nama }}</b-td>
              <b-td>{{ subKomponen.bobot }}</b-td>
              <b-td>{{ subKomponen.skorSelfAssesmentSub }}</b-td>
              <b-td>{{ subKomponen.skorPenilaianAwalSub }}</b-td>
              <b-td>{{ subKomponen.skorPerbaikanSub }}</b-td>
              <b-td>{{ subKomponen.skorPenilaianAkhirSub }}</b-td>
              <b-td></b-td>
            </b-tr>
          </template>
          
        </template>

        <b-tr>
          <b-td colspan="2" class="text-center"><b>NILAI TOTAL</b></b-td>
          <b-td><b>{{ skorTotalBobot }}</b></b-td>
          <b-td><b>{{ skorTotalSelftAsessment }}</b></b-td>
          <b-td><b>{{ skorTotalPenilaianAwal }}</b></b-td>
          <b-td><b>{{ skorTotalPerbaikan }}</b></b-td>
          <b-td><b>{{ skorTotalPenilaianAkhir }}</b></b-td>
          <b-td><b>{{ skorTotalPleno }}</b></b-td>
         
          
        </b-tr>
        <b-tr class="bg-yellow">
          <b-td colspan="2" class="text-center">
            <b>NILAI SAKIP</b>
            <b v-if="skorTotalPleno > 0">HASIL PLENO</b>
            <b v-else-if="skorTotalPenilaianAkhir">HASIL PENILAIAN AKHIR</b>
            <b v-else-if="skorTotalPerbaikan">HASIL PERBAIKAN</b>
            <b v-else-if="skorTotalPenilaianAwal">HASIL PENILAIAN AWAL</b>
            <b v-else-if="skorTotalSelftAsessment">HASIL SELF ASSESMENT</b>
            <b v-else>Belum Ada Penilaian</b>
          </b-td>
         <b-td colspan="6" class="text-center">
          <b v-if="skorTotalPleno > 0">
              {{ getPredikat(skorTotalPleno)[0] }} 
              <br> {{ getPredikat(skorTotalPleno)[1] }}  
              <br>{{getPredikat(skorTotalPleno)[2] }}
          </b>
            <b v-else-if="skorTotalPenilaianAkhir">
              {{ getPredikat(skorTotalPenilaianAkhir)[0] }} 
              <br> {{ getPredikat(skorTotalPenilaianAkhir)[1] }}  
              <br>{{getPredikat(skorTotalPenilaianAkhir)[2] }}
            </b>
            <b v-else-if="skorTotalPerbaikan">
              {{ getPredikat(skorTotalPerbaikan)[0] }} 
              <br> {{ getPredikat(skorTotalPerbaikan)[1] }}  
              <br>{{getPredikat(skorTotalPerbaikan)[2] }}
            </b>
            <b v-else-if="skorTotalPenilaianAwal">
              {{ getPredikat(skorTotalPenilaianAwal)[0] }} 
              <br> {{ getPredikat(skorTotalPenilaianAwal)[1] }}  
              <br>{{getPredikat(skorTotalPenilaianAwal)[2] }}
            </b>
            <b v-else-if="skorTotalSelftAsessment">
              {{ getPredikat(skorTotalSelftAsessment)[0] }} 
              <br> {{ getPredikat(skorTotalSelftAsessment)[1] }}  
              <br>{{getPredikat(skorTotalSelftAsessment)[2] }}
            </b>
            <b v-else>Belum Ada Penilaian</b>
            <b></b>
          </b-td> 
        </b-tr>
        <!-- <b-tr>
          <b-td colspan="5">{{ predikatAkhir[2] }}</b-td>
        </b-tr> -->
      </b-tbody>
    </b-table-simple>

     
  </b-card>

  <CatatanRekomendasi  :satker-id="filter.satuan_kerja_id" :success="komponenList.length > 0" class="mt-3" />
</div>
 
 
</template>

<style scoped>
.bg-yellow:hover, .bg-yellow {
  background-color: yellow;
}
</style>