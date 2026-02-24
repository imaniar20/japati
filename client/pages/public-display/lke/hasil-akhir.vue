<script>
import axios from 'axios'
import DiagramHasilAkhirLKE from '@/components/DiagramHasilAkhirLKE.vue';

export default {
  layout: 'guest',

  components: {
    DiagramHasilAkhirLKE
  },

  async asyncData({ $helper, query, route }) {
    const tahunKinerja = query.tahun_kinerja
    const satuanKerjaId = Number(query.satuan_kerja_id) || null
    
    if (tahunKinerja) {
      $helper.setTahunKinerjaPublic(tahunKinerja)
    }

    return {
      tahunKinerja,
      satuanKerjaId,
    }
  },

  data() {
    return {
      isBusy: {
        getData: false,
      },
      success: false,
      predikat: ['-', '-'],
      predikat2: ['-', '-'],
      predikatKomponen: ['-', '-'],
      skorTotal: 0,
      skorTotal2: 0,
      skorTotalPenilaianKomponen: 0,
      satuanKerja: null,
      done1: false,
      done2: false,
    }
  },

  computed: {
    predikatAkhir() {
      if (this.skorTotalPenilaianKomponen) {
        return this.predikatKomponen;
      } else if (this.done2) {
        return this.predikat2;
      } else {
        return this.predikat;
      }
    },
    skorAkhir() {
      if (this.skorTotalPenilaianKomponen) {
        return this.skorTotalPenilaianKomponen;
      } else if (this.done2) {
        return this.skorTotal2;
      } else {
        return this.skorTotal;
      }
    },
  },

  mounted() {
    if (this.satuanKerjaId) {
      this.getData()
    }
  },

  methods: {
    async getData() {
      this.isBusy.getData = true

      const { data: { success, predikat, predikat2, predikatKomponen, skorTotal, skorTotal2, skorTotalPenilaianKomponen, satuanKerja, done1, done2 } } = await axios.get('public-display/lke/eviden/hasil-akhir',{
        params: {
          satuan_kerja_id: this.satuanKerjaId,
        }
      });

      this.success = success
      this.predikat = predikat
      this.predikat2 = predikat2
      this.predikatKomponen = predikatKomponen
      this.skorTotal = skorTotal
      this.skorTotal2 = skorTotal2
      this.skorTotalPenilaianKomponen = skorTotalPenilaianKomponen
      this.satuanKerja = satuanKerja
      this.done1 = done1
      this.done2 = done2

      this.isBusy.getData = false
    }
  }
}
</script>

<template>
  <b-card>
    <h4 v-if="!satuanKerjaId" class="text-center py-5">
      URL tidak valid
    </h4>
    <h4 v-else-if="isBusy.getData" class="text-center py-5">
      Loading...
    </h4>
    <h5 v-else-if="!success" class="text-center py-5">Penilaian belum selesai</h5>
    <template v-else>
      <h3 class="text-center">
        Tahun {{ tahunKinerja }}
      </h3>

      <DiagramHasilAkhirLKE :satuanKerjaNama="satuanKerja.satuan_kerja_nama" :predikat="[predikatAkhir[0], predikatAkhir[1]]" :skor="skorAkhir" class="mt-3" />
    </template>
  </b-card>
</template>