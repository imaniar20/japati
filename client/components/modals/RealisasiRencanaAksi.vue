<script>
const componentId = 'modal-realisasi-rencana-aksi'
import axios from 'axios';

export default {
  name: componentId,

  data() {
    return {
      id: componentId,
      satuanKerjaId: 0,
      sasaranStrategisPdId: 0,
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
    this.$nuxt.$on(this.showEventId, (satuanKerjaId, sasaranStrategisPdId, iku, sasaran_strategis_satker) => {
      this.satuanKerjaId = satuanKerjaId
      this.sasaranStrategisPdId = sasaranStrategisPdId,
      this.sasaran_strategis_satker = sasaran_strategis_satker
      this.iku = iku
      this.reset()
      this.getData()
      this.$bvModal.show(this.id)
    });

    this.$nuxt.$on(this.hideEventId, () => {
      this.$bvModal.hide(this.id)
    });
  },

  methods: {
    async getData(page = 1) {
      this.isBusy.getData = true

      const { data } = await axios.get('public-display/rencana-aksi', {
        params: {
          satuan_kerja_id: this.satuanKerjaId,
          sasaran_strategis_pd_id: this.sasaranStrategisPdId,
          page,
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
    goToIndikator(indikatorId) {
      this.$bvModal.hide(this.id)
      this.$emit('go-to-indikator', indikatorId)
    },
  }
}
</script>

<template>
  <b-modal :id="id" title="Realisasi Rencana Aksi" size="xl">
    <b-table-simple :aria-busy="isBusy.getData" hover responsive bordered striped>
      <b-thead class="text-center align-middle" head-variant="info">
        <b-tr>
          <b-th class="align-middle" rowspan="2">Sasaran Strategis</b-th>
          <b-th class="align-middle" rowspan="2">Rencana Aksi</b-th>
          <b-th class="align-middle" rowspan="2">Indikator</b-th>
          <b-th class="align-middle" colspan="4">Realisasi</b-th>
          <b-th class="align-middle" rowspan="2">Pengampu</b-th>
        </b-tr>
        <b-tr>
          <b-th style="top:49px" v-for="n of 4" :key="n">TW {{ n }}</b-th>
        </b-tr>
      </b-thead>
      <b-tbody>
        <b-tr class="text-center" v-if="!data.data.length">
          <b-td colspan="8">There are no records to show</b-td>
        </b-tr>
        <template v-else>
          <b-tr v-for="(rencanaAksi, index) in data.data" :key="index">
            <b-td v-if="index == 0" :rowspan="data.data.length">{{ sasaran_strategis_satker }}</b-td>
            <b-td>{{ rencanaAksi.rencana_aksi || '-' }}</b-td>
            <b-td>
              <span class="clickable text-primary" @click="goToIndikator(rencanaAksi.id)">{{ rencanaAksi.indikator }}</span>
            </b-td>

            <b-td v-for="n in 4" :key="n">
              {{ rencanaAksi[`tw${n}_realisasi`] }} {{ rencanaAksi.satuan }}
            </b-td>
            <b-td>
              <template v-if="rencanaAksi.pengampu == 'unit-kerja' && rencanaAksi.struktur_organisasi">
                {{ rencanaAksi.struktur_organisasi.jabatan_nama }} - {{ rencanaAksi.struktur_organisasi.unit_kerja_nama_full }}
              </template>
              <template v-else-if="rencanaAksi.pengampu == 'tim-kerja' && rencanaAksi.tim_kerja">
                {{ rencanaAksi.tim_kerja.nama }} - {{ rencanaAksi.tim_kerja.ketua?.peg_nama }}
              </template>
              <template v-else>
                -
              </template>
            </b-td>
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