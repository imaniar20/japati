<script>
import DiagramDashboard from '~/components/DiagramDashboard'

export default {
  layout: 'guest',
  components: {
    DiagramDashboard,
  }
}
</script>

<template>
  <b-card>
    <DiagramDashboard :tahun-kinerja="$helper.getTahunKinerjaPublic()" />
  </b-card>
</template>
