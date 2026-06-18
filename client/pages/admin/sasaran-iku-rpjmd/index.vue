<script>
import axios from 'axios'
import Swal from 'sweetalert2'
import { destroy as doDestroy } from '~/plugins/swal'

export default {
  middleware: ['auth', 'role-super'],

  data() {
    const tahunMulai = this.$helper.getTahunMulai()

    return {
      periode: {
        tahun_mulai: tahunMulai,
        tahun_selesai: tahunMulai + 5,
      },
      sasaran: [],
      indikator: [],
      sasaranForm: this.emptySasaranForm(),
      indikatorForm: this.emptyIndikatorForm(),
      isBusy: {
        getData: false,
        saveSasaran: false,
        saveIndikator: false,
      },
      sasaranFields: [
        { key: 'nomor', label: 'No' },
        { key: 'sasaran', label: 'Sasaran Strategis RPJMD' },
        { key: 'action', label: 'Aksi', class: 'text-center' },
      ],
      indikatorFields: [
        { key: 'nomor', label: 'No' },
        { key: 'indikator', label: 'Indikator Sasaran Strategis / IKU Bupati' },
        { key: 'action', label: 'Aksi', class: 'text-center' },
      ],
    }
  },

  mounted() {
    this.getData()
  },

  computed: {
    nextSasaranNomor() {
      return this.getNextNomor(this.sasaran, this.sasaranForm.id)
    },
    nextIndikatorNomor() {
      return this.getNextNomor(this.indikator, this.indikatorForm.id)
    },
  },

  methods: {
    emptySasaranForm() {
      return {
        id: null,
        nomor: null,
        sasaran: '',
      }
    },
    emptyIndikatorForm() {
      return {
        id: null,
        nomor: null,
        indikator: '',
      }
    },
    getNextNomor(items, currentId = null) {
      const current = currentId
        ? items.find((item) => Number(item.id) === Number(currentId))
        : null

      if (current) {
        return current.nomor
      }

      return items.reduce((max, item) => Math.max(max, Number(item.nomor) || 0), 0) + 1
    },
    prepareSasaranForm() {
      if (!this.sasaranForm.nomor) {
        this.sasaranForm.nomor = this.nextSasaranNomor
      }
    },
    prepareIndikatorForm() {
      if (!this.indikatorForm.nomor) {
        this.indikatorForm.nomor = this.nextIndikatorNomor
      }
    },
    async getData() {
      try {
        this.isBusy.getData = true

        const { data } = await axios.get('admin/sasaran-iku-rpjmd')

        this.periode = data.periode
        this.sasaran = data.sasaran
        this.indikator = data.indikator
        this.prepareSasaranForm()
        this.prepareIndikatorForm()
      } catch (error) {
        Swal.fire({
          type: 'error',
          title: error.response?.data?.message || 'Gagal mengambil data!',
        })
      } finally {
        this.isBusy.getData = false
      }
    },
    editSasaran(item) {
      this.sasaranForm = { ...item }
    },
    resetSasaranForm() {
      this.sasaranForm = this.emptySasaranForm()
      this.prepareSasaranForm()
    },
    async submitSasaran() {
      try {
        this.isBusy.saveSasaran = true

        if (this.sasaranForm.id) {
          await axios.patch(`admin/sasaran-iku-rpjmd/sasaran/${this.sasaranForm.id}`, this.sasaranForm)
        } else {
          await axios.post('admin/sasaran-iku-rpjmd/sasaran', this.sasaranForm)
        }

        Swal.fire({
          type: 'success',
          title: 'Berhasil simpan data'
        })

        this.resetSasaranForm()
        this.getData()
      } catch (error) {
        Swal.fire({
          type: 'error',
          title: error.response?.data?.message || 'Gagal simpan data!',
        })
      } finally {
        this.isBusy.saveSasaran = false
      }
    },
    destroySasaran(id) {
      doDestroy({
        preConfirm: async () => {
          await axios.delete(`admin/sasaran-iku-rpjmd/sasaran/${id}`)
          this.resetSasaranForm()
          this.getData()
          return true
        }
      })
    },
    editIndikator(item) {
      this.indikatorForm = { ...item }
    },
    resetIndikatorForm() {
      this.indikatorForm = this.emptyIndikatorForm()
      this.prepareIndikatorForm()
    },
    async submitIndikator() {
      try {
        this.isBusy.saveIndikator = true

        if (this.indikatorForm.id) {
          await axios.patch(`admin/sasaran-iku-rpjmd/indikator/${this.indikatorForm.id}`, this.indikatorForm)
        } else {
          await axios.post('admin/sasaran-iku-rpjmd/indikator', this.indikatorForm)
        }

        Swal.fire({
          type: 'success',
          title: 'Berhasil simpan data'
        })

        this.resetIndikatorForm()
        this.getData()
      } catch (error) {
        Swal.fire({
          type: 'error',
          title: error.response?.data?.message || 'Gagal simpan data!',
        })
      } finally {
        this.isBusy.saveIndikator = false
      }
    },
    destroyIndikator(id) {
      doDestroy({
        preConfirm: async () => {
          await axios.delete(`admin/sasaran-iku-rpjmd/indikator/${id}`)
          this.resetIndikatorForm()
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
      <h5 class="mb-2 mb-md-0">Master Sasaran & IKU RPJMD</h5>
      <b-badge variant="info" class="px-3 py-2">
        Periode {{ periode.tahun_mulai }}-{{ periode.tahun_selesai }}
      </b-badge>
    </div>

    <b-overlay :show="isBusy.getData" rounded opacity="0.15">
      <b-tabs content-class="pt-3">
        <b-tab title="Sasaran Strategis" active>
          <form @submit.prevent="submitSasaran">
            <b-form-group label-cols="12" label-cols-md="2" label="Nomor" label-class="font-weight-bold" label-for="nomor-sasaran">
              <b-form-input id="nomor-sasaran" v-model.number="sasaranForm.nomor" type="number" min="1" required></b-form-input>
            </b-form-group>

            <b-form-group label-cols="12" label-cols-md="2" label="Sasaran Strategis RPJMD" label-class="font-weight-bold" label-for="sasaran">
              <b-form-textarea id="sasaran" v-model="sasaranForm.sasaran" rows="3" max-rows="8" required></b-form-textarea>
            </b-form-group>

            <div class="text-right">
              <b-button v-if="sasaranForm.id" variant="outline-secondary" class="mr-2" type="button" @click="resetSasaranForm">
                Batal
              </b-button>
              <b-button variant="primary" :disabled="isBusy.saveSasaran" type="submit">
                <i v-if="isBusy.saveSasaran" class="fa fa-spinner fa-pulse" aria-hidden="true"></i>
                <i v-else class="fa fa-floppy-o" aria-hidden="true"></i>
                Simpan Sasaran
              </b-button>
            </div>
          </form>

          <b-table responsive hover striped :items="sasaran" :fields="sasaranFields" show-empty class="table-bordered mt-3" head-variant="info">
            <template #cell(sasaran)="row">
              <strong>{{ row.item.sasaran }}</strong>
            </template>

            <template #cell(action)="row">
              <div class="text-nowrap">
                <b-button @click="editSasaran(row.item)" variant="outline-warning" size="sm" class="m-1 rounded-circle" title="Edit">
                  <i class="fa fa-pencil" aria-hidden="true"></i>
                </b-button>
                <b-button @click="destroySasaran(row.item.id)" variant="outline-danger" size="sm" class="m-1 rounded-circle" title="Hapus">
                  <i class="fa fa-trash" aria-hidden="true"></i>
                </b-button>
              </div>
            </template>
          </b-table>
        </b-tab>

        <b-tab title="Indikator / IKU Bupati">
          <form @submit.prevent="submitIndikator">
            <b-form-group label-cols="12" label-cols-md="2" label="Nomor" label-class="font-weight-bold" label-for="nomor-indikator">
              <b-form-input id="nomor-indikator" v-model.number="indikatorForm.nomor" type="number" min="1" required></b-form-input>
            </b-form-group>

            <b-form-group label-cols="12" label-cols-md="2" label="Indikator / IKU Bupati" label-class="font-weight-bold" label-for="indikator">
              <b-form-textarea id="indikator" v-model="indikatorForm.indikator" rows="3" max-rows="8" required></b-form-textarea>
            </b-form-group>

            <div class="text-right">
              <b-button v-if="indikatorForm.id" variant="outline-secondary" class="mr-2" type="button" @click="resetIndikatorForm">
                Batal
              </b-button>
              <b-button variant="primary" :disabled="isBusy.saveIndikator" type="submit">
                <i v-if="isBusy.saveIndikator" class="fa fa-spinner fa-pulse" aria-hidden="true"></i>
                <i v-else class="fa fa-floppy-o" aria-hidden="true"></i>
                Simpan Indikator
              </b-button>
            </div>
          </form>

          <b-table responsive hover striped :items="indikator" :fields="indikatorFields" show-empty class="table-bordered mt-3" head-variant="info">
            <template #cell(indikator)="row">
              <strong>{{ row.item.indikator }}</strong>
            </template>

            <template #cell(action)="row">
              <div class="text-nowrap">
                <b-button @click="editIndikator(row.item)" variant="outline-warning" size="sm" class="m-1 rounded-circle" title="Edit">
                  <i class="fa fa-pencil" aria-hidden="true"></i>
                </b-button>
                <b-button @click="destroyIndikator(row.item.id)" variant="outline-danger" size="sm" class="m-1 rounded-circle" title="Hapus">
                  <i class="fa fa-trash" aria-hidden="true"></i>
                </b-button>
              </div>
            </template>
          </b-table>
        </b-tab>
      </b-tabs>
    </b-overlay>
  </b-card>
</template>
