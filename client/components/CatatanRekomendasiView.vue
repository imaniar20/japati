<script>
import axios from 'axios'

export default {
  middleware: ['auth'],

  props: {
    satkerId: {
      type: Number,
      default: null,
    },
    success: {
      type: Boolean,
      default: null,
    }
  },

  data() {
    return {
      data: {
        catatan: [],
        rekomendasi: []
      },
      isBusy: {
        getData: false,
      },
      table: {
        fields_catatan: [
          { key: 'no', label: 'No' },
          { key: 'kriteria', label: 'Kriteria' },
          { key: 'catatan', label: 'Catatan' },
        ],
        fields_rekomendasi: [
          { key: 'no', label: 'No' },
          { key: 'rencana_aksi', label: 'Rekomendasi Rencana Aksi dan Tindak lanjut PD' },
          { key: 'berita_acara', label: 'Berita Acara Hasil Monev' },
          { key: 'sesuai', label: 'Hasil Monev' },
        ]
      }
    }
  },

  mounted() {
    this.getData()
  },

  methods: {
    async getData() {
      this.isBusy.getData = true
    
      try {
        const { data: { data: { catatan, rekomendasi } } } = await axios.get('/lke/catatan-rekomendasi', {
          params: {
            satuan_kerja_id: this.satkerId,
          },
        })

        this.data.catatan = catatan || []
        this.data.rekomendasi = rekomendasi || []
      } catch (error) {
        alert('Gagal mengambil data')
      } finally {
        this.isBusy.getData = false
      }
    }
  }
}
</script>

<template>
  <b-card v-if="success" title="Catatan & Rekomendasi">
    <div class="text-center">
      <h4>Catatan</h4>
    </div>

    <b-table responsive hover bordered :fields="table.fields_catatan" :items="data.catatan" :busy="isBusy.getData" show-empty head-variant="info">
      <template #cell(no)="row">
        <div class="text-center font-weight-bold">{{ row.index + 1 }}</div>
      </template>
      <template #cell(catatan)="row">
        <div style="white-space: break-spaces;">{{ row.value }}</div>
      </template>
    </b-table>
    <hr class="mt-5">
    <div class="text-center">
      <h4>Monev LHE Tahun {{ $helper.getTahunKinerja() - 1 }}</h4>
    </div>

    <b-table responsive hover bordered :fields="table.fields_rekomendasi" :items="data.rekomendasi" :busy="isBusy.getData" show-empty head-variant="info">
      <template #cell(no)="row">
        <div class="text-center font-weight-bold">{{ row.index + 1 }}</div>
      </template>
      <template #cell(rekomendasi)="row">
        <div style="white-space: break-spaces;">{{ row.value }}</div>
      </template>
      <template #cell(rencana_aksi)="row">
        <a v-for="rencanaAksi in row.value" :href="rencanaAksi" class="d-block">{{ rencanaAksi }}</a>
      </template>
      <template #cell(berita_acara)="row">
        <a :href="row.value" target="_blank" class="d-block">{{ row.value }}</a>
      </template>
      <template #cell(sesuai)="row">
        <span v-if="row.value">Sesuai</span>
        <span v-else>Masih ada yang belum sesuai</span>
      </template>
    </b-table>
  </b-card>
</template>