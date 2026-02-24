<script>
import axios from 'axios'
import Swal from 'sweetalert2'
import { destroy as doDestroy } from '~/plugins/swal'

export default {
  middleware: ['auth'],

  async asyncData({ $role }) {
    const { data } = await axios.get('perjanjian-kinerja')
    const canCrud = $role.isSuper() || $role.isSetda() || ($role.isPemerintahDaerah() && !$role.isBiro())

    return {
      data,
      canCrud,
    }
  },

  data() {
    return {
      table: {
        fields: [
          { key: 'no', label: 'No' },
          { key: 'peg_nip', label: 'Pegawai' },
          { key: 'nip_atasan', label: 'Atasan' },
          // { key: 'target', label: 'Target Kinerja' },
          // { key: 'realisasi', label: 'Realisasi' },
          { key: 'action', label: 'Aksi' },
        ]
      },
      filter: {
        satuan_kerja_id: null,
        misi_id: null,
        tujuan_id: null,
        indikator_tujuan_id: null,
      },
      isBusy: {
        doFilter: false,
        exportExcel: false,
      },
      dataTemp: {
        peg_nip : null,
        tahun : '2024',
        tanggal: '',
        pegawai: [],
        link : '',
      },
    }
  },

  watch: {
    filter: {
      handler: function () {
        this.doFilter()
      },
      deep: true,
    }
  },

  created() {
    /** prepend field Satuan Kerja jika akun super*/
    // if (this.$role.isSuper() || this.$role.isViewAll()) {
    //   this.table.fields.splice(1, 0, { key: 'satuan_kerja', label: 'Satuan Kerja' })
    // }
  },

  methods: {
    destroy(index, id) {
      doDestroy({
        preConfirm: async () => {
          return axios.delete(`/perjanjian-kinerja/${id}`)
            .then(() => {
              this.data.data.splice(index, 1)

              return true
            })
        }
      })
    },
    async doFilter(page = 1) {
      this.isBusy.doFilter = true

      const { data } = await axios.get('perjanjian-kinerja', {
        params: {
          filter: this.filter,
          page,
        }
      })

      this.data = data
      this.isBusy.doFilter = false
    },
    async exportExcel() {
      this.isBusy.exportExcel = true
      this.$bvModal.hide('form-modal')
      try {
        const { data } = await axios.get('/perjanjian-kinerja/export', {
          params: {
            nip: this.dataTemp.peg_nip,
            tahun: this.dataTemp.tahun,
            tanggal: this.dataTemp.tanggal,
          },
          // Accept: 'application/pdf',
          responseType: 'blob'
        })

        const url = window.URL.createObjectURL(new Blob([data], {type: 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'}))
        const link = document.createElement('a')

        link.href = url
        link.setAttribute('download', 'Perjanjian Kinerja '+this.dataTemp.peg_nip+'.docx')
        document.body.appendChild(link)
        link.click()
      } catch (error) {
        throw error
      } finally {
        this.isBusy.exportExcel = false
      }
    },
    async exportPdf() {
      this.isBusy.exportExcel = true
      this.$bvModal.hide('form-modal-pdf')
      try {
        const { data } = await axios.get('/perjanjian-kinerja/export-pdf', {
          params: {
            nip: this.dataTemp.peg_nip,
            tahun: this.dataTemp.tahun,
            tanggal: this.dataTemp.tanggal,
          },
          // Accept: 'application/pdf',
          responseType: 'blob'
        })

        const url = window.URL.createObjectURL(new Blob([data], {type: 'application/pdf'}))
        const link = document.createElement('a')

        link.href = url
        link.setAttribute('download', 'Perjanjian Kinerja '+this.dataTemp.peg_nip+'.pdf')
        document.body.appendChild(link)
        link.click()
      } catch (error) {
        throw error
      } finally {
        this.isBusy.exportExcel = false
      }
    },
    async exportExcelall() {
      this.message = ''; // Reset message
      this.isBusy.exportExcel = true
      this.$bvModal.hide('form-modal-all')
      try {
        const { data } = await axios.get('/perjanjian-kinerja/export-all', {
          params: {
            nip: this.dataTemp.peg_nip,
            tahun: this.dataTemp.tahun,
            tanggal: this.dataTemp.tanggal,
          },
          // Accept: 'application/pdf',
          responseType: 'blob'
        })
          const url = window.URL.createObjectURL(new Blob([data]))
          const link = document.createElement('a')

          link.href = url
          link.setAttribute('download', 'Perjanjian Kinerja.zip')
          document.body.appendChild(link)
          link.click()

      } catch (error) {
        if (error.response && error.response.status === 404) {
          this.message = 'Data masih dalam proses generate, silakan tunggu beberapa saat dan klik tombol export kembali';
        } else {
          this.message = 'An error occurred while downloading the file.';
        }
        Swal.fire({
            type: 'success',
            title: this.message
          })
      } finally {
        this.isBusy.exportExcel = false
      }
    },
    async storeLink() {
      this.message = ''; // Reset message
      this.isBusy.exportExcel = true
      this.$bvModal.hide('form-modal-link')

      try {
        const { data } = await axios.post('/perjanjian-kinerja/link', {
            link: this.dataTemp.link,
        })
        Swal.fire({
            type: 'success',
            title: 'Berhasil menginput link Perjanjian Kinerja'
          })
        // successMessage.value = response.data.message;
        // form.value = { name: '', message: '' }; // Clear form after success
      } catch (error) {
        Swal.fire({
            type: 'error',
            title: error.response?.data || error.errors.message
          })
      } finally {
        this.isBusy.exportExcel = false
      }
    },
    showModalForm(data = null) {
      console.log(data);
        this.dataTemp = {
          peg_nip : data.peg_nip,
          tahun : '2024',
          tanggal : '',
          pegawai : data
        }
      this.$bvModal.show('form-modal')
    },
    showModalFormPdf(data = null) {
      console.log(data);
        this.dataTemp = {
          peg_nip : data.peg_nip,
          tahun : '2024',
          tanggal : '',
          pegawai : data
        }
      this.$bvModal.show('form-modal-pdf')
    },
    showModalFormAll(data = null) {
      console.log(data);
        this.dataTemp = {
          peg_nip : data.peg_nip,
          tahun : '2024',
          tanggal : '',
          pegawai : data
        }
      this.$bvModal.show('form-modal-all')
    },
    showModalFormLink(data = null) {
        this.dataTemp = {
          link : ''
        }
      this.$bvModal.show('form-modal-link')
    },
  },
}
</script>

<template>
  <b-card>
    <div class="text-right">
      <b-button variant="success" @click="showModalFormAll" :disabled="isBusy.exportExcel">
        <b-spinner small v-if="isBusy.exportExcel"></b-spinner>
        <i v-else class="ti-download" aria-hidden="true"></i>
        Export
      </b-button>
      <b-button variant="success" @click="showModalFormLink" :disabled="isBusy.exportExcel">
        <b-spinner small v-if="isBusy.exportExcel"></b-spinner>
        <i v-else class="ti-plus" aria-hidden="true"></i>
        Simpan Link
      </b-button>
    </div>
    <div>
      <b-row>
        <b-col sm="6" md="4">

        </b-col>
        <b-col sm="6" md="4">

        </b-col>
      </b-row>
    </div>

    <b-table responsive hover striped sticky-header="calc(100vh - 300px)" :fields="table.fields" :items="data.data" :busy="isBusy.doFilter" show-empty class="table-bordered mt-2" head-variant="info" >
      <template #cell(no)="row">
        <div class="text-center">
          <b>{{ data.from + row.index }}</b> <br>
        </div>
      </template>
      <template  #cell(peg_nip)="row">
        {{ row.value }}<br>
        {{ row.item.peg_nama }}
      </template>

      <template #cell(nip_atasan)="row">
        {{ row.value }}<br>
        {{ row.item.nama_atasan }}
      </template>
      
      <template #cell(action)="row">
        <div class="text-nowrap">
          <!-- <nuxt-link :to="`/visi-misi-rpjmd/${row.item.id}`" class="btn btn-outline-success btn-sm m-1 rounded-circle" title="Detail">
            <i class="fa fa-eye" aria-hidden="true"></i>
          </nuxt-link> -->
          <button @click="showModalForm(row.item)" class="btn btn-outline-success btn-sm m-1 rounded-circle" title="Detail">
            <b-spinner small v-if="isBusy.exportExcel"></b-spinner>
            <i v-else class="ti-download" aria-hidden="true"></i>
          </button>
          <button @click="showModalFormPdf(row.item)" class="btn btn-outline-success btn-sm m-1 rounded-circle" title="Detail">
            <b-spinner small v-if="isBusy.exportExcel"></b-spinner>
            <i v-else class="ti-download" aria-hidden="true"></i>
          </button>
          <!-- <nuxt-link v-if="canCrud"  :to="`/visi-misi-rpjmd/${row.item.id}/edit`" class="btn btn-outline-warning btn-sm m-1 rounded-circle" title="Edit">
            <i class="fa fa-pencil" aria-hidden="true"></i>
          </nuxt-link>
          <b-button v-if="canCrud"  @click="destroy(row.index, row.item.id)" variant="outline-danger" size="sm" class="m-1 rounded-circle" title="Hapus">
            <i class="fa fa-trash" aria-hidden="true"></i>
          </b-button> -->
        </div>
      </template>
    </b-table>

    <div>
      <b-pagination
        v-model="data.current_page"
        :total-rows="data.total"
        :per-page="data.per_page"
        @change="doFilter($event)"
      >
        <template #page="{ page, active }">
          <i class="fa fa-spinner fa-pulse fa-fw" v-if="isBusy.doFilter && active"></i>
          <template v-else>{{ page }}</template>
        </template>
      </b-pagination>
    </div>
  <b-modal
      id="form-modal"
      size="lg"
      @ok="$event.preventDefault(); $refs['submit-form'].click()"
      title="Export Perjanjian Kinerja"
      no-close-on-backdrop
      :hide-header-close="isBusy.exportExcel"
    >
      <form @submit.stop.prevent="submit">
        <b-form-group
          label="Tanggal Perjanjian Kinerja"
          label-for="perjanjian-kinerja"
        >
        <b-form-datepicker
            id="datepicker-dateformat1"
            :date-format-options="{ year: 'numeric', month: 'numeric', day: 'numeric' }"
            locale="en"
            v-model="dataTemp.tanggal"
         ></b-form-datepicker>
        </b-form-group>
        <button ref="submit-form" class="d-none"></button>
      </form>

      <template #modal-footer="{ok, cancel}">
        <b-button @click="cancel" :disabled="isBusy.exportExcel">Batal</b-button>
        <b-button @click="exportExcel" variant="primary" :disabled="isBusy.exportExcel">
          <b-spinner small v-if="isBusy.exportExcel"></b-spinner>
          {{ dataTemp.id ? 'Export' : 'Export' }}
        </b-button>
      </template>
    </b-modal>
    <b-modal
      id="form-modal-pdf"
      size="lg"
      @ok="$event.preventDefault(); $refs['submit-form'].click()"
      title="Export Perjanjian Kinerja"
      no-close-on-backdrop
      :hide-header-close="isBusy.exportExcel"
    >
      <form @submit.stop.prevent="submit">
        <b-form-group
          label="Tanggal Perjanjian Kinerja"
          label-for="perjanjian-kinerja"
        >
        <b-form-datepicker
            id="datepicker-dateformat1"
            :date-format-options="{ year: 'numeric', month: 'numeric', day: 'numeric' }"
            locale="en"
            v-model="dataTemp.tanggal"
         ></b-form-datepicker>
        </b-form-group>
        <button ref="submit-form" class="d-none"></button>
      </form>

      <template #modal-footer="{ok, cancel}">
        <b-button @click="cancel" :disabled="isBusy.exportExcel">Batal</b-button>
        <b-button @click="exportPdf" variant="primary" :disabled="isBusy.exportExcel">
          <b-spinner small v-if="isBusy.exportExcel"></b-spinner>
          {{ dataTemp.id ? 'Export' : 'Export' }}
        </b-button>
      </template>
    </b-modal>
    <b-modal
      id="form-modal-all"
      size="lg"
      @ok="$event.preventDefault(); $refs['submit-form'].click()"
      title="Export Perjanjian Kinerja"
      no-close-on-backdrop
      :hide-header-close="isBusy.exportExcel"
    >
      <form @submit.stop.prevent="submit">
        <b-form-group
          label="Tanggal Perjanjian Kinerja"
          label-for="perjanjian-kinerja"
        >
        <b-form-datepicker
            id="datepicker-dateformat1"
            :date-format-options="{ year: 'numeric', month: 'numeric', day: 'numeric' }"
            locale="en"
            v-model="dataTemp.tanggal"
         ></b-form-datepicker>
        </b-form-group>
        <button ref="submit-form" class="d-none"></button>
      </form>

      <template #modal-footer="{ok, cancel}">
        <b-button @click="cancel" :disabled="isBusy.exportExcel">Batal</b-button>
        <b-button @click="exportExcelall" variant="primary" :disabled="isBusy.exportExcel">
          <b-spinner small v-if="isBusy.exportExcel"></b-spinner>
          {{ dataTemp.id ? 'Export' : 'Export' }}
        </b-button>
      </template>
    </b-modal>
    <b-modal
      id="form-modal-link"
      size="lg"
      @ok="$event.preventDefault(); $refs['submit-form'].click()"
      title="Link Perjanjian Kinerja"
      no-close-on-backdrop
      :hide-header-close="isBusy.exportExcel"
    >
      <form @submit.stop.prevent="submit">
        <b-form-group
          label="Link Perjanjian Kinerja"
          label-for="perjanjian-kinerja"
        >
         <b-form-textarea
          id="link"
          v-model="dataTemp.link"
          placeholder="Link"
          rows="4"
          max-rows="8"
          required
        ></b-form-textarea>
        </b-form-group>
        <button ref="submit-form" class="d-none"></button>
      </form>

      <template #modal-footer="{ok, cancel}">
        <b-button @click="cancel" :disabled="isBusy.exportExcel">Batal</b-button>
        <b-button @click="storeLink" variant="primary" :disabled="isBusy.exportExcel">
          <b-spinner small v-if="isBusy.exportExcel"></b-spinner>
          {{ dataTemp.id ? 'Simpan' : 'Simpan' }}
        </b-button>
      </template>
    </b-modal>
  </b-card>
</template>