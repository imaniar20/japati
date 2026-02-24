<script>
import axios from 'axios'
import Swal from 'sweetalert2'
import { debounce } from 'lodash'

export default {
  data() {
    let satker = null

    if (this.$role.isSuper()) {
      satker = 1030
    } else if (this.$role.isSetda()) {
      satker = 100103010000
    } else {
      satker = this.$store.getters['auth/user'].satuan_kerja_id
    }

    return {
      data: [],
      filter: {
        satuan_kerja_id: satker,
        tipe: '',
      },
      isBusy: {
        getData: false,
        save: false,
      },
      jabatanList: [],
    }
  },

  computed: {
    filteredData() {
      return this.data.filter(_ => this.filter.tipe ? _.tipe == this.filter.tipe : true)
    }
  },

  watch: {
    'filter.satuan_kerja_id'() {
      this.emitSatker()
      this.getData()
    },
  },

  mounted() {
    this.emitSatker()
    this.getData()
  },

  methods: {
    async getData() {
      this.isBusy.getData = true

      const { data: { data, jabatanList } } = await axios.get('kamus-indikator-fungsional', {
        params: this.filter
      })

      this.data = data
      this.jabatanList = jabatanList
      this.isBusy.getData = false
    },
    updateItemDebounce: debounce(function (...args) {
      this.updateItem(...args)
    }, 500),
    updateItem(index, key, value) {
      const data = this.data[index]

      if (!data.kamus_indikator_fungsional) {
        data['kamus_indikator_fungsional'] = {}
      }

      if (!data.temp) {
        data['temp'] = {}
      }

      data.kamus_indikator_fungsional[key] = value
      data.temp[key] = value

      this.$set(this.data, index, data)
    },
    async save(index) {
      try {
        const data = this.data[index]
        if (!data.temp) {
          return false
        }

        this.isBusy.save = index + 1

        await axios.post('kamus-indikator-fungsional', {
          class: data.class,
          id: data.id,
          data: data.temp,
        })

        Swal.fire('Berhasil', 'Berhasil simpan data', 'success')
      } catch (error) {
        throw error
      } finally {
        this.isBusy.save = false
      }
    },
    emitSatker() {
      this.$emit('satker', this.filter.satuan_kerja_id)
    }
  }
}
</script>

<template>
  <b-card>
    <div class="d-flex gap-5">
      <FilterSatuanKerja v-if="$role.isSuper() || $role.isSetda()" v-model="filter.satuan_kerja_id" :is-setda="$role.isSetda()" :selectProps="{clearable: false, appendToBody: true}" />
      <b-form-group label="Tampilkan Indikator" label-class="font-weight-bold">
        <b-form-select v-model="filter.tipe">
          <b-form-select-option value="">Semua</b-form-select-option>
          <b-form-select-option value="Indikator Program">Indikator Program</b-form-select-option>
          <b-form-select-option value="Indikator Kegiatan">Indikator Kegiatan</b-form-select-option>
          <b-form-select-option value="Indikator Sub Kegiatan">Indikator Sub Kegiatan</b-form-select-option>
        </b-form-select>
      </b-form-group>
    </div>

    <b-table-simple :aria-busy="isBusy.getData" responsive bordered striped hover>
      <b-thead class="text-center align-middle" head-variant="info">
        <b-tr>
          <b-th>No</b-th>
          <b-th>Indikator</b-th>
          <b-th>Nama Indikator Outcome/Output</b-th>
          <b-th>Nama Indikator SIPD/Kemendagri</b-th>
          <b-th width="300">Pengampu</b-th>
          <b-th width="600">Jabatan Fungsional</b-th>
          <b-th width="300">Keterangan</b-th>
          <b-th>Aksi</b-th>
        </b-tr>
      </b-thead>
      <b-tbody>
        <b-tr v-if="isBusy.getData">
          <b-td colspan="8" class="text-center"><b-spinner /></b-td>
        </b-tr>
        <b-tr v-else v-for="(item, index) in filteredData" :key="index">
          <b-th>{{ index + 1 }}</b-th>
          <b-td>{{ item.tipe }}</b-td>
          <b-td>{{ item.indikator }}</b-td>
          <b-td>{{ item.indikator_kemendagri }}</b-td>
          <b-td>
            <b-input
              :value="item.kamus_indikator_fungsional?.pengampu"
              @input="updateItemDebounce(index, 'pengampu', $event)"
            ></b-input>
          </b-td>
          <b-td>
            <v-select
              :value="item.kamus_indikator_fungsional?.jabatan_id"
              @input="updateItem(index, 'jabatan_id', $event)"
              :options="jabatanList"
              label="jf_nama"
              :reduce="opt => opt.jabatan_id"
              :clearable="false"
            >
            </v-select>
          </b-td>
          <b-td>
            <b-form-textarea
              :value="item.kamus_indikator_fungsional?.keterangan"
              @input="updateItemDebounce(index, 'keterangan', $event)"
              placeholder="Keterangan..."
            ></b-form-textarea>
          </b-td>
          <b-td>
            <b-button @click="save(index)" size="sm" variant="primary" :disabled="Boolean(isBusy.save || !item.temp)">
              <b-spinner small v-if="isBusy.save == index + 1"></b-spinner>
              Simpan
            </b-button>
          </b-td>
        </b-tr>
      </b-tbody>
    </b-table-simple>
  </b-card>
</template>