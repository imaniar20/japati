<script>
import axios from 'axios'
export default {
  data() {
    return {
      isBusy: {
        getData: false,
      },
      type: null,
      solusi: null,
      masalah: null,
      label: null,
      childrenLabel: null,
      childrenKinerjaLabel: null,
    }
  },

  methods: {
    async showModal(type, id) {
      this.type = type
      this.data = null // reset

      switch (type) {
        case 'kinerja-program':
          this.label = 'Program'
          break;
        case 'kinerja-kegiatan':
          this.label = 'Kegiatan'
          this.childrenLabel = 'Sub Kegiatan'
          this.childrenKinerjaLabel = 'Kinerja Sub Kegiatan'
          break;
        case 'kinerja-sub-kegiatan':
          this.label = 'Sub Kegiatan'
          this.childrenLabel = 'Langkah Aksi'
          this.childrenKinerjaLabel = 'Kinerja Langkah Aksi'
          break;
      
        default:
          this.childrenKinerjaLabel = 'ERROR!'
          break;
      }

      this.$bvModal.show('solusi-masalah-modal')

      try {
        await this.getData(type, id)
      } catch (error) {
        this.$bvModal.hide('solusi-masalah-modal')
        console.error(error);
      }
    },
    async getData(type, id) {
      this.isBusy.getData = true

      try {
        const { data: { solusi, masalah } } = await axios.get('/solusi-masalah', {
          params: {
            type,
            id,
          }
        })
  
        this.solusi = solusi
        this.masalah = masalah
      } catch (error) {
        throw error
      } finally {
        this.isBusy.getData = false
      }
    }
  }
}
</script>

<template>
  <b-modal id="solusi-masalah-modal" size="xl" title="Perbaikan Kinerja" hide-footer>
    <div v-if="isBusy.getData" class="text-center my-5">
      <b-spinner></b-spinner>
    </div>
    <div v-else-if="!isBusy.getData && solusi && masalah" >
      <b-table-simple responsive bordered striped hover fixed>
        <b-thead>
          <b-tr class="text-center">
            <b-th>Masalah Tahun lalu</b-th>
            <b-th>Solusi Tahun ini</b-th>
          </b-tr>
        </b-thead>
        <b-tbody>
          <b-tr>
            <b-td>
              <b>Sasaran {{ label }}</b> <br>
              {{ masalah.sasaran }}
            </b-td>
            <b-td>
              <b>Sasaran {{ label }}</b> <br>
              {{ solusi.sasaran }}
            </b-td>
          </b-tr>
          <b-tr>
            <b-td>
              <b>Indikator {{ label }}</b> <br>
              {{ masalah.indikator }}
            </b-td>
            <b-td>
              <b>Indikator {{ label }}</b> <br>
              {{ solusi.indikator }}
            </b-td>
          </b-tr>
          <b-tr>
            <b-td>
              <b>Nama {{ label }}</b> <br>
              {{ masalah.nama }}
            </b-td>
            <b-td>
              <b>Nama {{ label }}</b> <br>
              {{ solusi.nama }}
            </b-td>
          </b-tr>
          <b-tr>
            <b-td>
              <b>Pengampu</b> <br>
              {{ masalah.pengampu || '-' }}
            </b-td>
            <b-td>
              <b>Pengampu</b> <br>
              {{ solusi.pengampu || '-' }}
            </b-td>
          </b-tr>
          <b-tr>
            <b-td>
              <b>Target</b>: {{ masalah.target }} <br>
              <b>Realisasi</b>: {{ masalah.realisasi }} <br>
              <b>Capaian</b>: {{ masalah.capaian }}%
            </b-td>
            <b-td>
              <b>Target</b>: {{ solusi.target }} <br>
              <b>Realisasi</b>: {{ solusi.realisasi }} <br>
              <b>Capaian</b>: {{ solusi.capaian }}%
            </b-td>
          </b-tr>
        </b-tbody>
      </b-table-simple>

      <b-table-simple responsive bordered hover class="mt-4">
        <b-thead>
          <b-tr class="text-center">
            <b-th>No</b-th>
            <b-th>Diagnosa CSF Gagal</b-th>
          </b-tr>
        </b-thead>
        <b-tbody>
          <b-tr v-if="!masalah.kinerja_tidak_tercapai.length">
            <b-td colspan="2" class="text-center">Tidak ada data</b-td>
          </b-tr>
          <b-tr v-else v-for="(kinerja, index) in masalah.kinerja_tidak_tercapai" :key="index">
            <b-th class="text-center">{{ index + 1 }}</b-th>
            <b-td>{{ kinerja.catatan }}</b-td>
          </b-tr>
        </b-tbody>
      </b-table-simple>

      <div v-if="type == 'kinerja-kegiatan' || type == 'kinerja-sub-kegiatan'">
        <h4 class="text-center">{{ childrenKinerjaLabel }}</h4>
        <b-table-simple responsive bordered striped hover fixed>
          <b-thead>
            <b-tr class="text-center">
              <b-th>Masalah Tahun lalu</b-th>
              <b-th>Solusi Tahun ini</b-th>
            </b-tr>
          </b-thead>
          <b-tbody>
            <b-tr v-for="n in Math.max(masalah.children.length, solusi.children.length)" :key="n">
              <b-td>
                <template v-if="type != 'kinerja-sub-kegiatan'">
                  <div class="mb-1"><b>Sasaran {{ childrenLabel }}</b>: {{ masalah.children[n - 1]?.sasaran || '-' }}</div>
                  <div class="mb-1"><b>Indikator {{ childrenLabel }}</b>: {{ masalah.children[n - 1]?.indikator || '-' }}</div>
                </template>

                <div class="mb-1"><b>Nama {{ childrenLabel }}</b>: {{ masalah.children[n - 1]?.nama || '-' }}</div>
                <div class="mb-1"><b>Target</b>: {{ masalah.children[n - 1]?.target || '-' }}</div>
                <div class="mb-1"><b>Realisasi</b>: {{ masalah.children[n - 1]?.realisasi || '-' }}</div>
                <div class="mb-1"><b>Capaian</b>: {{ masalah.children[n - 1]?.capaian || '-' }}</div>
                <div class="mb-1"><b>Penyebab Kegagalan</b>: {{ masalah.children[n - 1]?.penyebab_kegagalan || '-' }}</div>
                <div class="mb-1">
                  <b>Diagnosa CSF Gagal:</b>
                  <template v-if="!masalah.children[n - 1]?.kinerja_tidak_tercapai.length">-</template>
                  <ul v-else>
                    <li v-for="(kinerja, index) in masalah.children[n - 1]?.kinerja_tidak_tercapai" :key="index" style="white-space: break-spaces;">
                      {{ kinerja.catatan }}
                    </li>
                  </ul>
                </div>
              </b-td>
              <b-td>
                <template v-if="type != 'kinerja-sub-kegiatan'">
                  <div class="mb-1"><b>Sasaran {{ childrenLabel }}</b>: {{ solusi.children[n - 1]?.sasaran || '-' }}</div>
                  <div class="mb-1"><b>Indikator {{ childrenLabel }}</b>: {{ solusi.children[n - 1]?.indikator || '-' }}</div>
                </template>

                <div class="mb-1"><b>Nama {{ childrenLabel }}</b>: {{ solusi.children[n - 1]?.nama || '-' }}</div>
                <div class="mb-1"><b>Target</b>: {{ solusi.children[n - 1]?.target || '-' }}</div>
                <div class="mb-1"><b>Realisasi</b>: {{ solusi.children[n - 1]?.realisasi || '-' }}</div>
                <div class="mb-1"><b>Capaian</b>: {{ solusi.children[n - 1]?.capaian || '-' }}</div>
              </b-td>
            </b-tr>
          </b-tbody>
        </b-table-simple>
      </div>
    </div>
    <b-alert v-else variant="danger">Data tidak ditemukan</b-alert>
  </b-modal>
</template>