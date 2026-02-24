<script>
import axios from 'axios'
import DiagramSasaran from '~/components/global/DiagramSasaran'

export default {
  components: {
    DiagramSasaran,
  },
  props: {
    satuanKerjaId: {
      required: true,
    }
  },

  data() {
    return {
      isBusy: false,
      data: [],
    }
  },

  mounted() {
    this.getData()
  },

  methods: {
    async getData() {
      this.isBusy = true

      const { data } = await axios.get('/lkip/arsitektur-kinerja', {
        params: {
          satuan_kerja_id: this.satuanKerjaId
        }
      })
      
      this.data = data
      this.isBusy = false
    }
  }
}
</script>

<template>
  <div>
    <div class="text-center" v-if="isBusy || !data.length">
      <b-spinner v-if="isBusy" />
      <h4 v-else>Tidak ada data</h4>
    </div>
    <DiagramSasaran v-else :data="data" />
  </div>
</template>