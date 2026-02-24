<script>
import axios from 'axios'
import Swal from 'sweetalert2'
import { destroy as doDestroy } from '~/plugins/swal'

export default {
  props: {
    satkerId: {
      required: true,
      type: Number,
    }
  },

  data() {
    return {
      isBusy: {
        getData: false,
        getOptions: false,
        submit: false,
      },
      data: [],
      options: {
        pengampu: [],
        jabatan: [],
      },
      dataTemp: {
        id: null,
        satuan_kerja_id: this.satkerId,
        jenis_indikator: null,
        indikator: null,
        pengampu_id: null,
        is_create_pengampu: false,
        created_pengampu: null,
        sasaran_id: null,
        is_create_sasaran: false,
        sasaran_created: null,
        jf: [],
        is_edit_jf: false,
        keterangan: null,
      },
    }
  },

  computed: {
    sasaranOptions() {
      if (!this.dataTemp.pengampu_id) {
        return []
      }

      return this.options.pengampu.find(_ => _.id == this.dataTemp.pengampu_id)?.sasaran || []
    },
  },

  watch: {
    satkerId() {
      this.getData()
    },
  },

  mounted() {
    this.getData()
  },

  methods: {
    async getData() {
      try {
        this.isBusy.getData = true

        const { data } = await axios.get('kamus-indikator-fungsional-manual', {
          params: {
            satuan_kerja_id: this.satkerId,
          }
        })

        this.data = data
      } catch (error) {
        throw error
      } finally {
        this.isBusy.getData = false
      }
    },
    showModalForm(data = null) {
      if (!data) {
        this.dataTemp = {
          id: null,
          satuan_kerja_id: this.satkerId,
          jenis_indikator: 'CSF Output',
          indikator: null,
          pengampu_id: null,
          is_create_pengampu: false,
          created_pengampu: null,
          sasaran_id: null,
          is_create_sasaran: false,
          sasaran_created: null,
          jf: [],
          is_edit_jf: false,
          keterangan: null,
        }
      } else {
        this.dataTemp = {
          id: data.id,
          satuan_kerja_id: data.satuan_kerja_id,
          jenis_indikator: data.jenis_indikator,
          indikator: data.indikator,
          pengampu_id: data.sasaran.pengampu_id,
          is_create_pengampu: false,
          created_pengampu: null,
          sasaran_id: data.sasaran_id,
          is_create_sasaran: false,
          sasaran_created: null,
          jf: data.sasaran.pengampu.jf.map(_ => _.jabatan_id),
          is_edit_jf: false,
          keterangan: data.keterangan,
        }
      }

      this.getOptions()
      this.$bvModal.show('form-modal')
    },
    async getOptions() {
      const { data } = await axios.get('kamus-indikator-fungsional-manual/options', {
        params: {
          satuan_kerja_id: this.satkerId,
        }
      })

      this.options.pengampu = data.pengampu
      this.options.jabatan = data.jabatan
    },
    pengampuChanged() {
      // reset sasaran
      this.dataTemp.sasaran_id = null

      // set jf
      this.setDataTempJf()
    },
    setDataTempJf() {
      if (!this.dataTemp.pengampu_id) {
        this.dataTemp.jf = []
      } else {
        this.dataTemp.jf = this.options.pengampu.find(_ => _.id == this.dataTemp.pengampu_id)?.jf?.map(_ => _.jabatan_id) || []
      }
    },
    createPengampuChanged() {
      this.dataTemp.pengampu_id = null
      this.dataTemp.created_pengampu = null
      this.dataTemp.jf = []
    },
    editJfChanged(value) {
      // reset ke nilai awal
      if (!value) {
        this.setDataTempJf()
      }
    },
    async submit() {
      if (!this.dataTemp.jf.length) {
        Swal.fire('Error', 'Jabatan Fungsional tidak boleh kosong', 'error')
        return false
      }

      try {
        this.isBusy.submit = true
        await axios.post('kamus-indikator-fungsional-manual', this.dataTemp)
        Swal.fire('Berhasil', 'Berhasil simpan data', 'success')
        this.$bvModal.hide('form-modal')
        this.getData()
      } catch (error) {
        throw error
      } finally {
        this.isBusy.submit = false
      }
    },
    destroy(index, id) {
      doDestroy({
        preConfirm: async () => {
          return axios.delete(`/kamus-indikator-fungsional-manual/${id}`)
            .then(() => {
              this.data.splice(index, 1)

              return true
            })
        }
      })
    },
  }
}
</script>

<template>
  <b-card>
    <div class="text-right mb-3">
      <b-button variant="primary" @click="showModalForm()">
        <i class="ti-plus" aria-hidden="true"></i> Tambah
      </b-button>
    </div>
    <b-table-simple :aria-busy="isBusy.getData" responsive bordered striped hover>
      <b-thead class="text-center align-middle" head-variant="info">
        <b-tr>
          <b-th>No</b-th>
          <b-th>Nama Jenis Indikator</b-th>
          <b-th>Sasaran</b-th>
          <b-th>Indikator</b-th>
          <b-th>Pengampu</b-th>
          <b-th>Jabatan Fungsional</b-th>
          <b-th width="300">Keterangan</b-th>
          <b-th>Aksi</b-th>
        </b-tr>
      </b-thead>
      <b-tbody>
        <b-tr v-if="isBusy.getData || !data.length">
          <b-td colspan="8" class="text-center">
            <b-spinner v-if="isBusy.getData" />
            <b v-else>Tidak ada data</b>
          </b-td>
        </b-tr>
        <b-tr v-else v-for="(item, index) in data" :key="index">
          <b-th>{{ index + 1 }}</b-th>
          <b-td>{{ item.jenis_indikator }}</b-td>
          <b-td>{{ item.sasaran.sasaran }}</b-td>
          <b-td>{{ item.indikator }}</b-td>
          <b-td>{{ item.sasaran.pengampu.pengampu }}</b-td>
          <b-td>
            <ul v-if="item.sasaran.pengampu.jf.length">
              <li v-for="(jf, index) of item.sasaran.pengampu.jf" :key="index">{{ jf.jabatan?.jf?.jf_nama || '-' }}</li>
            </ul>
          </b-td>
          <b-td>{{ item.keterangan }}</b-td>
          <b-td>
            <div class="text-nowrap">
              <button @click="showModalForm(item)" class="btn btn-outline-warning btn-sm m-1 rounded-circle" title="Edit">
                <i class="fa fa-pencil" aria-hidden="true"></i>
              </button>
              <b-button @click="destroy(index, item.id)" variant="outline-danger" size="sm" class="m-1 rounded-circle" title="Hapus">
                <i class="fa fa-trash" aria-hidden="true"></i>
              </b-button>
            </div>
          </b-td>
        </b-tr>
      </b-tbody>
    </b-table-simple>

    <b-modal
      id="form-modal"
      size="lg"
      @ok="$event.preventDefault(); $refs['submit-form'].click()"
      title="Olah data"
      no-close-on-backdrop
      :hide-header-close="isBusy.submit"
    >
      <form @submit.stop.prevent="submit">
        <b-form-group
          label="Nama Jenis Indikator"
          label-for="jenis-indikator"
        >
          <b-form-input
            v-model="dataTemp.jenis_indikator"
            required
          ></b-form-input>
        </b-form-group>
        <b-form-group
          label="Pengampu"
          label-for="pengampu"
        >
          <v-select
            v-if="!dataTemp.is_create_pengampu"
            v-model="dataTemp.pengampu_id"
            @input="pengampuChanged"
            :options="options.pengampu"
            label="pengampu"
            :reduce="opt => opt.id"
            :clearable="false"
            placeholder="Pilih nama pengampu"
          >
            <template #search="{attributes, events}">
              <input
                class="vs__search"
                :required="!dataTemp.pengampu_id"
                v-bind="attributes"
                v-on="events"
              />
            </template>
          </v-select>
          <b-form-input
            v-else
            v-model="dataTemp.created_pengampu"
            placeholder="Nama pengampu"
            required
          ></b-form-input>
          <b-form-checkbox
            v-model="dataTemp.is_create_pengampu"
            @input="createPengampuChanged"
            :value="true"
            :unchecked-value="false"
          >
            Tambah baru
          </b-form-checkbox>
        </b-form-group>
        <b-form-group
          label="Jabatan Fungsional"
          label-for="jf"
        >
          <v-select
            v-model="dataTemp.jf"
            :options="options.jabatan"
            label="jf_nama"
            :reduce="opt => opt.jabatan_id"
            :clearable="false"
            multiple
            :disabled="!dataTemp.is_edit_jf"
          >
            <template #search="{attributes, events}">
              <input
                class="vs__search"
                :required="!dataTemp.jf || !dataTemp.jf.length"
                v-bind="attributes"
                v-on="events"
              />
            </template>
          </v-select>
          <b-form-checkbox
            v-model="dataTemp.is_edit_jf"
            @input="editJfChanged"
            :value="true"
            :unchecked-value="false"
          >
            Ubah Jabatan Fungsional
          </b-form-checkbox>
          <small v-if="dataTemp.is_edit_jf">Mengubah Jabatan Fungsional akan mengubah semua yang ada pada pengampu tersebut</small>
        </b-form-group>
        <b-form-group
          label="Sasaran"
          label-for="sasaran"
        >
          <v-select
            v-if="!dataTemp.is_create_sasaran"
            v-model="dataTemp.sasaran_id"
            :options="sasaranOptions"
            label="sasaran"
            :reduce="opt => opt.id"
            :clearable="false"
            placeholder="Pilih sasaran"
          >
            <template #search="{attributes, events}">
              <input
                class="vs__search"
                :required="!dataTemp.sasaran_id"
                v-bind="attributes"
                v-on="events"
              />
            </template>
          </v-select>
          <b-form-input
            v-else
            v-model="dataTemp.sasaran_created"
            placeholder="Nama sasaran"
            required
          ></b-form-input>
          <b-form-checkbox
            v-model="dataTemp.is_create_sasaran"
            @input="dataTemp.sasaran_id = null; dataTemp.sasaran_created = null"
            :value="true"
            :unchecked-value="false"
          >
            Tambah baru
          </b-form-checkbox>
        </b-form-group>
        <b-form-group
          label="Indikator"
          label-for="indikator"
        >
          <b-form-input
            id="indikator"
            v-model="dataTemp.indikator"
            required
          ></b-form-input>
        </b-form-group>
        <b-form-group
          label="Keterangan"
          label-for="keterangan"
        >
          <b-form-textarea
            id="keterangan"
            v-model="dataTemp.keterangan"
          ></b-form-textarea>
        </b-form-group>
        <button ref="submit-form" class="d-none"></button>
      </form>

      <template #modal-footer="{ok, cancel}">
        <b-button @click="cancel" :disabled="isBusy.submit">Batal</b-button>
        <b-button variant="primary" @click="ok" :disabled="isBusy.submit">
          <b-spinner small v-if="isBusy.submit"></b-spinner>
          {{ dataTemp.id ? 'Simpan' : 'Tambahkan' }}
        </b-button>
      </template>
    </b-modal>
  </b-card>
</template>
