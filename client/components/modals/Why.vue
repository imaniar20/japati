<script>
const componentId = 'modal-why'
import axios from 'axios';

export default {
  name: componentId,

  data() {
    return {
      id: componentId,
      satuanKerjaId: 0,
      sasaranStrategisRpjmdId: 0,
      sasaranStrategisPdId: 0,
      sasaranStrategisId:0,
      programId: 0,
      kegiatanId: 0,
      subKegiatanId:0,

      iku: '',
      showEventId: `show-${componentId}`,
      hideEventId: `hide-${componentId}`,
      data: null,
      isBusy: {
        getData: false,
      },
    }
  },

  mounted() {
    this.$nuxt.$on(this.showEventId, (satuanKerjaId, sasaranStrategisRpjmdId, sasaranStrategisPdId, iku, programId, kegiatanId, subKegiatanId) => {
      this.satuanKerjaId = satuanKerjaId
      this.sasaranStrategisRpjmdId = sasaranStrategisRpjmdId
      this.sasaranStrategisPdId = sasaranStrategisPdId
      this.iku = iku
      this.programId = programId,
      this.kegiatanId = kegiatanId,
      this.subKegiatanId= subKegiatanId
      this.reset()
      this.getData()
      this.$bvModal.show(this.id)
    });

    this.$nuxt.$on(this.hideEventId, () => {
      this.$bvModal.hide(this.id)
    });
  },
  computed:{
    
  },

  methods: {
    async getData() {
      this.isBusy.getData = true

      const { data } = await axios.get('public-display/display-mikro/why', {
        params: {
          satuan_kerja_id: this.satuanKerjaId,
          sasaran_strategis_rpjmd_id: this.sasaranStrategisRpjmdId,
          sasaran_strategis_pd_id: this.sasaranStrategisPdId,
          sasaranStrategisId : this.sasaranStrategisId,
          programId : this.programId,
          kegiatanId : this.kegiatanId,
          subKegiatanId : this.subKegiatanId
        }
      })

      this.data = data
      this.isBusy.getData = false
    },
    reset() {
      this.data = null
    },

    renderedMath(rumus) {
      return this.$katex.renderToString(rumus, {
        throwOnError: false,
      });
    },
    // goToIndikator(indikatorId) {
    //   this.$bvModal.hide(this.id)
    //   this.$emit('go-to-indikator', indikatorId)
    // },
  }
}
</script>

<template>
  <b-modal :id="id" title="W" size="xl">
    <p v-if="this.isBusy.getData">
      Loading....
    </p>
    <p v-else>
      {{  data }}
    </p>
  </b-modal>
</template>

<style scoped>
.clickable {
  cursor: pointer;
}

.clickable:hover {
  text-decoration: underline;
}
</style>