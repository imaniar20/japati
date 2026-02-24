<script>
import DiagramDashboard from '~/components/DiagramDashboard.vue'

export default {
  layout: 'guest',

  components: {
    DiagramDashboard,
  },

  data() {
    return {
      filter: {
        satuan_kerja_id: null
      },
    }
  },

  mounted() {
    if (this.$route.query.tahun_kinerja) {
      this.$helper.setTahunKinerjaPublic(this.$route.query.tahun_kinerja)
    }

    this.filter.satuan_kerja_id = Number(this.$route.query.satuan_kerja_id) || 1030
  },
}
</script>

<template>
  <b-card>
    <DiagramDashboard v-if="filter.satuan_kerja_id" :satker-id="filter.satuan_kerja_id" />
  </b-card>
</template>
