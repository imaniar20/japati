<script>
import Swal from 'sweetalert2';
import axios from 'axios';
import { mapGetters } from 'vuex'

export default {
  middleware: ['role-validator-lke'],
  
  data() {
    return {
      filter: {
        satuan_kerja_id: null,
      },
      data: [],
      bobotTotal: 0,
      skorTotal: 0,
      isHumanisDone: false,
      isBusy: {
        getData: false,
        submit: false,
        close: false,
      },
      form: {},
    }
  },

  computed: {
    skorTotalHumanis() {
      return Object.values(this.form).reduce((acc, current) => acc + Number(current.nilai), 0);
    },
    ...mapGetters({
      user: 'auth/user'
    })
  },

  watch: {
    filter: {
      handler: function () {
        if (this.filter.satuan_kerja_id) this.getData()
      },
      deep: true,
    }
  },

  methods: {

    async printReport(){
      let tahunKinerja = this.$helper.getTahunKinerja();
      let path =
        process.env.apiUrl +
        "/laporan-lhe/export?satuan_kerja_id=" +
        this.filter.satuan_kerja_id +
        "&tahun_kinerja=" +
        tahunKinerja;
      window.open(path, "_blank");
    },
    async getData() {
      try {
        this.form = {}
        this.data = []
        this.bobotTotal = 0
        this.skorTotal = 0
        this.isHumanisDone = false

        this.isBusy.getData = true
        
        const { data: { success, data, bobotTotal, skorTotal, isHumanisDone } } = await axios.get('lke/penilaian-humanis', {
          params: this.filter,
        })
        
        if (!success) {
          return
        }

        this.data = data
        this.bobotTotal = bobotTotal
        this.skorTotal = skorTotal
        this.isHumanisDone = isHumanisDone

        this.populateForm()
      } catch (error) {
        console.log(error);
        Swal.fire('Gagal!', 'Gagal mengambil data', 'error')
      } finally {
        this.isBusy.getData = false
      }
    },
    populateForm() {
      this.data.forEach(komponen => {
        this.$set(this.form, komponen.id, {
          id: komponen.id,
          nilai: Number(komponen.skor_penilaian) || 0,
        })
      });
    },
    closeConfirm() {
      Swal.fire({
        title: 'Validasi',
        text:  'Apakah Anda yakin? Data penilaian tidak dapat diubah setelah di-submit. Pastikan data sudah di simpan sebelum melakukan submit.',
        type: 'question',
        showConfirmButton: true,
        showCancelButton: true,
        confirmButtonText: 'Submit'
      })
      .then(({value}) => {
        if (value) {
          this.close()
        }
      })
    },
    async close() {
      try {
        const res = await this.submit(false)

        if (!res) return;
      } catch (error) {
        return
      }

      try {
        this.isBusy.close = true
        
        await axios.post('/lke/penilaian-humanis/close', this.filter)

        this.isHumanisDone = true
      } catch (error) {
        Swal.fire('Gagal!', 'Gagal validasi data', 'error')
        console.log(error);
      } finally {
        this.isBusy.close = false
      }
    },
    async submit(showSuccessNotif = true) {
      const form = Object.entries(this.form)

      for (let i = 0; i < form.length; i++) {
        const [id, item] = form[i];
        const komponen = this.data.find(_ => _.id == id)

        if (item.nilai < 0) {
          Swal.fire('Gagal', `Nilai pada Komponen ${komponen.nama} tidak boleh kurang dari 0`, 'warning')
          return
        }

        if (item.nilai > komponen.bobot) {
          Swal.fire('Gagal', `Nilai pada Komponen ${komponen.nama} tidak boleh lebih dari bobot`, 'warning')
          return
        }
      }
      
      try {
        this.isBusy.submit = true
        
        await axios.post('/lke/penilaian-humanis', {
          satuan_kerja_id: this.filter.satuan_kerja_id,
          data: this.form,
        })

        if (showSuccessNotif) {
          Swal.fire('Berhasil', 'Berhasil simpan data', 'success')
        }

        return true
      } catch (error) {
        Swal.fire('Gagal!', 'Gagal simpan data', 'error')
        console.log(error);

        return false
      } finally {
        this.isBusy.submit = false
      }
    }
  }
}
</script>

<template>
  <b-card>
    <div>
      <FilterSatuanKerja v-model="filter.satuan_kerja_id" :selectProps="{clearable: false}" :satuanKerjaIds="user.lke_penilaian_satuan_kerja_ids" />
    </div>

    <div v-if="isBusy.getData" class="text-center my-5">
      <b-spinner></b-spinner>
    </div>

    <div v-else-if="filter.satuan_kerja_id">
      <div class="text-right" v-if="!isHumanisDone && data.length">
        <b-button variant="success" @click="closeConfirm" :disabled="isBusy.close">
          <b-spinner small v-if="isBusy.close"></b-spinner>
          Validasi
        </b-button>
        <b-button variant="primary" @click="submit" :disabled="isBusy.submit">
          <b-spinner small v-if="isBusy.submit"></b-spinner>
          Simpan
        </b-button>
      </div>

      <b-alert :show="isHumanisDone" variant="success">
        Anda sudah selesai melakukan penilaian
      </b-alert>
      <b-button v-if="isHumanisDone" variant="success" @click="printReport">
          Cetak Laporan LKE
        </b-button>
      <h5 v-if="!data.length" class="text-center my-5">
        Perangkat Daerah belum melakukan submit penilaian
      </h5>
      <b-table-simple v-else responsive bordered hover class="mt-3">
        <b-thead class="text-center align-middle">
          <b-tr>
            <b-th>No</b-th>
            <b-th>Komponen/Sub Komponen</b-th>
            <b-th>Bobot</b-th>
            <b-th>Nilai Tahap Akhir</b-th>
            <b-th>Penilaian</b-th>
          </b-tr>
        </b-thead>
        <b-tbody>
          <template v-for="(komponen) in data">
            <b-tr :key="komponen.id">
              <b-td><b>{{ komponen.nomor }}</b></b-td>
              <b-td><b>{{ komponen.nama }}</b></b-td>
              <b-td><b>{{ komponen.bobot }}</b></b-td>
              <b-td><b>{{ komponen.skor }}</b></b-td>
              <b-td>
                <b v-if="isHumanisDone">{{ form[komponen.id].nilai }}</b>
                <b-input v-else type="number" v-model="form[komponen.id].nilai" min="0" :max="komponen.bobot" step="0.5"></b-input>
              </b-td>
            </b-tr>

            <template v-for="(subKomponen) in komponen.sub_komponen">
              <b-tr :key="subKomponen.id">
                <b-td>{{ komponen.nomor }}.{{ subKomponen.nomor }}</b-td>
                <b-td>{{ subKomponen.nama }}</b-td>
                <b-td>{{ subKomponen.bobot }}</b-td>
                <b-td>{{ subKomponen.skor }}</b-td>
                <b-td></b-td>
              </b-tr>
            </template>
          </template>
          
          <b-tr>
            <b-td colspan="2" class="text-center"><b>NILAI TOTAL</b></b-td>
            <b-td><b>{{ bobotTotal }}</b></b-td>
            <b-td><b>{{ skorTotal }}</b></b-td>
            <b-td><b>{{ skorTotalHumanis }}</b></b-td>
          </b-tr>
        </b-tbody>
      </b-table-simple>
    </div>
  </b-card>
</template>