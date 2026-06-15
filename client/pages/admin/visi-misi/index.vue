<script>
import axios from 'axios'
import Swal from 'sweetalert2'
import { destroy as doDestroy } from '~/plugins/swal'

export default {
  middleware: ['auth', 'role-super'],

  data() {
    const tahunMulai = this.$helper.getTahunMulai()
    const periode = {
      tahun_mulai: tahunMulai,
      tahun_selesai: tahunMulai + 5,
    }

    return {
      periode,
      visi: [],
      misi: [],
      tujuan: [],
      indikatorTujuan: [],
      visiForm: this.emptyVisiForm(periode),
      misiForm: this.emptyMisiForm(),
      tujuanForm: this.emptyTujuanForm(),
      indikatorTujuanForm: this.emptyIndikatorTujuanForm(),
      isBusy: {
        getData: false,
        saveVisi: false,
        saveMisi: false,
        saveTujuan: false,
        saveIndikatorTujuan: false,
      },
      misiFields: [
        { key: 'visi', label: 'Visi' },
        { key: 'nomor', label: 'No' },
        { key: 'misi', label: 'Misi' },
        { key: 'action', label: 'Aksi', class: 'text-center' },
      ],
      tujuanFields: [
        { key: 'misi', label: 'Misi' },
        { key: 'nomor', label: 'No' },
        { key: 'tujuan', label: 'Tujuan' },
        { key: 'action', label: 'Aksi', class: 'text-center' },
      ],
      indikatorTujuanFields: [
        { key: 'tujuan', label: 'Tujuan' },
        { key: 'nomor', label: 'No' },
        { key: 'indikator', label: 'Indikator Tujuan' },
        { key: 'action', label: 'Aksi', class: 'text-center' },
      ],
    }
  },

  mounted() {
    this.getData()
  },

  computed: {
    visiPreview() {
      return `Visi ${this.visiForm.tahun_mulai || '-'}-${this.visiForm.tahun_selesai || '-'}`
    },
    misiNomorPreview() {
      return this.getNextNomor(this.misi, 'visi_id', this.misiForm.visi_id, this.misiForm.id)
    },
    tujuanNomorPreview() {
      return this.getNextNomor(this.tujuan, 'misi_id', this.tujuanForm.misi_id, this.tujuanForm.id)
    },
    indikatorTujuanNomorPreview() {
      return this.getNextNomor(
        this.indikatorTujuan,
        'tujuan_id',
        this.indikatorTujuanForm.tujuan_id,
        this.indikatorTujuanForm.id
      )
    },
  },

  methods: {
    emptyVisiForm(periode = this.periode) {
      return {
        id: null,
        tahun_mulai: periode.tahun_mulai,
        tahun_selesai: periode.tahun_selesai,
      }
    },
    emptyMisiForm() {
      return {
        id: null,
        visi_id: null,
        nomor: null,
        misi: '',
      }
    },
    emptyTujuanForm() {
      return {
        id: null,
        visi_id: null,
        misi_id: null,
        nomor: null,
        tujuan: '',
      }
    },
    emptyIndikatorTujuanForm() {
      return {
        id: null,
        visi_id: null,
        misi_id: null,
        tujuan_id: null,
        nomor: null,
        indikator: '',
      }
    },
    getNextNomor(items, parentKey, parentId, currentId = null) {
      if (!parentId) {
        return '-'
      }

      const current = currentId
        ? items.find((item) => Number(item.id) === Number(currentId))
        : null

      if (current && Number(current[parentKey]) === Number(parentId)) {
        return current.nomor
      }

      const maxNomor = items
        .filter((item) => Number(item[parentKey]) === Number(parentId))
        .reduce((max, item) => Math.max(max, Number(item.nomor) || 0), 0)

      return maxNomor + 1
    },
    async getData() {
      this.isBusy.getData = true

      const { data } = await axios.get('admin/visi-misi')

      this.periode = data.periode
      if (!this.visiForm.id) {
        this.resetVisiForm()
      }
      this.visi = data.visi
      this.misi = data.misi
      this.tujuan = data.tujuan
      this.indikatorTujuan = data.indikator_tujuan
      this.isBusy.getData = false
    },
    editVisi(item) {
      const periode = String(item.visi || '').match(/(\d{4})\D+(\d{4})/)

      this.visiForm = {
        id: item.id,
        tahun_mulai: periode ? parseInt(periode[1]) : item.tahun_mulai,
        tahun_selesai: periode ? parseInt(periode[2]) : item.tahun_mulai + 5,
      }
    },
    resetVisiForm() {
      this.visiForm = this.emptyVisiForm()
    },
    async submitVisi() {
      try {
        this.isBusy.saveVisi = true

        if (this.visiForm.id) {
          await axios.patch(`admin/visi-misi/visi/${this.visiForm.id}`, this.visiForm)
        } else {
          await axios.post('admin/visi-misi/visi', this.visiForm)
        }

        Swal.fire({
          type: 'success',
          title: 'Berhasil simpan data'
        })

        this.resetVisiForm()
        this.getData()
      } catch (error) {
        Swal.fire({
          type: 'error',
          title: error.response?.data?.message || 'Gagal simpan data!',
        })
      } finally {
        this.isBusy.saveVisi = false
      }
    },
    destroyVisi(id) {
      doDestroy({
        preConfirm: async () => {
          await axios.delete(`admin/visi-misi/visi/${id}`)
          this.resetVisiForm()
          this.getData()
          return true
        }
      })
    },
    editMisi(item) {
      this.misiForm = { ...item }
    },
    resetMisiForm() {
      this.misiForm = this.emptyMisiForm()
    },
    async submitMisi() {
      try {
        this.isBusy.saveMisi = true

        if (this.misiForm.id) {
          await axios.patch(`admin/visi-misi/misi/${this.misiForm.id}`, this.misiForm)
        } else {
          await axios.post('admin/visi-misi/misi', this.misiForm)
        }

        Swal.fire({
          type: 'success',
          title: 'Berhasil simpan data'
        })

        this.resetMisiForm()
        this.getData()
      } catch (error) {
        Swal.fire({
          type: 'error',
          title: error.response?.data?.message || 'Gagal simpan data!',
        })
      } finally {
        this.isBusy.saveMisi = false
      }
    },
    destroyMisi(id) {
      doDestroy({
        preConfirm: async () => {
          await axios.delete(`admin/visi-misi/misi/${id}`)
          this.resetMisiForm()
          this.getData()
          return true
        }
      })
    },
    editTujuan(item) {
      this.tujuanForm = {
        ...item,
        misi_id: null,
        visi_id: item.misi ? item.misi.visi_id : null,
      }

      this.$nextTick(() => {
        this.tujuanForm.misi_id = item.misi_id
      })
    },
    resetTujuanForm() {
      this.tujuanForm = this.emptyTujuanForm()
    },
    async submitTujuan() {
      try {
        this.isBusy.saveTujuan = true

        if (this.tujuanForm.id) {
          await axios.patch(`admin/visi-misi/tujuan/${this.tujuanForm.id}`, this.tujuanForm)
        } else {
          await axios.post('admin/visi-misi/tujuan', this.tujuanForm)
        }

        Swal.fire({
          type: 'success',
          title: 'Berhasil simpan data'
        })

        this.resetTujuanForm()
        this.getData()
      } catch (error) {
        Swal.fire({
          type: 'error',
          title: error.response?.data?.message || 'Gagal simpan data!',
        })
      } finally {
        this.isBusy.saveTujuan = false
      }
    },
    destroyTujuan(id) {
      doDestroy({
        preConfirm: async () => {
          await axios.delete(`admin/visi-misi/tujuan/${id}`)
          this.resetTujuanForm()
          this.getData()
          return true
        }
      })
    },
    editIndikatorTujuan(item) {
      const misi = item.tujuan
        ? this.misi.find((misi) => misi.id === item.tujuan.misi_id)
        : null

      this.indikatorTujuanForm = {
        ...item,
        misi_id: null,
        tujuan_id: null,
        visi_id: misi ? misi.visi_id : null,
      }

      this.$nextTick(() => {
        this.indikatorTujuanForm.misi_id = item.tujuan ? item.tujuan.misi_id : null

        this.$nextTick(() => {
          this.indikatorTujuanForm.tujuan_id = item.tujuan_id
        })
      })
    },
    resetIndikatorTujuanForm() {
      this.indikatorTujuanForm = this.emptyIndikatorTujuanForm()
    },
    async submitIndikatorTujuan() {
      try {
        this.isBusy.saveIndikatorTujuan = true

        if (this.indikatorTujuanForm.id) {
          await axios.patch(`admin/visi-misi/indikator-tujuan/${this.indikatorTujuanForm.id}`, this.indikatorTujuanForm)
        } else {
          await axios.post('admin/visi-misi/indikator-tujuan', this.indikatorTujuanForm)
        }

        Swal.fire({
          type: 'success',
          title: 'Berhasil simpan data'
        })

        this.resetIndikatorTujuanForm()
        this.getData()
      } catch (error) {
        Swal.fire({
          type: 'error',
          title: error.response?.data?.message || 'Gagal simpan data!',
        })
      } finally {
        this.isBusy.saveIndikatorTujuan = false
      }
    },
    destroyIndikatorTujuan(id) {
      doDestroy({
        preConfirm: async () => {
          await axios.delete(`admin/visi-misi/indikator-tujuan/${id}`)
          this.resetIndikatorTujuanForm()
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
      <h5 class="mb-2 mb-md-0">Master Perencanaan RPJMD</h5>
      <b-badge variant="info" class="px-3 py-2">
        Periode {{ periode.tahun_mulai }}-{{ periode.tahun_selesai }}
      </b-badge>
    </div>

    <b-overlay :show="isBusy.getData" rounded opacity="0.15">
      <section>
        <h6 class="font-weight-bold">Visi</h6>

        <form @submit.prevent="submitVisi">
          <b-form-group label-cols="12" label-cols-md="2" label="Periode Awal" label-class="font-weight-bold" label-for="visi-tahun-mulai">
            <b-form-input id="visi-tahun-mulai" v-model.number="visiForm.tahun_mulai" type="number" min="1900" step="1" required readonly></b-form-input>
          </b-form-group>

          <b-form-group label-cols="12" label-cols-md="2" label="Periode Akhir" label-class="font-weight-bold" label-for="visi-tahun-selesai">
            <b-form-input id="visi-tahun-selesai" v-model.number="visiForm.tahun_selesai" type="number" min="1900" step="1" required readonly></b-form-input>
          </b-form-group>

          <b-form-group label-cols="12" label-cols-md="2" label="Hasil" label-class="font-weight-bold" label-for="visi">
            <b-form-input id="visi" :value="visiPreview" readonly></b-form-input>
          </b-form-group>

          <div class="text-right">
            <b-button v-if="visiForm.id" variant="outline-secondary" class="mr-2" type="button" @click="resetVisiForm">
              Batal
            </b-button>
            <b-button variant="primary" :disabled="isBusy.saveVisi || (!visiForm.id && visi.length > 0)" type="submit">
              <i v-if="isBusy.saveVisi" class="fa fa-spinner fa-pulse" aria-hidden="true"></i>
              <i v-else class="fa fa-floppy-o" aria-hidden="true"></i>
              Simpan Visi
            </b-button>
          </div>
        </form>

        <b-table responsive hover striped :items="visi" :fields="[
          { key: 'visi', label: 'Visi' },
          { key: 'action', label: 'Aksi', class: 'text-center' },
        ]" show-empty class="table-bordered mt-3" head-variant="info">
          <template #cell(action)="row">
            <div class="text-nowrap">
              <b-button @click="editVisi(row.item)" variant="outline-warning" size="sm" class="m-1 rounded-circle" title="Edit">
                <i class="fa fa-pencil" aria-hidden="true"></i>
              </b-button>
              <b-button @click="destroyVisi(row.item.id)" variant="outline-danger" size="sm" class="m-1 rounded-circle" title="Hapus">
                <i class="fa fa-trash" aria-hidden="true"></i>
              </b-button>
            </div>
          </template>
        </b-table>
      </section>

      <hr class="my-4">

      <section>
        <h6 class="font-weight-bold">Misi</h6>

        <form @submit.prevent="submitMisi">
          <OptionVisi v-model="misiForm.visi_id" />

          <b-form-group label-cols="12" label-cols-md="2" label="Nomor Otomatis" label-class="font-weight-bold" label-for="nomor">
            <b-form-input id="nomor" :value="misiNomorPreview" readonly></b-form-input>
          </b-form-group>

          <b-form-group label-cols="12" label-cols-md="2" label="Misi" label-class="font-weight-bold" label-for="misi">
            <b-form-textarea id="misi" v-model="misiForm.misi" rows="3" max-rows="8" required></b-form-textarea>
          </b-form-group>

          <div class="text-right">
            <b-button v-if="misiForm.id" variant="outline-secondary" class="mr-2" type="button" @click="resetMisiForm">
              Batal
            </b-button>
            <b-button variant="primary" :disabled="isBusy.saveMisi" type="submit">
              <i v-if="isBusy.saveMisi" class="fa fa-spinner fa-pulse" aria-hidden="true"></i>
              <i v-else class="fa fa-floppy-o" aria-hidden="true"></i>
              Simpan Misi
            </b-button>
          </div>
        </form>

        <b-table responsive hover striped :items="misi" :fields="misiFields" show-empty class="table-bordered mt-3" head-variant="info">
          <template #cell(visi)="row">
            {{ row.item.visi ? row.item.visi.visi : '-' }}
          </template>

          <template #cell(action)="row">
            <div class="text-nowrap">
              <b-button @click="editMisi(row.item)" variant="outline-warning" size="sm" class="m-1 rounded-circle" title="Edit">
                <i class="fa fa-pencil" aria-hidden="true"></i>
              </b-button>
              <b-button @click="destroyMisi(row.item.id)" variant="outline-danger" size="sm" class="m-1 rounded-circle" title="Hapus">
                <i class="fa fa-trash" aria-hidden="true"></i>
              </b-button>
            </div>
          </template>
        </b-table>
      </section>

      <hr class="my-4">

      <section>
        <h6 class="font-weight-bold">Tujuan</h6>

        <form @submit.prevent="submitTujuan">
          <OptionVisi v-model="tujuanForm.visi_id" />
          <OptionMisi v-model="tujuanForm.misi_id" :visi-id="tujuanForm.visi_id" depends-on-visi />

          <b-form-group label-cols="12" label-cols-md="2" label="Nomor Otomatis" label-class="font-weight-bold" label-for="nomor-tujuan">
            <b-form-input id="nomor-tujuan" :value="tujuanNomorPreview" readonly></b-form-input>
          </b-form-group>

          <b-form-group label-cols="12" label-cols-md="2" label="Tujuan" label-class="font-weight-bold" label-for="tujuan">
            <b-form-textarea id="tujuan" v-model="tujuanForm.tujuan" rows="3" max-rows="8" required></b-form-textarea>
          </b-form-group>

          <div class="text-right">
            <b-button v-if="tujuanForm.id" variant="outline-secondary" class="mr-2" type="button" @click="resetTujuanForm">
              Batal
            </b-button>
            <b-button variant="primary" :disabled="isBusy.saveTujuan" type="submit">
              <i v-if="isBusy.saveTujuan" class="fa fa-spinner fa-pulse" aria-hidden="true"></i>
              <i v-else class="fa fa-floppy-o" aria-hidden="true"></i>
              Simpan Tujuan
            </b-button>
          </div>
        </form>

        <b-table responsive hover striped :items="tujuan" :fields="tujuanFields" show-empty class="table-bordered mt-3" head-variant="info">
          <template #cell(misi)="row">
            {{ row.item.misi ? `${row.item.misi.nomor}. ${row.item.misi.misi}` : '-' }}
          </template>

          <template #cell(action)="row">
            <div class="text-nowrap">
              <b-button @click="editTujuan(row.item)" variant="outline-warning" size="sm" class="m-1 rounded-circle" title="Edit">
                <i class="fa fa-pencil" aria-hidden="true"></i>
              </b-button>
              <b-button @click="destroyTujuan(row.item.id)" variant="outline-danger" size="sm" class="m-1 rounded-circle" title="Hapus">
                <i class="fa fa-trash" aria-hidden="true"></i>
              </b-button>
            </div>
          </template>
        </b-table>
      </section>

      <hr class="my-4">

      <section>
        <h6 class="font-weight-bold">Indikator Tujuan</h6>

        <form @submit.prevent="submitIndikatorTujuan">
          <OptionVisi v-model="indikatorTujuanForm.visi_id" />
          <OptionMisi v-model="indikatorTujuanForm.misi_id" :visi-id="indikatorTujuanForm.visi_id" depends-on-visi />
          <OptionTujuan v-model="indikatorTujuanForm.tujuan_id" :misi-id="indikatorTujuanForm.misi_id" depends-on-misi />

          <b-form-group label-cols="12" label-cols-md="2" label="Nomor Otomatis" label-class="font-weight-bold" label-for="nomor-indikator-tujuan">
            <b-form-input id="nomor-indikator-tujuan" :value="indikatorTujuanNomorPreview" readonly></b-form-input>
          </b-form-group>

          <b-form-group label-cols="12" label-cols-md="2" label="Indikator Tujuan" label-class="font-weight-bold" label-for="indikator-tujuan">
            <b-form-textarea id="indikator-tujuan" v-model="indikatorTujuanForm.indikator" rows="3" max-rows="8" required></b-form-textarea>
          </b-form-group>

          <div class="text-right">
            <b-button v-if="indikatorTujuanForm.id" variant="outline-secondary" class="mr-2" type="button" @click="resetIndikatorTujuanForm">
              Batal
            </b-button>
            <b-button variant="primary" :disabled="isBusy.saveIndikatorTujuan" type="submit">
              <i v-if="isBusy.saveIndikatorTujuan" class="fa fa-spinner fa-pulse" aria-hidden="true"></i>
              <i v-else class="fa fa-floppy-o" aria-hidden="true"></i>
              Simpan Indikator Tujuan
            </b-button>
          </div>
        </form>

        <b-table responsive hover striped :items="indikatorTujuan" :fields="indikatorTujuanFields" show-empty class="table-bordered mt-3" head-variant="info">
          <template #cell(tujuan)="row">
            {{ row.item.tujuan ? `${row.item.tujuan.nomor}. ${row.item.tujuan.tujuan}` : '-' }}
          </template>

          <template #cell(action)="row">
            <div class="text-nowrap">
              <b-button @click="editIndikatorTujuan(row.item)" variant="outline-warning" size="sm" class="m-1 rounded-circle" title="Edit">
                <i class="fa fa-pencil" aria-hidden="true"></i>
              </b-button>
              <b-button @click="destroyIndikatorTujuan(row.item.id)" variant="outline-danger" size="sm" class="m-1 rounded-circle" title="Hapus">
                <i class="fa fa-trash" aria-hidden="true"></i>
              </b-button>
            </div>
          </template>
        </b-table>
      </section>
    </b-overlay>
  </b-card>
</template>
