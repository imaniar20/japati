<script>
const componentId = 'modal-definisi-operasional'
import axios from 'axios';

export default {
  name: componentId,

  data() {
    return {
      id: componentId,
      satuanKerjaId: 0,
      sasaranStrategisRpjmdId: 0,
      sasaranStrategisId:0,
      programId: 0,
      kegiatanId: 0,
      subKegiatanId:0,

      iku: '',
      showEventId: `show-${componentId}`,
      hideEventId: `hide-${componentId}`,
      data: {
        data: [],
        current_page: 1,
        total: 0,
        per_page: 20,
      },
      isBusy: {
        getData: false,
      },
    }
  },

  mounted() {
    this.$nuxt.$on(this.showEventId, (satuanKerjaId, sasaranStrategisRpjmdId, iku, programId, kegiatanId, subKegiatanId) => {
      this.satuanKerjaId = satuanKerjaId
      this.sasaranStrategisRpjmdId = sasaranStrategisRpjmdId
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
    async getData(page = 1) {
      this.isBusy.getData = true

      const { data } = await axios.get('public-display/definisi-operasional', {
        params: {
          satuan_kerja_id: this.satuanKerjaId,
          sasaran_strategis_rpjmd_id: this.sasaranStrategisRpjmdId,
          page,
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
      this.data = {
        data: [],
        current_page: 1,
        total: 0,
        per_page: 20,
      }
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
  <b-modal :id="id" title="Definisi Operasional" size="xl">
    <b-table-simple :aria-busy="isBusy.getData" hover responsive bordered striped>
      <b-thead class="text-center align-middle" head-variant="info">
        <b-tr>
          <b-th class="align-middle">Narasi</b-th>
          <b-th class="align-middle">Rumus</b-th>
          <b-th class="align-middle">Keterangan</b-th>
          <b-th class="align-middle">Sumber</b-th>
        </b-tr>
      
      </b-thead>
      <b-tbody>
        <b-tr class="text-center" v-if="!data.data.length">
          <b-td colspan="8">There are no records to show</b-td>
        </b-tr>
        <template v-else>
          <b-tr v-for="(definsiOperasional, index) in data.data" :key="index">
            <b-td>{{ definsiOperasional.do_narasi || '-'  }}</b-td>
            <b-td><img :src="definsiOperasional.do_rumus"  width="400" height="200" class="mb-2 mt-2 rounded mx-auto d-block" alt="Gambar Rumus"></b-td>
            <b-td>{{ definsiOperasional.do_keterangan || '-'  }}</b-td>
            <b-td>{{ definsiOperasional.do_sumber || '-'  }}</b-td>
          
          </b-tr>
        </template>
      </b-tbody>
    </b-table-simple>

    <div>
      <b-pagination
        v-model="data.current_page"
        :total-rows="data.total"
        :per-page="data.per_page"
        @change="getData($event)"
      >
        <template #page="{ page, active }">
          <i class="fa fa-spinner fa-pulse fa-fw" v-if="isBusy.getData && active"></i>
          <template v-else>{{ page }}</template>
        </template>
      </b-pagination>
    </div>
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