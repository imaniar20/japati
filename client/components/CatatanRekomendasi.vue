<script>
import axios from 'axios'
import Swal from 'sweetalert2'

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
        store: false,
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
          { key: 'action', label: 'Aksi' },
        ]
      },
      rekomendasiForm: {
        rekomendasi: '',
        sesuai: true,
      },
      options: {
        rekomendasiSesuai: [
          { value: true, text: 'Sesuai' },
          { value: false, text: 'Masih ada yang belum sesuai' },
        ],
        rencanaAksi: [],
      }
    }
  },

  watch: {
    satkerId: function () {
      this.getData()
    }
  },

  mounted() {
    if (this.satkerId) {
      this.getData()
    }
  },

  methods: {
    async getData() {
      this.isBusy.getData = true
    
      try {
        const { data: { data, rencana_aksi } } = await axios.get('/lke/catatan-rekomendasi', {
          params: {
            satuan_kerja_id: this.satkerId,
          },
        })

        this.data.catatan = data?.catatan || []
        this.data.rekomendasi = data?.rekomendasi || []
        this.options.rencanaAksi = rencana_aksi || []
      } catch (error) {
        console.error(error)
        alert('Gagal mengambil data')
      } finally {
        this.isBusy.getData = false
      }
    },
    async store() {
      this.isBusy.store = true

      try {
        await axios.post('/lke/catatan-rekomendasi', {
          satuan_kerja_id: this.satkerId,
          rekomendasi: this.data.rekomendasi
        })
      } catch (error) {
        alert('Gagal menambah data')
      } finally {
        this.isBusy.store = false
      }
    },
    reset() {
      this.rekomendasiForm = {
        rekomendasi: '',
        sesuai: true,
      }
    },

    rekomendasiAdd() {
      this.data.rekomendasi.push({
        rekomendasi: this.rekomendasiForm.rekomendasi,
        sesuai: this.rekomendasiForm.sesuai,
        rencana_aksi: this.options.rencanaAksi,
        berita_acara: this.rekomendasiForm.berita_acara,
      })

      this.$bvModal.hide('modal-rekomendasi-create')
      this.store()
    },
    
    rekomendasiUpdate() {
      this.data.rekomendasi.splice(this.rekomendasiForm.index, 1, {
        rekomendasi: this.rekomendasiForm.rekomendasi,
        sesuai: this.rekomendasiForm.sesuai,
        rencana_aksi: this.rekomendasiForm.rencana_aksi,
        berita_acara: this.rekomendasiForm.berita_acara,
      })

      this.$bvModal.hide('modal-rekomendasi-edit')
      this.store()
    },

    rekomendasiDestroy(index) {
      Swal.fire({
        title: 'Hapus Data',
        text:  'Apakah Anda yakin akan menghapus data?',
        type: 'warning',
        showConfirmButton: true,
        showCancelButton: true,
        confirmButtonColor: '#bd2130',
        confirmButtonText: 'Hapus'
      })
      .then(({value}) => {
        if (value) {
          this.data.rekomendasi.splice(index, 1)
          this.store()
        }
      })
    },

    rekomendasiSetForm(index, item) {
      this.rekomendasiForm = {
        index,
        ...item
      }
    },
  }
}
</script>

<template>
  <b-card v-if="satkerId && success" title="Catatan & Rekomendasi">
    <div class="text-center">
      <h4>Catatan</h4>
    </div>

    <b-table responsive hover bordered :fields="table.fields_catatan" :items="data.catatan" :busy="isBusy.getData && isBusy.store" show-empty head-variant="info">
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
    <div class="text-right mb-3">
      <b-button variant="primary" v-b-modal.modal-rekomendasi-create @click="reset"><i class="ti-plus" aria-hidden="true"></i> Tambah</b-button>
    </div>

    <b-table responsive hover bordered :fields="table.fields_rekomendasi" :items="data.rekomendasi" :busy="isBusy.getData && isBusy.store" show-empty head-variant="info">
      <template #cell(no)="row">
        <div class="text-center font-weight-bold">{{ row.index + 1 }}</div>
      </template>
      <template #cell(rekomendasi)="row">
        <div style="white-space: break-spaces;">{{ row.value }}</div>
      </template>
      <template #cell(rencana_aksi)="row">
        <a v-for="rencanaAksi in row.value" :href="rencanaAksi" target="_blank" class="d-block">{{ rencanaAksi }}</a>
      </template>
      <template #cell(berita_acara)="row">
        <a :href="row.value" target="_blank" class="d-block">{{ row.value }}</a>
      </template>
      <template #cell(sesuai)="row">
        <span v-if="row.value">Sesuai</span>
        <span v-else>Masih ada yang belum sesuai</span>
      </template>
      <template #cell(action)="row">
        <div class="text-nowrap">
          <b-button size="sm" v-b-modal.modal-rekomendasi-edit @click="rekomendasiSetForm(row.index, row.item)" variant="outline-warning" class="rounded-circle" v-b-tooltip.hover title="Edit">
            <i class="fa fa-pencil" aria-hidden="true"></i>
          </b-button>
          <b-button size="sm" @click="rekomendasiDestroy(row.index)" variant="outline-danger" class="rounded-circle" v-b-tooltip.hover title="Hapus">
            <i class="fa fa-trash" aria-hidden="true"></i>
          </b-button>
        </div>
      </template>
    </b-table>

    <!-- Modal Rekomendasi -->
    <b-modal id="modal-rekomendasi-create" title="Tambah Data">
      <form ref="form-rekomendasi-create" @submit.prevent="rekomendasiAdd">
        <b-form-group label-cols="12" label="Berita Acara Hasil Monev" label-class="font-weight-bold" label-for="berita_acara">
          <b-form-input id="berita_acara" v-model="rekomendasiForm.berita_acara" type="url" required></b-form-input>
          <small>Harus formal URL. Contoh: https://drive.google.com/contoh</small>
        </b-form-group>
        <b-form-group label-cols="12" label="Monev LHE" label-class="font-weight-bold" label-for="sesuai">
          <b-form-select v-model="rekomendasiForm.sesuai" :options="options.rekomendasiSesuai" required></b-form-select>
        </b-form-group>
        <button ref="submit-form-rekomendasi-create" class="d-none"></button>
      </form>

      <template #modal-footer="{ cancel }">
        <b-button @click="cancel">Cancel</b-button>
        <b-button variant="primary" @click="$refs['submit-form-rekomendasi-create'].click()">
          Tambah
        </b-button>
      </template>
    </b-modal>
    <b-modal id="modal-rekomendasi-edit" title="Edit Data">
      <form ref="form-edit" @submit.prevent="rekomendasiUpdate">
        <b-form-group label-cols="12" label="Berita Acara Hasil Monev" label-class="font-weight-bold" label-for="berita_acara">
          <b-form-input id="berita_acara" v-model="rekomendasiForm.berita_acara" type="url" required></b-form-input>
          <small>Harus formal URL. Contoh: https://drive.google.com/contoh</small>
        </b-form-group>
        <b-form-group label-cols="12" label="Monev LHE" label-class="font-weight-bold" label-for="sesuai">
          <b-form-select v-model="rekomendasiForm.sesuai" :options="options.rekomendasiSesuai" required></b-form-select>
        </b-form-group>
        <button ref="submit-form-rekomendasi-edit" class="d-none"></button>
      </form>

      <template #modal-footer="{ cancel }">
        <b-button @click="cancel">Cancel</b-button>
        <b-button variant="primary" @click="$refs['submit-form-rekomendasi-edit'].click()">
          Simpan
        </b-button>
      </template>
    </b-modal>
  </b-card>
</template>