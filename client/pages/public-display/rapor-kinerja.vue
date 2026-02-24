<script>
import DiagramRaporKinerja from '~/components/DiagramRaporKinerja.vue'

export default {
  layout: 'guest',
  
  components: {
    DiagramRaporKinerja,
  },

  async asyncData({ params, query }) {
    const triwulan = parseInt(params.triwulan)
    const satuanKerjaId = parseInt(params.satuanKerjaId)
    const tahunKinerja = parseInt(query.tahun_kinerja) || null

    return {
      triwulan,
      satuanKerjaId,
      tahunKinerja,
    }
  }
}
</script>

<template>
  <DiagramRaporKinerja :triwulan="triwulan" :satuanKerjaId="satuanKerjaId" :tahunKinerja="tahunKinerja" />
</template>