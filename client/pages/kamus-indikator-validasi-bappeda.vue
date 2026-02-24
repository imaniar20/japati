<script>
import axios from 'axios'
import Swal from 'sweetalert2'

export default {
  middleware: ['auth', 'role-validator-bappeda'],

  data() {
    return {
      data: [],
      isBusy: {
        getData: false,
      }
    }
  },

  mounted() {
    this.getData()
  },

  methods: {
    async getData() {
      this.isBusy.getData = true

      const { data } = await axios.get('kamus-indikator-validasi-bappeda')

      this.data = data
      this.isBusy.getData = false
    },
    validate(satkerId, validate, satker) {
      Swal.fire({
        title: validate ? 'Validasi' : 'Hapus Validasi',
        text: validate
          ? `Apakah Anda yakin akan validasi ${satker}`
          : `Apakah Anda yakin akan menghapus validasi ${satker}`,
        type: validate ? 'question' : 'warning',
        showCancelButton: true,
        cancelButtonText: 'Batal',
        confirmButtonText: validate ? 'Validasi' : 'Hapus Validasi',
        confirmButtonColor: validate ? null : '#bd2130',
        focusCancel: true,
        showLoaderOnConfirm: true,
        allowOutsideClick: () => !Swal.isLoading(),
        preConfirm: async () => {
          return await this.doValidate(satkerId, validate)
        }
      })
      .then(res => {
        if (res.value) {
          Swal.fire({
            type: 'success',
            title: validate
              ? 'Berhasil validasi data'
              : 'Berhasil hapus validasi'
          })

          this.getData()
        }
      })
    },
    async doValidate(satkerId, validate) {
      await axios.post('kamus-indikator-validasi-bappeda', {
        satuan_kerja_id: satkerId,
        status: validate
      })

      return true
    }
  }
}
</script>

<template>
  <b-card>
    <b-table-simple :aria-busy="isBusy.getData" responsive bordered striped hover>
      <b-thead class="text-center align-middle" head-variant="info">
        <b-tr>
          <b-th>Satuan Kerja</b-th>
          <b-th>Aksi</b-th>
        </b-tr>
      </b-thead>
      <b-tbody>
        <b-tr v-if="isBusy.getData">
          <b-td colspan="2" class="text-center"><b-spinner /></b-td>
        </b-tr>
        <b-tr v-else v-for="(item, index) of data" :key="index">
          <b-td>{{ item.satuan_kerja_nama }}</b-td>
          <b-td class="text-center">
            <b-button v-if="!item.is_validasi" @click="validate(item.satuan_kerja_id, true, item.satuan_kerja_nama)" variant="success" size="sm" class="m-1 rounded-circle" title="Validasi">
              <i class="fa fa-check" aria-hidden="true"></i>
            </b-button>
            <b-button v-else @click="validate(item.satuan_kerja_id, false, item.satuan_kerja_nama)" variant="danger" size="sm" class="m-1 rounded-circle"  title="Hapus Validasi">
              <i class="fa fa-times" aria-hidden="true"></i>
            </b-button>
          </b-td>
        </b-tr>
      </b-tbody>
    </b-table-simple>
  </b-card>
</template>