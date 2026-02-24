<script>
import axios from 'axios';
const componentId = 'modal-berbagi-peran'

export default {
  name: componentId,

  data() {
    return {
      id: componentId,
      sasaranStrategisPdId: 0,
      kinerjaProgramId: 0,
      kinerjaKegiatanId: 0,
      kinerjaSubKegiatanId: 0,
      iku: '',
      showEventId: `show-${componentId}`,
      hideEventId: `hide-${componentId}`,
      data: null,
      kinerjaName: null,
      isBusy: {
        getData: false,
      },
    }
  },

  mounted() {
    this.$nuxt.$on(this.showEventId, (kinerjaName, sasaranStrategisPdId, kinerjaProgramId, kinerjaKegiatanId, kinerjaSubKegiatanId) => {
      this.sasaranStrategisPdId = sasaranStrategisPdId
      this.kinerjaProgramId = kinerjaProgramId,
      this.kinerjaKegiatanId = kinerjaKegiatanId,
      this.kinerjaSubKegiatanId = kinerjaSubKegiatanId
      this.kinerjaName = kinerjaName
      this.reset()
      this.getData()
      this.$bvModal.show(this.id)
    });

    this.$nuxt.$on(this.hideEventId, () => {
      this.$bvModal.hide(this.id)
    });
  },
  computed: {

  },

  methods: {
    async getData() {
      this.isBusy.getData = true
      this.data = null // Reset data on new fetch

      try {
        const { data } = await axios.get('public-display/berbagi-peran', {
          params: {
            sasaran_strategis_pd_id: this.sasaranStrategisPdId,
            // --- FIX: Use the correct property names ---
            kinerja_program_id: this.kinerjaProgramId,
            kinerja_kegiatan_id: this.kinerjaKegiatanId,
            kinerja_sub_kegiatan_id: this.kinerjaSubKegiatanId
          }
        })
        this.data = data
      } catch (error) {
        console.error("Error fetching data: ", error)
        // You could set an error message here
        this.data = null // Ensure data is null on error
      } finally {
        this.isBusy.getData = false
      }
    },
    reset() {
      this.data = null
    },

    renderedMath(rumus) {
      return this.$katex.renderToString(rumus, {
        throwOnError: false,
      });
    },
  }
}
</script>

<template>
  <b-modal :id="id" :title="`Crosscutting ${kinerjaName}`" size="xl" centered scrollable>
    
    <div v-if="isBusy.getData" class="text-center my-5">
      <b-spinner label="Loading..."></b-spinner>
      <p class="mt-2">Memuat data...</p>
    </div>

    <div v-else-if="data">
      <b-row>
        
        <b-col md="4">
          <b-card header-tag="header" class="mb-3 h-100">
            <template #header>
              <h6 class="mb-0">
                <b-icon-people-fill variant="primary"></b-icon-people-fill>
                Peran Internal
              </h6>
            </template>
            
            <b-list-group v-if="data.kinerjaInternal && data.kinerjaInternal.length > 0" flush>
              <b-list-group-item v-for="(item, index) in data.kinerjaInternal" :key="`internal-${index}`" class="px-0">
                <div v-if="item.nama" class="font-weight-bold">{{ item.peg_nama || item.nama || 'Nama Tidak Tersedia' }}</div>
                <div v-if="item.jabatan_nama" class="font-weight-bold">{{ item.jabatan_nama || 'Jabatan Tidak Ditentukan' }}</div>
              </b-list-group-item>
            </b-list-group>
            
            <div v-else class="text-center text-muted">
              <p>Tidak ada data peran internal.</p>
            </div>
          </b-card>
        </b-col>

        <b-col md="4">
          <b-card header-tag="header" class="mb-3 h-100">
            <template #header>
              <h6 class="mb-0">
                <b-icon-diagram3-fill variant="success"></b-icon-diagram3-fill>
                Peran Lintas Sektor
              </h6>
            </template>
            
            <b-list-group v-if="data.kinerjaCrossCutting && data.kinerjaCrossCutting.length > 0" flush>
              <b-list-group-item v-for="(item, index) in data.kinerjaCrossCutting" :key="`cross-${index}`" class="px-0">
                <div v-if="item.nama" class="font-weight-bold">{{ item.peg_nama || item.nama || 'Nama Tidak Tersedia' }}</div>
                <div v-if="item.jabatan_nama" class="text-muted">{{ item.jabatan_nama || 'Jabatan Tidak Ditentukan' }}</div>
                <small v-if="item.satuan_kerja_nama" class="text-muted">{{ item.satuan_kerja_nama || 'Satuan Kerja Tidak Ditentukan' }}</small>
              </b-list-group-item>
            </b-list-group>
            
            <div v-else class="text-center text-muted">
              <p>Tidak ada data peran lintas sektor.</p>
            </div>
          </b-card>
        </b-col>

        <b-col md="4">
          <b-card header-tag="header" class="mb-3 h-100">
            <template #header>
              <h6 class="mb-0">
                <b-icon-building variant="info"></b-icon-building>
                Peran Eksternal
              </h6>
            </template>
            
            <b-list-group v-if="data.kinerjaExternal && data.kinerjaExternal.length > 0" flush>
              <b-list-group-item v-for="(item, index) in data.kinerjaExternal" :key="`external-${index}`" class="px-0">
                <!-- <div class="font-weight-bold">{{ item.peg_nama || 'Personel Tidak Ditentukan' }}</div> -->
                <div v-if="item.nama" class="text-info">{{ item.nama }}</div>
                <div v-if="item.jabatan_nama" class="text-muted">{{ item.jabatan_nama }}</div>
                
                <!-- <small v-if="!item.peg_nama && !item.nama && !item.jabatan_nama" class="text-muted">
                  Data tidak lengkap
                </small> -->
              </b-list-group-item>
            </b-list-group>

            <div v-else class="text-center text-muted">
              <p>Tidak ada data peran eksternal.</p>
            </div>
          </b-card>
        </b-col>

      </b-row>
    </div>

    <div v-else>
      <p class="text-center text-muted">Data tidak ditemukan atau terjadi kesalahan.</p>
    </div>

    <template #modal-footer>
      <b-button variant="secondary" @click="$bvModal.hide(id)">Tutup</b-button>
    </template>
  </b-modal>
</template>

<style scoped>
.clickable {
  cursor: pointer;
}

.clickable:hover {
  text-decoration: underline;
}

/* Optional: Ensure cards in a row have the same height */
.card {
  display: flex;
  flex-direction: column;
}
</style>