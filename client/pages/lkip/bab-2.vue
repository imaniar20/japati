<script>
import { mapGetters } from 'vuex'
import Tabel21 from '~/components/LKIP/Tabel21'
import PerjanjianKinerja from '~/components/LKIP/PerjanjianKinerja'

export default {
  middleware: ['auth'],
  components: {
    Tabel21,
    PerjanjianKinerja,
  },
  data() {
    return {
      filter: {
        satuan_kerja_id: null,
      },
    }
  },

  computed: {
    ...mapGetters({
      user: 'auth/user'
    }),
  },

  created() {
    this.filter.satuan_kerja_id = this.user.satuan_kerja_id
  },
}
</script>

<template>
  <b-card>
    <!-- BAB II -->
    <h3 class="text-center">BAB II</h3>
    <div class="text-center">
      <h5>TABEL 2.1</h5>
      Tujuan, Sasaran, dan Indikator Kinerja Pemerintah Daerah Provinsi Jawa Barat Tahun {{ $helper.getTahunKinerja() }}
    </div>
    <Tabel21 :satuan-kerja-id="filter.satuan_kerja_id" />

    <h5 class="text-center">PERJANJIAN KINERJA</h5>
    <PerjanjianKinerja :satuan-kerja-id="filter.satuan_kerja_id" />
  </b-card>
</template>