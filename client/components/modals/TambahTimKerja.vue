<script>
const componentId = 'modal-tambah-tim-kerja'
import axios from 'axios';
import { debounce } from 'lodash'
import Swal from 'sweetalert2';

export default {
  name: componentId,

  props: {
    satuanKerjaId: {
      type: Number,
      required: true
    },
  },

  data() {
    return {
      id: componentId,
      showEventId: `show-${componentId}`,
      hideEventId: `hide-${componentId}`,
      parent: null,
      form: {
        nama: null,
        v_struktur_organisasi_id: null,
        satuan_kerja_id: this.satuanKerjaId,
        nip_ketua: null,
      },
      isBusy: false,
      unitKerjaList: [],
      pegawai: [],
      timKerja: {
        settings: {
          minCharSearch: 3,
        }
      }
    }
  },

  computed: {
    labelKetua() {
      if (this.parent == 'kinerja-kegiatan') {
        return 'Pengampu Kinerja Outcome'
      }

      return 'Ketua Tim Kerja'
    }
  },

  mounted() {
    this.$nuxt.$on(this.showEventId, (parent) => {
      this.parent = parent
      this.getUnitKerja()
      this.$bvModal.show(this.id)
    });

    this.$nuxt.$on(this.hideEventId, () => {
      this.$bvModal.hide(this.id)
    });
  },

  methods: {
    async getUnitKerja() {
      const { data } = await axios.get(`/option/unit-kerja/${this.satuanKerjaId}`)

      this.unitKerjaList = data
    },

    async submit() {
      this.isBusy = true
      
      const { data } = await axios.post('/tim-kerja', this.form)

      this.isBusy = false

      if (!data.success) {
        alert(data.message)
        return false
      }

      this.$bvModal.hide(this.id)

      this.$emit('submit', data.data)
    },

    searchPegawai: debounce(function (search, loading) {
      if (this.isBusy || search.length < this.timKerja.settings.minCharSearch) {
        return false
      }

      this.doSearchPegawai(search, loading)
    }, 500),

    doSearchPegawai(search, loading) {
      loading(true)
      axios.get('/tim-kerja/pegawai', {
          params: {
            search,
            satuan_kerja_id: this.satuanKerjaId
          }
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
      return ((label || '').toLowerCase().indexOf(search.toLowerCase()) > -1)
        || ((option.peg_nip || '').toLowerCase().indexOf(search.toLowerCase()) > -1)
        || ((option.peg_nama || '').toLowerCase().indexOf(search.toLowerCase()) > -1)
    },
  }
}
</script>

<template>
  <b-modal :id="id" title="Tambah Tim Kerja" size="lg">
    <form ref="form" @submit.prevent="submit">
      <b-form-group label-cols="12" label-cols-md="2" label="Nama Tim Kerja" label-class="font-weight-bold" label-for="nama">
        <b-form-input id="nama" v-model="form.nama" required></b-form-input>
      </b-form-group>
      <b-form-group label-cols="12" label-cols-md="2" label="Unit Kerja Koordinasi" label-class="font-weight-bold" label-for="unit-kerja-koordinasi">
        <v-select id="unit-kerja-koordinasi"
          v-model="form.v_struktur_organisasi_id"
          :options="unitKerjaList"
          :reduce="opt => opt.id"
          label="unit_kerja_nama" 
          placeholder="Pilih Unit Kerja"
        >
        </v-select>
        <small>Kosongkan jika Tim Kerja mengacu pada Satuan Kerja</small>
      </b-form-group>
      <b-form-group label-cols="12" label-cols-md="2" :label="labelKetua" label-class="font-weight-bold" label-for="ketua-tim-kerja">
        <v-select id="ketua-tim-kerja"
          v-model="form.nip_ketua"
          :options="pegawai"
          @search="searchPegawai"
          :filterBy="filterPegawai"
          :reduce="opt => opt.peg_nip"
          label="peg_nama" 
          :placeholder="`Pilih ${labelKetua}`"
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
          <template v-slot:no-options="{ search, searching }">
            <template v-if="searching && search.length >= timKerja.settings.minCharSearch">
              Data tidak ditemukan untuk kata kunci <em>{{ search }}</em>.
            </template>
            <em v-else style="opacity: 0.5">Silahkan inputkan minimal {{ timKerja.settings.minCharSearch }} karakter nama Ketua Tim Kerja</em>
          </template>
        </v-select>
        <small>Cari berdasarkan NIP atau nama</small>
      </b-form-group>

      <button ref="form-submit" class="d-none"></button>
    </form>

    <template #modal-footer>
      <b-button variant="primary" :disabled="isBusy" @click="$refs['form-submit'].click()">
        <b-spinner small v-if="isBusy" />
        Tambahkan
      </b-button>
    </template>
  </b-modal>
</template>