import Swal from 'sweetalert2'

export const destroy = ({
  title = 'Apakah Anda yakin?',
  text = 'Data yang dihapus tidak akan bisa dikembalikan',
  preConfirm,
}) => {
  Swal.fire({
    title,
    text,
    type: 'warning',
    confirmButtonText: 'Hapus',
    confirmButtonColor: '#bd2130',
    showCancelButton: true,
    focusCancel: true,
    showLoaderOnConfirm: true,
    allowOutsideClick: () => !Swal.isLoading(),
    preConfirm: () => {
      return preConfirm()
    }
  })
  .then(res => {
    if (res.value) {
      Swal.fire({
        type: 'success',
        title: 'Berhasil hapus data'
      })
    }
  })
};

