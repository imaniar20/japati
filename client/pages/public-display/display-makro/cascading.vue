<script>
import axios from 'axios'
import DiagramSasaran from '~/components/global/DiagramSasaran.vue'

export default {
  layout: 'guest',
  components: {
    DiagramSasaran,
  },
  async asyncData() {
    const { data: sasaranStrategisRpjmd } = await axios.get('/public-display/display-makro/cascading')

    return {
      sasaranStrategisRpjmd,
    }
  },
}
</script>

<template>
  <b-card>
    <div v-if="!sasaranStrategisRpjmd.length" class="text-center my-4 font-weight-bold">
      <div>Tidak ada data</div>
    </div>
    <DiagramSasaran v-else :data="sasaranStrategisRpjmd" max-level="2" :tahunKinerja="$helper.getTahunKinerjaPublic()" />
  </b-card>
</template>