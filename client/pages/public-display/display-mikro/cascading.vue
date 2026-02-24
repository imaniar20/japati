<script>
import axios from 'axios'
import DiagramSasaran from '~/components/global/DiagramSasaran'

export default {
  layout: 'guest',
  
  components: {
    DiagramSasaran,
  },

  async asyncData() {
    const { data: satkerList } = await axios.get('/option/satuan-kerja')

    return {
      satkerList,
    }
  },

  data() {
    return {
      data: [],
      filter: {
        satuan_kerja_id: null,
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
    if (this.$route.query.tahun_kinerja) {
      this.$helper.setTahunKinerjaPublic(this.$route.query.tahun_kinerja)
    }

    this.filter.satuan_kerja_id = Number(this.$route.query.satuan_kerja_id) || null
  },

  methods: {
    async getData() {
      if (!this.filter.satuan_kerja_id) {
        this.data = []
        return
      }

      this.isBusy = true

      const { data } = await axios.get('/public-display/display-mikro/cascading', {
        params: {
          ...this.filter,
          show_skp: this.$route.query.showSkp,
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
          <FilterSatuanKerja v-model="filter.satuan_kerja_id" />
        </b-col>
      </b-row>
    </div>

    <div v-if="!data.length || isBusy" class="text-center my-4 font-weight-bold">
      <div v-if="isBusy">Memuat data...</div>
      <div v-else>
        <span v-if="!filter.satuan_kerja_id">Pilih satuan kerja terlebih dahulu</span>
        <span v-else>Tidak ada data</span>
      </div>
    </div>
    <DiagramSasaran v-else :data="data" :satkerList="satkerList" :satkerId="filter.satuan_kerja_id" :key="componentKey" :tahunKinerja="$helper.getTahunKinerjaPublic()" />
  </b-card>
</template>
