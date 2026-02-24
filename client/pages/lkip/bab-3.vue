<script>
import { mapGetters } from 'vuex'
import Tabel31 from '~/components/LKIP/Tabel31'
import DiagramDashboard from '~/components/DiagramDashboard'
import ProgramInovatif from '~/components/LKIP/ProgramInovatif'
import Tabel32 from '~/components/LKIP/Tabel32'
import PengelolaanDataKinerja from '~/components/LKIP/PengelolaanDataKinerja'

export default {
  middleware: ['auth'],
  components: {
    Tabel31,
    DiagramDashboard,
    ProgramInovatif,
    Tabel32,
    PengelolaanDataKinerja,
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
    <!-- BAB III -->
    <h3 class="text-center">BAB III</h3>
    <div class="text-center">
      <h5>TABEL 3.1</h5>
      AKUNTABILITAS KINERJA
    </div>
    <Tabel31 :satuan-kerja-id="filter.satuan_kerja_id" />
    <hr>

    <!-- <h5 class="text-center">PROGRAM INOVATIF</h5>
    <ProgramInovatif :satuan-kerja-id="filter.satuan_kerja_id" />
    <hr> -->

    <div class="text-center">
      <h5>TABEL 3.2</h5>
      AKUNTABILITAS KEUANGAN
    </div>
    <Tabel32 :satuan-kerja-id="filter.satuan_kerja_id" />
    <hr>

    <h5 class="text-center">PENGELOLAAN DATA KINERJA</h5>
    <PengelolaanDataKinerja :satuan-kerja-id="filter.satuan_kerja_id"/>
    <hr>
    
    <h5 class="text-center">DIAGRAM DASHBORD</h5>
    <DiagramDashboard :satker-id="filter.satuan_kerja_id" />
  </b-card>
</template>

<style scoped>
  hr{
    margin: 25px 0;
  }
</style>
