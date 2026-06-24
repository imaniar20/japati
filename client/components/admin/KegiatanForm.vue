<script>
import axios from 'axios'
import Swal from 'sweetalert2'

export default {
  name: 'AdminKegiatanForm',

  props: {
    form: {
      type: Object,
      required: true,
    },
    isBusy: {
      type: Boolean,
      default: false,
    },
  },

  data() {
    return {
      program: [],
      isProgramBusy: false,
    }
  },

  created() {
    this.getProgram()
  },

  methods: {
    async getProgram(reset = false) {
      if (!this.form.satuan_kerja_id || !this.form.tahun_kinerja) {
        this.program = []
        return
      }

      try {
        this.isProgramBusy = true

        const { data } = await axios.get('program-data', {
          params: {
            satuan_kerja_id: this.form.satuan_kerja_id,
            tahun_kinerja: this.form.tahun_kinerja,
          },
        })

        this.program = data

        if (reset && this.form.program_id && !data.some((item) => Number(item.id) === Number(this.form.program_id))) {
          this.form.program_id = null
        }
      } catch (error) {
        Swal.fire({
          type: 'error',
          title: error.response?.data?.message || 'Gagal mengambil data program!',
        })
      } finally {
        this.isProgramBusy = false
      }
    },
  },

  watch: {
    'form.satuan_kerja_id': function () {
      this.getProgram(true)
    },
    'form.tahun_kinerja': function () {
      this.getProgram(true)
    },
  },
}
</script>

<template>
  <b-card>
    <form @submit.prevent="$emit('submit')">
      <OptionSatuanKerja
        v-if="$role.isSuper()"
        id="kegiatan-satuan-kerja"
        v-model="form.satuan_kerja_id"
        label-title="Satuan Kerja / OPD"
      />

      <div v-if="form.satuan_kerja_id">
        <b-row>
          <b-col cols="12" md="4">
            <b-form-group label="Tahun Kinerja" label-class="font-weight-bold" label-for="tahun_kinerja">
              <b-form-input
                id="tahun_kinerja"
                v-model.number="form.tahun_kinerja"
                type="number"
                min="1900"
                max="2100"
                required
              ></b-form-input>
            </b-form-group>
          </b-col>
          <b-col cols="12" md="4">
            <b-form-group label="Kode Kegiatan" label-class="font-weight-bold" label-for="kode">
              <b-form-input id="kode" v-model="form.kode" required></b-form-input>
            </b-form-group>
          </b-col>
          <b-col cols="12" md="4">
            <b-form-group label="Anggaran" label-class="font-weight-bold" label-for="anggaran">
              <b-form-input
                id="anggaran"
                v-model.number="form.anggaran"
                type="number"
                min="0"
                step="0.01"
                required
              ></b-form-input>
            </b-form-group>
          </b-col>
        </b-row>

        <b-form-group label="Program" label-class="font-weight-bold" label-for="program">
          <v-select
            id="program"
            v-model="form.program_id"
            :options="program"
            :reduce="opt => opt.id"
            label="nama"
            placeholder="Pilih program"
            :loading="isProgramBusy"
            :clearable="false"
            :filterBy="$helper.vSelectFilterBy('kode', 'nama')"
          >
            <template #search="{ attributes, events }">
              <input
                class="vs__search"
                :required="!form.program_id"
                v-bind="attributes"
                v-on="events"
              />
            </template>
            <template #option="opt">
              <div class="py-1">
                <div><b>{{ opt.kode }}</b> - {{ opt.nama }}</div>
              </div>
            </template>
            <template #selected-option="opt">
              <div>{{ opt.kode }} - {{ opt.nama }}</div>
            </template>
          </v-select>
        </b-form-group>

        <b-form-group label="Nama Kegiatan" label-class="font-weight-bold" label-for="nama">
          <b-form-textarea id="nama" v-model="form.nama" rows="3" required></b-form-textarea>
        </b-form-group>

        <div class="text-right mt-3">
          <nuxt-link to="/admin/kegiatan" class="btn btn-outline-secondary mr-2">
            Batal
          </nuxt-link>
          <b-button variant="primary" :disabled="isBusy" type="submit">
            <i v-if="isBusy" class="fa fa-spinner fa-pulse" aria-hidden="true"></i>
            <i v-else class="fa fa-floppy-o" aria-hidden="true"></i>
            Simpan
          </b-button>
        </div>
      </div>
    </form>
  </b-card>
</template>
