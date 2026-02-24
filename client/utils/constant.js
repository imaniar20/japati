export const NOTIFICATION_SUCCESS_CREATE = 'Notifikasi berhasil dibuat'
export const SUCCESS_DELETE = 'Data berhasil di hapus'
export const FAILED_DELETE = 'Maaf, ada kesalahan dalam menghapus data'
export const CONFIRM_DELETE =
  'Apakah anda yakin akan menghapus data kegiatan dengan ID: '

export const DEFAULT_FILTER = {
  groupBy: [],
  groupDesc: [],
  sortBy: ['created_at'],
  sortDesc: [true],
  sortOrder: 'desc',
  multiSort: false,
  mustSort: false,
  keyWords: undefined
}
export const DEFAULT_PAGINATION = {
  page: 1,
  itemsPerPage: 50,
  total: 0
}
