export const getChipColor = (status) => {
  let chipColor = null
  if (status === 'PUBLISHED') {
    chipColor = 'success'
  } else if (status === 'APPROVED') {
    chipColor = 'success'
  }
  return chipColor
}

export const moneyFormat = {
  decimal: ',',
  thousands: '.',
  prefix: 'Rp ',
  suffix: '',
  precision: 2,
  masked: false,
}