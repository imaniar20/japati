<script>
import axios from 'axios'
import { destroy as doDestroy } from '~/plugins/swal'

export default {
  props: {
    type: {
      type: String,
      required: true,
      validator: function (value) {
        return ['kinerja-program', 'kinerja-kegiatan', 'kinerja-sub-kegiatan'].indexOf(value) !== -1
      }
    },
    id: {
      type: Number,
      required: true,
    },
  },

  data() {
    return {
      data: [],
      isBusy: {
        getData: false,
        store: false,
        update: false,
      },
      table: {
        fields: [
          { key: 'no', label: 'No' },
          { key: 'catatan', label: 'Catatan' },
          { key: 'action', label: 'Aksi' },
        ]
      },
      form: {
        catatan: null,
      },
    }
  },

  mounted() {
    this.getData()
  },

  methods: {
    async getData() {
      this.isBusy.getData = true

      try {
        const { data } = await axios.get('/kinerja-tercapai', {
          params: {
            type: this.type,
            id: this.id
          }
        })

        this.data = data
      } catch (error) {
        alert('Gagal mengambil data kinerja')
      } finally {
        this.isBusy.getData = false
      }
    },
    async store() {
      this.isBusy.store = true

      try {
        await axios.post('/kinerja-tercapai', {
          ...this.form,
          type: this.type,
          id: this.id
        })
        this.$bvModal.hide(this.parseId('modal-create'))
        this.reset()
        this.getData()
      } catch (error) {
        alert('Gagal menambah data')
      } finally {
        this.isBusy.store = false
      }
    },
    reset() {
      this.form = {
        catatan: null
      }
    },
    async destroy(index, id) {
      doDestroy({
        preConfirm: async () => {
          return axios.delete(`/kinerja-tercapai/${id}`)
            .then(() => {
              this.data.splice(index, 1)

              return true
            })
            .catch(() => {
              alert('Gagal menghapus data')
              return false
            })
        }
      })
    },
    setForm(item) {
      this.form = {
        id: item.id,
        catatan: item.catatan
      }
    },
    async update() {
      this.isBusy.update = true

      try {
        await axios.patch(`/kinerja-tercapai/${this.form.id}`, this.form)
        this.$bvModal.hide(this.parseId('modal-edit'))
        this.reset()
        this.getData()
      } catch (error) {
        alert('Gagal mengubah data')
      } finally {
        this.isBusy.update = false
      }
    },
    /**
     * Tambah prefix supaya tidak bentrok
     * kasus bentrok dengan KinerjaTidakTercapai
     */
    parseId(id) {
      return `kinerja-tercapai-${id}`
    }
  }
}
</script>

<template>
  <div>
    <div class="text-right mb-3">
      <b-button variant="primary" v-b-modal="parseId('modal-create')" @click="reset"><i class="ti-plus" aria-hidden="true"></i> Tambah</b-button>
    </div>

    <b-table responsive hover bordered :fields="table.fields" :items="data" :busy="isBusy.getData" show-empty head-variant="info">
      <template #cell(no)="row">
        <div class="text-center font-weight-bold">{{ row.index + 1 }}</div>
      </template>
      <template #cell(catatan)="row">
        <div style="white-space: break-spaces;">{{ row.value }}</div>
      </template>
      <template #cell(action)="row">
        <div class="text-nowrap">
          <b-button size="sm" v-b-modal="parseId('modal-edit')" @click="setForm(row.item)" variant="outline-warning" class="rounded-circle" v-b-tooltip.hover title="Edit">
            <i class="fa fa-pencil" aria-hidden="true"></i>
          </b-button>
          <b-button @click="destroy(row.index, row.item.id)" size="sm" variant="outline-danger" class="rounded-circle" v-b-tooltip.hover title="Hapus">
            <i class="fa fa-trash" aria-hidden="true"></i>
          </b-button>
        </div>
      </template>
    </b-table>

    <b-modal :id="parseId('modal-create')" title="Tambah Diagnosa Critical Success Factor (CSF)">
      <form :ref="parseId('form-create')" @submit.prevent="store">
        <b-form-group label-cols="12" label="Catatan" label-class="font-weight-bold" label-for="catatan">
          <b-form-textarea id="catatan" v-model="form.catatan" required></b-form-textarea>
        </b-form-group>
        <button :ref="parseId('submit-form-create')" class="d-none"></button>
      </form>

      <template #modal-footer="{ cancel }">
        <b-button @click="cancel">Cancel</b-button>
        <b-button variant="primary" @click="$refs[parseId('submit-form-create')].click()" :disabled="isBusy.store">
          <b-spinner small v-if="isBusy.store"></b-spinner>
          Submit
        </b-button>
      </template>
    </b-modal>

    <b-modal :id="parseId('modal-edit')" title="Ubah Diagnosa Critical Success Factor (CSF)">
      <form :ref="parseId('form-edit')" @submit.prevent="update">
        <b-form-group label-cols="12" label="Catatan" label-class="font-weight-bold" label-for="catatan">
          <b-form-textarea id="catatan" v-model="form.catatan" required></b-form-textarea>
        </b-form-group>
        <button :ref="parseId('submit-form-edit')" class="d-none"></button>
      </form>

      <template #modal-footer="{ cancel }">
        <b-button @click="cancel">Cancel</b-button>
        <b-button variant="primary" @click="$refs[parseId('submit-form-edit')].click()" :disabled="isBusy.update">
          <b-spinner small v-if="isBusy.update"></b-spinner>
          Submit
        </b-button>
      </template>
    </b-modal>
  </div>
</template>