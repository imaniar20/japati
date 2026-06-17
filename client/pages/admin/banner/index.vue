<script>
import axios from 'axios'
import Swal from 'sweetalert2'
import { destroy as doDestroy } from '~/plugins/swal'

export default {
  middleware: ['auth', 'role-super'],

  data() {
    return {
      data: [],
      form: this.emptyForm(),
      editingId: null,
      isBusy: {
        getData: false,
        save: false,
      },
      fields: [
        { key: 'order', label: 'Urutan' },
        { key: 'judul', label: 'Judul' },
        { key: 'gambar_url', label: 'Gambar' },
        { key: 'action', label: 'Aksi', class: 'text-center' },
      ],
    }
  },

  computed: {
    isEditing() {
      return this.editingId !== null && this.editingId !== undefined
    },
    tahunKinerja() {
      return this.$helper.getTahunKinerja()
    },
  },

  mounted() {
    this.getData()
  },

  methods: {
    emptyForm() {
      return {
        judul: '',
        order: null,
        gambar: null,
        gambar_url: null,
      }
    },
    async getData() {
      try {
        this.isBusy.getData = true

        const { data } = await axios.get('admin/banner')

        this.data = data
      } catch (error) {
        Swal.fire({
          type: 'error',
          title: error.response?.data?.message || 'Gagal mengambil data!',
        })
      } finally {
        this.isBusy.getData = false
      }
    },
    edit(item) {
      if (!item.id) {
        Swal.fire({
          type: 'error',
          title: 'ID banner belum tersedia. Jalankan migration perbaikan tabel infografis dulu.',
        })
        return
      }

      this.editingId = item.id
      this.form = {
        ...this.emptyForm(),
        judul: item.judul,
        order: item.order,
        gambar_url: item.gambar_url,
      }

      this.resetFileInput()
      window.scrollTo({ top: 0, behavior: 'smooth' })
    },
    resetForm() {
      this.editingId = null
      this.form = this.emptyForm()
      this.resetFileInput()
    },
    resetFileInput() {
      this.$nextTick(() => {
        if (this.$refs.gambar) {
          this.$refs.gambar.reset()
        }
      })
    },
    buildPayload() {
      const payload = new FormData()

      payload.append('judul', this.form.judul || '')

      if (this.form.order !== null && this.form.order !== '') {
        payload.append('order', this.form.order)
      }

      if (this.form.gambar) {
        payload.append('gambar', this.form.gambar)
      }

      return payload
    },
    async submit() {
      try {
        this.isBusy.save = true

        const payload = this.buildPayload()

        if (this.isEditing) {
          await axios.post(`admin/banner/${this.editingId}`, payload)
        } else {
          await axios.post('admin/banner', payload)
        }

        Swal.fire({
          type: 'success',
          title: 'Berhasil simpan data'
        })

        this.resetForm()
        this.getData()
      } catch (error) {
        Swal.fire({
          type: 'error',
          title: error.response?.data?.message || 'Gagal simpan data!',
        })
      } finally {
        this.isBusy.save = false
      }
    },
    destroy(id) {
      if (!id) {
        Swal.fire({
          type: 'error',
          title: 'ID banner belum tersedia. Jalankan migration perbaikan tabel infografis dulu.',
        })
        return
      }

      doDestroy({
        preConfirm: async () => {
          await axios.delete(`admin/banner/${id}`)
          this.getData()
          return true
        }
      })
    },
  },
}
</script>

<template>
  <b-card>
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
      <h5 class="mb-2 mb-md-0">Master Banner</h5>
      <b-badge variant="info" class="px-3 py-2">Tahun {{ tahunKinerja }}</b-badge>
    </div>

    <form @submit.prevent="submit">
      <b-row>
        <b-col cols="12" md="8">
          <b-form-group label="Judul" label-class="font-weight-bold" label-for="judul">
            <b-form-input id="judul" v-model="form.judul" required></b-form-input>
          </b-form-group>
        </b-col>
        <b-col cols="12" md="4">
          <b-form-group label="Urutan" label-class="font-weight-bold" label-for="order">
            <b-form-input id="order" v-model.number="form.order" type="number" min="0"></b-form-input>
          </b-form-group>
        </b-col>
      </b-row>

      <b-row>
        <b-col cols="12" md="8">
          <b-form-group label="Foto Banner" label-class="font-weight-bold" label-for="gambar">
            <b-form-file id="gambar" ref="gambar" v-model="form.gambar" accept=".jpg,.jpeg,.png,.webp" :required="!isEditing"></b-form-file>
          </b-form-group>
        </b-col>
      </b-row>

      <b-row v-if="form.gambar_url">
        <b-col cols="12" md="6">
          <img :src="form.gambar_url" alt="" class="banner-preview">
        </b-col>
      </b-row>

      <div class="text-right mt-3">
        <b-button v-if="isEditing" variant="outline-secondary" class="mr-2" type="button" @click="resetForm">
          Batal
        </b-button>
        <b-button variant="primary" :disabled="isBusy.save" type="submit">
          <i v-if="isBusy.save" class="fa fa-spinner fa-pulse" aria-hidden="true"></i>
          <i v-else class="fa fa-floppy-o" aria-hidden="true"></i>
          Simpan
        </b-button>
      </div>
    </form>

    <hr class="my-4">

    <div class="text-right mb-3">
      <b-badge variant="info" class="px-3 py-2">{{ data.length }} Banner</b-badge>
    </div>

    <b-table responsive hover striped :busy="isBusy.getData" :items="data" :fields="fields" show-empty class="table-bordered" head-variant="info">
      <template #cell(gambar_url)="row">
        <img v-if="row.item.gambar_url" :src="row.item.gambar_url" alt="" class="table-preview">
        <span v-else>-</span>
      </template>

      <template #cell(action)="row">
        <div class="text-nowrap">
          <b-button @click="edit(row.item)" variant="outline-warning" size="sm" class="m-1 rounded-circle" title="Edit">
            <i class="fa fa-pencil" aria-hidden="true"></i>
          </b-button>
          <b-button @click="destroy(row.item.id)" variant="outline-danger" size="sm" class="m-1 rounded-circle" title="Hapus">
            <i class="fa fa-trash" aria-hidden="true"></i>
          </b-button>
        </div>
      </template>
    </b-table>
  </b-card>
</template>

<style scoped>
.banner-preview {
  width: 100%;
  max-height: 260px;
  object-fit: contain;
  border: 1px solid #dee2e6;
  border-radius: 8px;
  background: #f8f9fa;
}

.table-preview {
  width: 96px;
  height: 72px;
  object-fit: contain;
  border: 1px solid #dee2e6;
  border-radius: 6px;
  background: #f8f9fa;
}
</style>
