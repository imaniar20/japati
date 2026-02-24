<script>
import Swal from 'sweetalert2'
import axios from 'axios'

export default {
  methods: {
    validate(model, id, isValidated) {
      Swal.fire({
        title: 'Hasil Desk Data',
        text: isValidated
          ? 'Apakah Anda yakin akan memperbaharui hasil desk data ini?'
          : 'Apakah Anda yakin dengan hasil desk data ini?',
        type: 'question',
        confirmButtonText: 'Iya',
        showCancelButton: true,
        cancelButtonText: 'Tidak',
        focusCancel: true,
        showLoaderOnConfirm: true,
        input: 'textarea',
        inputPlaceholder: 'Apa catatan yang ingin Anda tambahkan?',
        allowOutsideClick: () => !Swal.isLoading(),
        preConfirm: async (keterangan) => {
          return await this.doValidate(model, id, keterangan)
        }
      })
      .then(res => {
        if (res.value) {
          Swal.fire({
            type: 'success',
            title: 'Berhasil desk data'
          })

          this.$emit('success')
        }
      })
    },
    async doValidate(model, id, keterangan) {
      try {
        await axios.post('validasi-skp', {
          class: model,
          id,
          keterangan,
        })

        return true
      } catch (error) {
        throw error
      }
    },
    reject(model, id) {
      Swal.fire({
        title: 'Hasil Desk Data',
        text: 'Apakah Anda yakin akan menolak hasil desk data ini?',
        type: 'warning',
        confirmButtonText: 'Tolak',
        confirmButtonColor: '#bd2130',
        showCancelButton: true,
        cancelButtonText: 'Batal',
        focusCancel: true,
        showLoaderOnConfirm: true,
        input: 'textarea',
        inputPlaceholder: 'Masukkan alasan',
        inputValidator: (value) => {
          if (!value) {
            return 'Alasan wajib diisi!'
          }
        },
        allowOutsideClick: () => !Swal.isLoading(),
        preConfirm: async (keterangan) => {
          return await this.doReject(model, id, keterangan)
        }
      })
      .then(res => {
        if (res.value) {
          Swal.fire({
            type: 'success',
            title: 'Berhasil tolak desk data'
          })

          this.$emit('success')
        }
      })
    },
    async doReject(model, id, keterangan) {
      try {
        await axios.post('validasi-skp/reject', {
          class: model,
          id,
          keterangan,
        })

        return true
      } catch (error) {
        throw error
      }
    },
  }
}
</script>

<template>
  
</template>