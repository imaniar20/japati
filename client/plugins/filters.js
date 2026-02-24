import Vue from 'vue'
Vue.filter('formatDateTime', function (value) {
  if (!value || value == '') return ''
  var nmBulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
  if (value.indexOf('T') > -1) {
    var date = new Date(value)
    var tanggal = date.getDate();
    var bulan = date.getMonth() + 1;
    var jam = date.getHours().toString().padStart(2, '0')
    var menit = date.getMinutes().toString().padStart(2, '0')
    // return nmBulan[bulan];
    return tanggal + " " + nmBulan[bulan] + " " + date.getFullYear() + " " + jam + ":" + menit;
  }
  var tanggal = parseInt(value.toString().substring(8, 10));
  var bulan = parseInt(value.toString().substring(5, 7));
  var jam = value.toString().substring(11, 13).padStart(2, '0')
  var menit = value.toString().substring(14, 16).padStart(2, '0')
  // return nmBulan[bulan];
  return tanggal + " " + nmBulan[bulan] + " " + value.toString().substring(0, 4) + " " + jam + ":" + menit;
});
Vue.filter('formatDate', function (value) {
  if (!value || value == '') return ''
  var nmBulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
  if (value.indexOf('T') > -1) {
    var date = new Date(value)
    var tanggal = date.getDate();
    var bulan = date.getMonth() + 1;
    // return nmBulan[bulan];
    return tanggal + " " + nmBulan[bulan] + " " + date.getFullYear()
  }
  var tanggal = parseInt(value.toString().substring(8, 10));
  var bulan = parseInt(value.toString().substring(5, 7));
  var jam = value.toString().substring(11, 13).padStart(2, '0')
  var menit = value.toString().substring(14, 16).padStart(2, '0')
  // return nmBulan[bulan];
  return tanggal + " " + nmBulan[bulan] + " " + value.toString().substring(0, 4)
});
Vue.filter('formatMoney', function(amount, decimalCount = 0, decimal = ",", thousands = ".") {
  try {
    decimalCount = Math.abs(decimalCount);
    decimalCount = isNaN(decimalCount) ? 2 : decimalCount;

    const negativeSign = amount < 0 ? "-" : "";

    let i = parseInt(amount = Math.abs(Number(amount) || 0).toFixed(decimalCount)).toString();
    let j = (i.length > 3) ? i.length % 3 : 0;

    return negativeSign + (j ? i.substr(0, j) + thousands : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands) + (decimalCount ? decimal + Math.abs(amount - i).toFixed(decimalCount).slice(2) : "");
  } catch (e) {
    return ''
  }
});

Vue.filter('rupiah', function (value) {
  value = value.toString();
  let [integerPart, decimalPart] = value.split('.');

  // Format the integer part
  let formatted = integerPart.split('').reverse().join('');
  formatted = formatted.match(/\d{1,3}/g);
  formatted = formatted.join('.').split('').reverse().join('');

  // Append decimal part if exists
  if (decimalPart !== undefined) {
    return "Rp\u00A0" + formatted + ',' + decimalPart;
  }
  
  return "Rp\u00A0" + formatted;
})

Vue.filter('decimalDigit', function (value, digit = 2) {
  const number = parseFloat(value);
  if (isNaN(number)) {
    return value;
  }
  
  return + parseFloat(number).toFixed(digit);
});
