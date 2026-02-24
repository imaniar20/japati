<script>
import axios from 'axios'
import DiagramSasaran from '~/components/global/DiagramSasaran'

export default {
  middleware: ['auth'],

  components: {
    DiagramSasaran,
  },

  async asyncData() {
    const { data: {
      sasaranStrategisRpjmd,
      satkerList,
    }} = await axios.get('/display-makro/cascading')

    satkerList[0].satuan_kerja_nama = 'Pilih satuan kerja'

    return {
      sasaranStrategisRpjmd,
      satkerList,
    }
  },

  data() {
    let satkerId = null

    if (!this.$role.isSuper() && !this.$role.isSetda()) {
      satkerId = this.$store.getters['auth/user'].satuan_kerja_id
    }

    return {
      filter: {
        satuan_kerja_id: satkerId,
      },
      isBusy: false,
      componentKey: 0,
    }
  },

  watch: {
    filter: {
      handler: function () {
        this.doFilter()
      },
      deep: true,
    }
  },

  methods: {
    async doFilter() {
      this.isBusy = true

      const { data: {
        sasaranStrategisRpjmd,
      } } = await axios.get('/display-makro/cascading', {
        params: {
          filter: this.filter,
        }
      })

      this.sasaranStrategisRpjmd = sasaranStrategisRpjmd
      this.isBusy = false
      this.componentKey++
    },
  },
}
</script>

<template>
  <b-card>
    <div>
      <b-row>
        <b-col sm="6" md="4">
          <FilterSatuanKerja v-if="$role.isSuper() || $role.isSetda()" v-model="filter.satuan_kerja_id" :is-setda="$role.isSetda()" />
        </b-col>
      </b-row>
    </div>

    <div v-if="!sasaranStrategisRpjmd.length || isBusy" class="text-center my-4 font-weight-bold">
      <div v-if="isBusy">Memuat data...</div>
      <div v-else>
        <span v-if="($role.isSuper() || $role.isSetda()) && !filter.satuan_kerja_id">Pilih satuan kerja terlebih dahulu</span>
        <span v-else>Tidak ada data</span>
      </div>
    </div>
    <diagram-sasaran v-else :data="sasaranStrategisRpjmd" :satkerList="satkerList" :satkerId="filter.satuan_kerja_id" max-level="2" :key="componentKey" :showSwitchIconCapaian="true" :showSwitchIconEditKinerja="true" />
  </b-card>
</template>
