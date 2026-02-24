<script>
import DiagramDashboard from '~/components/DiagramDashboard.vue'
import { mapGetters } from 'vuex'

export default {
  middleware: ['auth'],

  components: {
    DiagramDashboard,
  },

  computed: {
    ...mapGetters({
      user: 'auth/user'
    }),
  },
}
</script>

<template>
  <b-card>
    <DiagramDashboard :satker-id="user.satuan_kerja_id" />
  </b-card>
</template>
