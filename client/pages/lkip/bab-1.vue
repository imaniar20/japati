<script>
import { mapGetters } from 'vuex'
import ArsitekturKinerja from '~/components/LKIP/ArsitekturKinerja'

export default {
  middleware: ['auth'],
  components: {
    ArsitekturKinerja,
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
     <!-- BAB I -->
    <h3 class="text-center">BAB I</h3>
    <h5 class="text-center">ARSITEKTUR KINERJA DAN CASCADING</h5>
    <ArsitekturKinerja :satuan-kerja-id="filter.satuan_kerja_id" />
  </b-card>
</template>