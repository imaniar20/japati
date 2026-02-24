<script>
import axios from 'axios'
import { destroy as doDestroy } from '~/plugins/swal'

export default {
  middleware: ['auth'],

  async asyncData({ params, redirect }) {
    const triwulan = parseInt(params.triwulan)
    const kinerjaId = parseInt(params.kinerjaId)
    
    if (![1, 2, 3, 4].includes(triwulan)) {
      redirect('/rapor-kinerja/1/data')
      return false
    }

    const { data: info } = await axios.get(`/rapor-kinerja/${triwulan}/data/${kinerjaId}/penyebab-kegagalan`, {
      params: {
        info: true,
      }
    })

    return {
      triwulan,
      kinerjaId,
      info,
    }
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
          { key: 'penyebab', label: 'Penyebab' },
          { key: 'langkah_aksi_count', label: 'Jumlah langkah aksi' },
          { key: 'action', label: 'Aksi' },
        ]
      },
      triwulanBulan: {
        1: [['jan', 'Januari'], ['feb', 'Februari'], ['mar', 'Maret']],
        2: [['jan', 'Januari'], ['feb', 'Februari'], ['mar', 'Maret'], ['apr', 'April'], ['may', 'Mei'], ['jun', 'Juni']],
        3: [['jan', 'Januari'], ['feb', 'Februari'], ['mar', 'Maret'], ['apr', 'April'], ['may', 'Mei'], ['jun', 'Juni'], ['jul', 'Juli'], ['aug', 'Agustus'], ['sep', 'September']],
        4: [['jan', 'Januari'], ['feb', 'Februari'], ['mar', 'Maret'], ['apr', 'April'], ['may', 'Mei'], ['jun', 'Juni'], ['jul', 'Juli'], ['aug', 'Agustus'], ['sep', 'September'], ['oct', 'Oktober'], ['nov', 'November'], ['dec', 'Desember']],
      },
      form: {
        penyebab: null
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
        const { data } = await axios.get(`/rapor-kinerja/${this.triwulan}/data/${this.kinerjaId}/penyebab-kegagalan`)

        this.data = data
      } catch (error) {
        alert('Gagal mengambil data penyebab kegagalan kinerja')
      } finally {
        this.isBusy.getData = false
      }
    },
    async store() {
      this.isBusy.store = true

      try {
        await axios.post(`/rapor-kinerja/${this.triwulan}/data/${this.kinerjaId}/penyebab-kegagalan`, this.form)
        this.$bvModal.hide('modal-create')
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
        penyebab: null
      }
    },
    destroy(index, id) {
      doDestroy({
        preConfirm: async () => {
          return axios.delete(`/rapor-kinerja/${this.triwulan}/data/${this.kinerjaId}/penyebab-kegagalan/${id}`)
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
        penyebab: item.penyebab
      }
    },
    async update() {
      this.isBusy.update = true

      try {
        await axios.patch(`/rapor-kinerja/${this.triwulan}/data/${this.kinerjaId}/penyebab-kegagalan/${this.form.id}`, this.form)
        this.$bvModal.hide('modal-edit')
        this.reset()
        this.getData()
      } catch (error) {
        alert('Gagal mengubah data')
      } finally {
        this.isBusy.update = false
      }
    },
  }
}
</script>



<template>
  <div>
    <b-card>
      <div>
        <b-form-group label-cols="12" label="Kegiatan" label-class="font-weight-bold">
          {{ info.kegiatan?.nama || '-' }}
        </b-form-group>
        <b-form-group label-cols="12" label="Sub Kegiatan" label-class="font-weight-bold">
          {{ info.sub_kegiatan?.nama || '-' }}
        </b-form-group>
        <b-form-group label-cols="12" label="Sasaran Kinerja Sub Kegiatan" label-class="font-weight-bold">
          {{ info.sasaran }}
        </b-form-group>
        <b-form-group label-cols="12" label="Indikator Kinerja Sub Kegiatan" label-class="font-weight-bold">
          {{ info.indikator }}
        </b-form-group>
        <b-form-group label-cols="12" label="Capaian" label-class="font-weight-bold">
          <b-table-simple small responsive>
            <b-thead>
              <b-tr>
                <b-th>Bulan</b-th>
                <b-th>Target</b-th>
                <b-th>Realisasi</b-th>
              </b-tr>
            </b-thead>
            <b-tbody>
              <b-tr v-for="(bulan, index) of triwulanBulan[triwulan]" :key="index" :class="{'bg-tidak-tercapai': parseFloat(info.realisasi_bulanan[bulan[0]]) < parseFloat(info.target_bulanan[bulan[0]])}">
                <b-th>{{ bulan[1] }}</b-th>
                <b-td>{{ info.target_bulanan[bulan[0]] }}</b-td>
                <b-td>{{ info.realisasi_bulanan[bulan[0]] }}</b-td>
              </b-tr>
            </b-tbody>
          </b-table-simple>
        </b-form-group>

      </div>
    </b-card>

    <b-card class="mt-2">
      <div class="text-right mb-3">
        <b-button variant="primary" v-b-modal.modal-create @click="reset"><i class="ti-plus" aria-hidden="true"></i> Tambah</b-button>
      </div>

      <b-table responsive hover sticky-header="calc(100vh - 200px)" :fields="table.fields" :items="data" :busy="isBusy.getData" show-empty class="table-bordered" head-variant="info">
        <template #cell(no)="row">
          {{ row.index + 1 }}
        </template>
        <template #cell(action)="row">
          <div class="text-nowrap">
            <nuxt-link :to="`/kinerja-langkah-aksi?penyebab_kegagalan_id=${row.item.id}`" class="btn btn-outline-success btn-sm rounded-circle" v-b-tooltip.hover title="Lihat langkah aksi">
              <i class="fa fa-eye" aria-hidden="true"></i>
            </nuxt-link>
            <nuxt-link :to="`/kinerja-langkah-aksi/create?penyebab_kegagalan_id=${row.item.id}`" class="btn btn-outline-primary btn-sm rounded-circle" v-b-tooltip.hover title="Tambah langkah aksi">
              <i class="fa fa-plus" aria-hidden="true"></i>
            </nuxt-link>
            <b-button size="sm" v-b-modal.modal-edit @click="setForm(row.item)" variant="outline-warning" class="rounded-circle" v-b-tooltip.hover title="Edit">
              <i class="fa fa-pencil" aria-hidden="true"></i>
            </b-button>
            <b-button @click="destroy(row.index, row.item.id)" size="sm" variant="outline-danger" class="rounded-circle" v-b-tooltip.hover title="Hapus">
              <i class="fa fa-trash" aria-hidden="true"></i>
            </b-button>
          </div>
        </template>
      </b-table>
    </b-card>

    <b-modal id="modal-create" title="Tambah penyebab kegagalan">
      <form ref="form-create" @submit.prevent="store">
        <b-form-group label-cols="12" label="Penyebab" label-class="font-weight-bold" label-for="penyebab">
          <b-form-textarea id="penyebab" v-model="form.penyebab" required></b-form-textarea>
        </b-form-group>
        <button ref="submit-form-create" class="d-none"></button>
      </form>

      <template #modal-footer="{ cancel }">
        <b-button @click="cancel">Cancel</b-button>
        <b-button variant="primary" @click="$refs['submit-form-create'].click()" :disabled="isBusy.store">
          <b-spinner small v-if="isBusy.store"></b-spinner>
          Submit
        </b-button>
      </template>
    </b-modal>

    <b-modal id="modal-edit" title="Ubah penyebab kegagalan">
      <form ref="form-edit" @submit.prevent="update">
        <b-form-group label-cols="12" label="Penyebab" label-class="font-weight-bold" label-for="penyebab">
          <b-form-textarea id="penyebab" v-model="form.penyebab" required></b-form-textarea>
        </b-form-group>
        <button ref="submit-form-edit" class="d-none"></button>
      </form>

      <template #modal-footer="{ cancel }">
        <b-button @click="cancel">Cancel</b-button>
        <b-button variant="primary" @click="$refs['submit-form-edit'].click()" :disabled="isBusy.update">
          <b-spinner small v-if="isBusy.update"></b-spinner>
          Submit
        </b-button>
      </template>
    </b-modal>
  </div>
</template>

<style scoped>
.bg-tidak-tercapai {
  background-color: #ff001973 !important;
}
</style>