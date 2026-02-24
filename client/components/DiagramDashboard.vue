<script>
import axios from 'axios'
import DiagramCapaianIKU from '~/components/global/DiagramCapaianIKU.vue'

export default {
  middleware: 'auth',

  props: {
    satkerId: {
      type: Number,
      default: null,
    },
    tahunKinerja: {
      type: Number,
      default() {
        return this.$helper.getTahunKinerja()
      },
    },
  },

  components: {
    DiagramCapaianIKU,
  },

  head () {
    return { title: 'Dashboard' }
  },

  data() {
    return {
      isBusy: false,
      data: [],
      showDiagram: true
    }
  },

  mounted() {
    this.getData();
  },

  methods: {
    async getData() {
      this.isBusy = true

      const { data } = await axios.get(`diagram-iku-gubernur/${this.satkerId}`, {
        params: {
          tahun_kinerja: this.tahunKinerja,
        }
      })

      this.data = data
      this.isBusy = false
    },
  }
}
</script>

<template>
  <div>
    <div v-if="isBusy" class="text-center mt-5"><h4>Memuat data...</h4></div>
    <div v-else-if="!data || !data.length" class="text-center mt-5"><h4>Tidak ada data</h4></div>
    <b-row style="margin:0 -5px" v-else>
      <b-col style="padding:0 5px;" class="mb-3" md="6" xl="4" v-for="iku of data" :key="iku.id">
        <b-card class="text-center diagram-card shadow h-100">
          <DiagramCapaianIKU :data="iku" :tahun-kinerja="tahunKinerja" />
        </b-card>
      </b-col>
    </b-row>
  </div>
</template>

<style scoped lang="scss">
  /deep/ .diagram-container{
    -ms-transform: scale(0.65);
    -webkit-transform: scale(0.65);
    transform: scale(.65);
    transform-origin: top center;
    margin-bottom: -280px;
  }
  @media (min-width:500px) {
    /deep/ .diagram-container{
      height: 150%;
      display: flex;
      justify-content: space-between;
      flex-direction: column;
    }
  }
  @media (min-width:991px) and (max-width:1200px){
    /deep/ .diagram-container{
      // -ms-transform: scale(0.8);
      // -webkit-transform: scale(0.8);
      // transform: scale(.8);
      margin-bottom: -250px;
    }
  }
</style>
