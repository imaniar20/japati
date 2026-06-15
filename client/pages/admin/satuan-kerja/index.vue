<script>
import axios from 'axios'
import Swal from 'sweetalert2'
import { destroy as doDestroy } from '~/plugins/swal'

export default {
  middleware: ['auth', 'role-super'],

  data() {
    return {
      data: [],
      filter: {
        keyword: '',
      },
      form: this.emptyForm(),
      editingId: null,
      isBusy: {
        getData: false,
        save: false,
      },
      fields: [
        { key: 'satuan_kerja_id', label: 'ID' },
        { key: 'satuan_kerja_nama', label: 'Nama OPD' },
        { key: 'satuan_kerja_nama_alias', label: 'Alias' },
        { key: 'kode', label: 'Kode' },
        { key: 'kode_skpd', label: 'Kode SKPD' },
        { key: 'tahun_id', label: 'Tahun' },
        { key: 'status', label: 'Status' },
        { key: 'action', label: 'Aksi', class: 'text-center' },
      ],
    }
  },

  computed: {
    isEditing() {
      return this.editingId !== null
    },
    filteredData() {
      const keyword = this.filter.keyword.trim().toLowerCase()

      if (!keyword) {
        return this.data
      }

      return this.data.filter((item) => [
        item.satuan_kerja_id,
        item.satuan_kerja_nama,
        item.satuan_kerja_nama_alias,
        item.kode,
        item.kode_skpd,
        item.satuan_kerja_alamat,
      ].some((value) => String(value || '').toLowerCase().includes(keyword)))
    },
  },

  mounted() {
    this.getData()
  },

  methods: {
    emptyForm() {
      return {
        satuan_kerja_id: null,
        tahun_id: parseInt(String(this.$helper.getTahunKinerja()).substring(0, 4)),
        satuan_kerja_nama: '',
        kode: '',
        satuan_kerja_alamat: '',
        satuan_kerja_kel_ds: '',
        kecamatan_id: null,
        satuan_kerja_khusus: '',
        status: null,
        kode_skpd: '',
        create_username: '',
        update_username: '',
        latitude: null,
        longitude: null,
        kota: '',
        kecamatan: '',
        kelurahan: '',
        satuan_kerja_nama_alias: '',
        sapk_id: '',
        bobot: null,
        m_kabkot_id: null,
        rumpun_id: null,
        lampiran_no: null,
      }
    },
    async getData() {
      this.isBusy.getData = true

      const { data } = await axios.get('admin/satuan-kerja')

      this.data = data
      this.isBusy.getData = false
    },
    edit(item) {
      this.editingId = item.satuan_kerja_id
      this.form = {
        ...this.emptyForm(),
        ...item,
      }

      window.scrollTo({ top: 0, behavior: 'smooth' })
    },
    resetForm() {
      this.editingId = null
      this.form = this.emptyForm()
    },
    async submit() {
      try {
        this.isBusy.save = true

        if (this.isEditing) {
          await axios.patch(`admin/satuan-kerja/${this.editingId}`, this.form)
        } else {
          await axios.post('admin/satuan-kerja', this.form)
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
      doDestroy({
        preConfirm: async () => {
          await axios.delete(`admin/satuan-kerja/${id}`)
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
    <form @submit.prevent="submit">
      <b-row>
        <b-col cols="12" md="4">
          <b-form-group label="ID Satuan Kerja" label-class="font-weight-bold" label-for="satuan_kerja_id">
            <b-form-input id="satuan_kerja_id" v-model.number="form.satuan_kerja_id" type="number" min="1" required :readonly="isEditing"></b-form-input>
          </b-form-group>
        </b-col>
        <b-col cols="12" md="4">
          <b-form-group label="Tahun" label-class="font-weight-bold" label-for="tahun_id">
            <b-form-input id="tahun_id" v-model.number="form.tahun_id" type="number" min="1900"></b-form-input>
          </b-form-group>
        </b-col>
        <b-col cols="12" md="4">
          <b-form-group label="Status" label-class="font-weight-bold" label-for="status">
            <b-form-input id="status" v-model.number="form.status" type="number"></b-form-input>
          </b-form-group>
        </b-col>
      </b-row>

      <b-form-group label="Nama OPD" label-class="font-weight-bold" label-for="satuan_kerja_nama">
        <b-form-input id="satuan_kerja_nama" v-model="form.satuan_kerja_nama" required></b-form-input>
      </b-form-group>

      <b-row>
        <b-col cols="12" md="4">
          <b-form-group label="Alias" label-class="font-weight-bold" label-for="satuan_kerja_nama_alias">
            <b-form-input id="satuan_kerja_nama_alias" v-model="form.satuan_kerja_nama_alias"></b-form-input>
          </b-form-group>
        </b-col>
        <b-col cols="12" md="4">
          <b-form-group label="Kode" label-class="font-weight-bold" label-for="kode">
            <b-form-input id="kode" v-model="form.kode"></b-form-input>
          </b-form-group>
        </b-col>
        <b-col cols="12" md="4">
          <b-form-group label="Kode SKPD" label-class="font-weight-bold" label-for="kode_skpd">
            <b-form-input id="kode_skpd" v-model="form.kode_skpd"></b-form-input>
          </b-form-group>
        </b-col>
      </b-row>

      <b-form-group label="Alamat" label-class="font-weight-bold" label-for="satuan_kerja_alamat">
        <b-form-textarea id="satuan_kerja_alamat" v-model="form.satuan_kerja_alamat" rows="2" max-rows="6"></b-form-textarea>
      </b-form-group>

      <b-row>
        <b-col cols="12" md="4">
          <b-form-group label="Kota" label-class="font-weight-bold" label-for="kota">
            <b-form-input id="kota" v-model="form.kota"></b-form-input>
          </b-form-group>
        </b-col>
        <b-col cols="12" md="4">
          <b-form-group label="Kecamatan" label-class="font-weight-bold" label-for="kecamatan">
            <b-form-input id="kecamatan" v-model="form.kecamatan"></b-form-input>
          </b-form-group>
        </b-col>
        <b-col cols="12" md="4">
          <b-form-group label="Kelurahan" label-class="font-weight-bold" label-for="kelurahan">
            <b-form-input id="kelurahan" v-model="form.kelurahan"></b-form-input>
          </b-form-group>
        </b-col>
      </b-row>

      <b-row>
        <b-col cols="12" md="4">
          <b-form-group label="Latitude" label-class="font-weight-bold" label-for="latitude">
            <b-form-input id="latitude" v-model.number="form.latitude" type="number" step="any"></b-form-input>
          </b-form-group>
        </b-col>
        <b-col cols="12" md="4">
          <b-form-group label="Longitude" label-class="font-weight-bold" label-for="longitude">
            <b-form-input id="longitude" v-model.number="form.longitude" type="number" step="any"></b-form-input>
          </b-form-group>
        </b-col>
        <b-col cols="12" md="4">
          <b-form-group label="Kecamatan ID" label-class="font-weight-bold" label-for="kecamatan_id">
            <b-form-input id="kecamatan_id" v-model.number="form.kecamatan_id" type="number"></b-form-input>
          </b-form-group>
        </b-col>
      </b-row>

      <b-button v-b-toggle:satuan-kerja-lainnya variant="outline-secondary" size="sm" type="button" class="mb-3">
        Field Tambahan
      </b-button>

      <b-collapse id="satuan-kerja-lainnya">
        <b-row>
          <b-col cols="12" md="4">
            <b-form-group label="Kel/Desa" label-class="font-weight-bold" label-for="satuan_kerja_kel_ds">
              <b-form-input id="satuan_kerja_kel_ds" v-model="form.satuan_kerja_kel_ds"></b-form-input>
            </b-form-group>
          </b-col>
          <b-col cols="12" md="4">
            <b-form-group label="Khusus" label-class="font-weight-bold" label-for="satuan_kerja_khusus">
              <b-form-input id="satuan_kerja_khusus" v-model="form.satuan_kerja_khusus"></b-form-input>
            </b-form-group>
          </b-col>
          <b-col cols="12" md="4">
            <b-form-group label="SAPK ID" label-class="font-weight-bold" label-for="sapk_id">
              <b-form-input id="sapk_id" v-model="form.sapk_id"></b-form-input>
            </b-form-group>
          </b-col>
        </b-row>

        <b-row>
          <b-col cols="12" md="3">
            <b-form-group label="Bobot" label-class="font-weight-bold" label-for="bobot">
              <b-form-input id="bobot" v-model.number="form.bobot" type="number" step="any"></b-form-input>
            </b-form-group>
          </b-col>
          <b-col cols="12" md="3">
            <b-form-group label="Kab/Kota ID" label-class="font-weight-bold" label-for="m_kabkot_id">
              <b-form-input id="m_kabkot_id" v-model.number="form.m_kabkot_id" type="number"></b-form-input>
            </b-form-group>
          </b-col>
          <b-col cols="12" md="3">
            <b-form-group label="Rumpun ID" label-class="font-weight-bold" label-for="rumpun_id">
              <b-form-input id="rumpun_id" v-model.number="form.rumpun_id" type="number"></b-form-input>
            </b-form-group>
          </b-col>
          <b-col cols="12" md="3">
            <b-form-group label="Lampiran No" label-class="font-weight-bold" label-for="lampiran_no">
              <b-form-input id="lampiran_no" v-model.number="form.lampiran_no" type="number" step="any"></b-form-input>
            </b-form-group>
          </b-col>
        </b-row>

        <b-row>
          <b-col cols="12" md="6">
            <b-form-group label="Create Username" label-class="font-weight-bold" label-for="create_username">
              <b-form-input id="create_username" v-model="form.create_username"></b-form-input>
            </b-form-group>
          </b-col>
          <b-col cols="12" md="6">
            <b-form-group label="Update Username" label-class="font-weight-bold" label-for="update_username">
              <b-form-input id="update_username" v-model="form.update_username"></b-form-input>
            </b-form-group>
          </b-col>
        </b-row>
      </b-collapse>

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

    <b-row class="align-items-end mb-3">
      <b-col cols="12" md="6">
        <b-form-group label="Cari" label-class="font-weight-bold" label-for="keyword" class="mb-md-0">
          <b-form-input id="keyword" v-model="filter.keyword"></b-form-input>
        </b-form-group>
      </b-col>
      <b-col cols="12" md="6" class="text-md-right">
        <b-badge variant="info" class="px-3 py-2">{{ filteredData.length }} OPD</b-badge>
      </b-col>
    </b-row>

    <b-table responsive hover striped :busy="isBusy.getData" :items="filteredData" :fields="fields" show-empty class="table-bordered" head-variant="info">
      <template #cell(satuan_kerja_nama)="row">
        <strong>{{ row.item.satuan_kerja_nama }}</strong>
        <div v-if="row.item.satuan_kerja_alamat" class="text-muted small">{{ row.item.satuan_kerja_alamat }}</div>
      </template>

      <template #cell(status)="row">
        {{ row.item.status === null || row.item.status === undefined ? '-' : row.item.status }}
      </template>

      <template #cell(action)="row">
        <div class="text-nowrap">
          <b-button @click="edit(row.item)" variant="outline-warning" size="sm" class="m-1 rounded-circle" title="Edit">
            <i class="fa fa-pencil" aria-hidden="true"></i>
          </b-button>
          <b-button @click="destroy(row.item.satuan_kerja_id)" variant="outline-danger" size="sm" class="m-1 rounded-circle" title="Hapus">
            <i class="fa fa-trash" aria-hidden="true"></i>
          </b-button>
        </div>
      </template>
    </b-table>
  </b-card>
</template>
