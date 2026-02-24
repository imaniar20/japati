<script>
import axios from 'axios'
import DiagramSasaran from '~/components/global/DiagramSasaran'

export default {
  middleware: ['auth'],

  components: {
    DiagramSasaran,
  },

  async asyncData({ store, $role }) {
    const { data: satkerList } = await axios.get('/option/satuan-kerja', {
      params: {
        satuan_kerja_id: !$role.isSuper() ? store.state.auth['user'].satuan_kerja_id : null,
      }
    })

    return {
      satkerList,
    }
  },

  data() {
    let satkerId = null

    if (!this.$role.isSuper() && !this.$role.isSetda()) {
      satkerId = this.$store.getters['auth/user'].satuan_kerja_id
    }

    return {
      data: [],
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
        this.getData()
      },
      deep: true,
    }
  },

  mounted() {
    if (!this.$role.isSuper() && !this.$role.isSetda()) {
      this.getData()
    }
  },

  methods: {
    async getData() {
      this.isBusy = true

      const { data } = await axios.get('/display-mikro/cascading', {
        params: {
          ...this.filter
        }
      })

      this.data = data
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

    <div v-if="!data.length || isBusy" class="text-center my-4 font-weight-bold">
      <div v-if="isBusy">Memuat data...</div>
      <div v-else>
        <span v-if="($role.isSuper() || $role.isSetda()) && !filter.satuan_kerja_id">Pilih satuan kerja terlebih dahulu</span>
        <span v-else>Tidak ada data</span>
      </div>
    </div>
    <DiagramSasaran v-else :data="data" :satkerList="satkerList" :satkerId="filter.satuan_kerja_id" :key="componentKey" :showSwitchIconCapaian="true" :showSwitchIconEditKinerja="true" />
  </b-card>
</template>
