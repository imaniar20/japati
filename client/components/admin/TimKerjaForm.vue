<script>
import axios from 'axios'
import { debounce } from 'lodash'
import Swal from 'sweetalert2'

export default {
  name: 'AdminTimKerjaForm',

  props: {
    form: {
      type: Object,
      required: true,
    },
    mode: {
      type: String,
      default: 'create',
    },
    isBusy: {
      type: Boolean,
      default: false,
    },
  },

  data() {
    return {
      unitKerjaList: [],
      pegawai: [],
      isUnitKerjaBusy: false,
      searchSettings: {
        minCharSearch: 3,
      },
    }
  },

  computed: {
    isEdit() {
      return this.mode === 'edit'
    },
  },

  mounted() {
    if (this.form.ketua) {
      this.pegawai = [this.form.ketua]
    }

    if (this.form.satuan_kerja_id) {
      this.getUnitKerja()
    }
  },

  methods: {
    async getUnitKerja() {
      if (!this.form.satuan_kerja_id) {
        this.unitKerjaList = []
        return
      }

      try {
        this.isUnitKerjaBusy = true

        const { data } = await axios.get(`/option/unit-kerja/${this.form.satuan_kerja_id}`)

        this.unitKerjaList = data
      } catch (error) {
        this.unitKerjaList = []
        Swal.fire({
          type: 'error',
          title: 'Gagal mengambil data unit kerja',
        })
      } finally {
        this.isUnitKerjaBusy = false
      }
    },

    searchPegawai: debounce(function (search, loading) {
      if (!this.form.satuan_kerja_id || search.length < this.searchSettings.minCharSearch) {
        return false
      }

      this.doSearchPegawai(search, loading)
    }, 500),

    doSearchPegawai(search, loading) {
      loading(true)

      axios.get('/tim-kerja/pegawai', {
        params: {
          search,
          satuan_kerja_id: this.form.satuan_kerja_id,
        },
      })
        .then((res) => {
          this.pegawai = res.data
        })
        .catch(() => {
          this.pegawai = []
          Swal.fire({
            type: 'error',
            title: 'Gagal mencari data pegawai',
          })
        })
        .then(() => {
          loading(false)
        })
    },

    filterPegawai(option, label, search) {
      const keyword = search.toLowerCase()

      return ((label || '').toLowerCase().indexOf(keyword) > -1)
        || ((option.peg_nip || '').toLowerCase().indexOf(keyword) > -1)
        || ((option.peg_nama || '').toLowerCase().indexOf(keyword) > -1)
    },
  },

  watch: {
    'form.satuan_kerja_id': function (newValue, oldValue) {
      if (oldValue && oldValue !== newValue) {
        this.form.v_struktur_organisasi_id = null
        this.form.nip_ketua = null
        this.pegawai = []
      }

      this.getUnitKerja()
    },
  },
}
</script>

<template>
  <b-card>
    <form @submit.prevent="$emit('submit')">
      <OptionSatuanKerja
        v-if="$role.isSuper()"
        id="tim-kerja-satuan-kerja"
        v-model="form.satuan_kerja_id"
        label-title="Satuan Kerja / OPD"
      />

      <div v-if="form.satuan_kerja_id">
        <b-form-group label-cols="12" label-cols-md="2" label="Nama Tim Kerja" label-class="font-weight-bold" label-for="nama">
          <b-form-input id="nama" v-model="form.nama" required></b-form-input>
        </b-form-group>

        <b-form-group label-cols="12" label-cols-md="2" label="Unit Kerja Koordinasi" label-class="font-weight-bold" label-for="unit-kerja-koordinasi">
          <v-select id="unit-kerja-koordinasi"
            v-model="form.v_struktur_organisasi_id"
            :options="unitKerjaList"
            :reduce="opt => opt.id"
            label="unit_kerja_nama"
            :placeholder="isUnitKerjaBusy ? 'Sedang memuat data...' : 'Pilih Unit Kerja'"
          />
        </b-form-group>

        <b-form-group label-cols="12" label-cols-md="2" label="Pengampu Kinerja Outcome" label-class="font-weight-bold" label-for="pengampu-outcome">
          <v-select id="pengampu-outcome"
            v-model="form.nip_ketua"
            :options="pegawai"
            @search="searchPegawai"
            :filterBy="filterPegawai"
            :reduce="opt => opt.peg_nip"
            label="peg_nama"
            placeholder="Pilih Pengampu Kinerja Outcome"
            :clearable="false"
          >
            <template #search="{attributes, events}">
              <input
                class="vs__search"
                :required="!form.nip_ketua"
                v-bind="attributes"
                v-on="events"
              />
            </template>
            <template slot="option" slot-scope="option">
              <div class="my-2">
                <div class="font-weight-bold">{{ option.peg_nip }} - {{ option.peg_nama }}</div>
                <div>{{ option.jabatan_nama }} - {{ option.unit_kerja_nama }}</div>
              </div>
            </template>
            <template #selected-option="option">
              {{ option.peg_nama || option.peg_nip }}
            </template>
            <template v-slot:no-options="{ search, searching }">
              <template v-if="searching && search.length >= searchSettings.minCharSearch">
                Data tidak ditemukan untuk kata kunci <em>{{ search }}</em>.
              </template>
              <em v-else style="opacity: 0.5">Input minimal {{ searchSettings.minCharSearch }} karakter nama atau NIP</em>
            </template>
          </v-select>
        </b-form-group>

        <div class="text-right mt-3">
          <nuxt-link to="/admin/tim-kerja" class="btn btn-outline-secondary mr-2">
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
