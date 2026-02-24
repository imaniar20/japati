<script>
import axios from 'axios'
import Swal from 'sweetalert2'

export default {
  props: {
    type: {
      type: String,
      required: true,
      validator: function (value) {
        return ['sasaran-strategis-rpjmd', 'sasaran-strategis-pd', 'kinerja-program', 'kinerja-kegiatan', 'kinerja-sub-kegiatan'].indexOf(value) !== -1
      }
    },
    id: {
      type: Number,
      required: true,
    },
    solusi: {
      required: false,
      default: null,
    }
  },

  data() {
    return {
      form: {
        masalah_id: null,
        masalah_type: null,
      },
      options: [],
      isBusy: {
        submit: false,
        destroy: false,
      },
    }
  },

  mounted() {
    if (this.solusi) {
      this.form.masalah_id = this.solusi.masalah_id
      this.form.masalah_type = this.solusi.masalah_type
    }

    this.getData()
  },

  methods: {
    async getData() {
      const { data } = await axios.get(`/solusi-kinerja/${this.type}`)

      this.options = data
    },
    setType(e) {
      this.form.masalah_type = e.type
    },
    async submit() {
      this.isBusy.submit = true

      try {
        await axios.post(`/solusi-kinerja/${this.type}/${this.id}`, this.form)

        Swal.fire('Berhasil', 'Berhasil simpan data', 'success')
      } catch (error) {
        Swal.fire('Gagal', error.response?.data?.message || 'Gagal simpan data', 'error')
      } finally {
        this.isBusy.submit = false
      }
    },
    async destroy() {
      this.form.masalah_id = null
      this.form.masalah_type = null

      this.isBusy.destroy = true

      try {
        await axios.delete(`/solusi-kinerja/${this.type}/${this.id}`)
        Swal.fire('Berhasil', 'Berhasil hapus data', 'success')
      } catch (error) {
        Swal.fire('Gagal', error.response?.data?.message || 'Gagal simpan data', 'error')
      } finally {
        this.isBusy.destroy = false
      }
    },
    filterKegagalan(option, label, search) {
      label = `${label} ${option.nama} ${option.sasaran} ${option.indikator}`
      return (label || '').toLocaleLowerCase().indexOf(search.toLocaleLowerCase()) > -1
    }
  }
}
</script>

<template>
  <div>
    <v-select
      v-model="form.masalah_id"
      :options="options"
      :reduce="opt => opt.id"
      placeholder="Pilih kegagalan tahun lalu"
      @option:selected="setType"
      :clearable="false"
      :filterBy="filterKegagalan"
    >
      <template #option="opt">
        <div class="border-bottom py-2 my-1">
          <div v-if="opt.type_string" class="font-weight-bold mb-1 text-uppercase">{{ opt.type_string }}</div>
          <div>Nama: <b>{{ opt.nama }}</b></div>
          <div>Sasaran: <b>{{ opt.sasaran }}</b></div>
          <div>Indikator: <b>{{ opt.indikator }}</b></div>
        </div>
      </template>
      <template #selected-option="opt">
        <div style="display: flex; align-items: baseline">
          <div>{{ opt.nama }} | {{ opt.sasaran }} | {{ opt.indikator }}</div>
        </div>
      </template>
    </v-select>
    <div class="text-right mt-5">
      <b-button variant="danger" :disabled="isBusy.destroy" @click="destroy">
        <b-spinner small v-if="isBusy.destroy"></b-spinner>
        <i v-else class="fa fa-trash" aria-hidden="true"></i>
        Hapus
      </b-button>
      <b-button variant="primary" :disabled="isBusy.submit" @click="submit">
        <b-spinner small v-if="isBusy.submit"></b-spinner>
        <i v-else class="fa fa-floppy-o" aria-hidden="true"></i>
        Simpan
      </b-button>
    </div>
  </div>
</template>