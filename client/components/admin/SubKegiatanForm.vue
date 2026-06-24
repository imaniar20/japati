<script>
import axios from 'axios'
import Swal from 'sweetalert2'

export default {
  name: 'AdminSubKegiatanForm',

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
      kegiatan: [],
      isKegiatanBusy: false,
    }
  },

  created() {
    this.ensureIndikator()
    this.getKegiatan()
  },

  methods: {
    ensureIndikator() {
      if (!Array.isArray(this.form.indikator)) {
        this.$set(this.form, 'indikator', [])
      }
    },

    addIndikator() {
      this.ensureIndikator()
      this.form.indikator.push({
        indikator: null,
        target: null,
        satuan: null,
      })
    },

    removeIndikator(index) {
      this.form.indikator.splice(index, 1)
    },

    async getKegiatan(reset = false) {
      if (!this.form.satuan_kerja_id || !this.form.tahun_kinerja) {
        this.kegiatan = []
        return
      }

      try {
        this.isKegiatanBusy = true

        const { data } = await axios.get('kegiatan-data', {
          params: {
            satuan_kerja_id: this.form.satuan_kerja_id,
            tahun_kinerja: this.form.tahun_kinerja,
          },
        })

        this.kegiatan = data

        if (reset && this.form.kegiatan_id && !data.some((item) => Number(item.id) === Number(this.form.kegiatan_id))) {
          this.form.kegiatan_id = null
        }
      } catch (error) {
        Swal.fire({
          type: 'error',
          title: error.response?.data?.message || 'Gagal mengambil data kegiatan!',
        })
      } finally {
        this.isKegiatanBusy = false
      }
    },
  },

  watch: {
    'form.satuan_kerja_id': function () {
      this.getKegiatan(true)
    },
    'form.tahun_kinerja': function () {
      this.getKegiatan(true)
    },
  },
}
</script>

<template>
  <b-card>
    <form @submit.prevent="$emit('submit')">
      <OptionSatuanKerja
        v-if="$role.isSuper()"
        id="sub-kegiatan-satuan-kerja"
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
            <b-form-group label="Kode Sub Kegiatan" label-class="font-weight-bold" label-for="kode">
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

        <b-form-group label="Kegiatan" label-class="font-weight-bold" label-for="kegiatan">
          <v-select
            id="kegiatan"
            v-model="form.kegiatan_id"
            :options="kegiatan"
            :reduce="opt => opt.id"
            label="nama"
            placeholder="Pilih kegiatan"
            :loading="isKegiatanBusy"
            :clearable="false"
            :filterBy="$helper.vSelectFilterBy('kode', 'nama', 'program.nama')"
          >
            <template #search="{ attributes, events }">
              <input
                class="vs__search"
                :required="!form.kegiatan_id"
                v-bind="attributes"
                v-on="events"
              />
            </template>
            <template #option="opt">
              <div class="py-1">
                <div><b>{{ opt.kode }}</b> - {{ opt.nama }}</div>
                <div class="text-muted small">{{ opt.program?.nama || '-' }}</div>
              </div>
            </template>
            <template #selected-option="opt">
              <div>{{ opt.kode }} - {{ opt.nama }}</div>
            </template>
          </v-select>
        </b-form-group>

        <b-form-group label="Nama Sub Kegiatan" label-class="font-weight-bold" label-for="nama">
          <b-form-textarea id="nama" v-model="form.nama" rows="3" required></b-form-textarea>
        </b-form-group>

        <b-card class="mt-3" title="Indikator Kemendagri">
          <div v-for="(item, index) in form.indikator" :key="index" class="border-bottom pb-3 mb-3">
            <b-row>
              <b-col cols="12" md="6">
                <b-form-group label="Indikator" label-class="font-weight-bold">
                  <b-form-textarea v-model="item.indikator" rows="2"></b-form-textarea>
                </b-form-group>
              </b-col>
              <b-col cols="12" md="2">
                <b-form-group label="Target" label-class="font-weight-bold">
                  <b-form-input v-model="item.target"></b-form-input>
                </b-form-group>
              </b-col>
              <b-col cols="12" md="3">
                <b-form-group label="Satuan" label-class="font-weight-bold">
                  <b-form-input v-model="item.satuan"></b-form-input>
                </b-form-group>
              </b-col>
              <b-col cols="12" md="1" class="d-flex align-items-end">
                <b-button variant="outline-danger" class="mb-3 rounded-circle" title="Hapus indikator" @click="removeIndikator(index)">
                  <i class="fa fa-trash" aria-hidden="true"></i>
                </b-button>
              </b-col>
            </b-row>
          </div>

          <b-button variant="outline-primary" size="sm" @click="addIndikator">
            <i class="fa fa-plus" aria-hidden="true"></i>
            Tambah Indikator
          </b-button>
        </b-card>

        <div class="text-right mt-3">
          <nuxt-link to="/admin/sub-kegiatan" class="btn btn-outline-secondary mr-2">
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
