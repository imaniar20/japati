<script>
import axios from 'axios'
import Swal from 'sweetalert2'

export default {
  middleware: ['role-perangkat-daerah'],

  async asyncData() {
    const { data: { data: komponenList, statusPenilaian } } = await axios.get('/lke/eviden')

    return {
      komponenList,
      statusPenilaian,
    }
  },

  data() {
    return {
      STATUS_NULL: null, // PD belum submit validasi
      STATUS_IN_ASSESSMENT: 'in-assessment', // PD sudah submit validasi tapi belum atau sedang dinilai validasi
      STATUS_DONE: 'done',

      form: {},
      isReady: false,
      isBusy: {
        submit: false,
        upload: false,
        submitPenilaian: false,
      },
    }
  },

  computed: {
    showHasilPenilaian: function () {
      return [this.STATUS_DONE].includes(this.statusPenilaian)
    }
  },

  mounted() {
    this.populateForm()    
  },

  methods: {
    populateForm() {
      this.komponenList.forEach(komponen => {
        komponen.sub_komponen.forEach(subKomponen => {
          subKomponen.kriteria.forEach(kriteria => {
            if (this.showHasilPenilaian) {
              this.$set(this.form, kriteria.id, {
                kriteria_id: kriteria.id,
                parameter_id: this.getLastRiwayat(kriteria.eviden.riwayat)?.parameter_id || null,
                eviden: this.getLastRiwayat(kriteria.eviden.riwayat)?.eviden || [],
              })
            } else {
              this.$set(this.form, kriteria.id, {
                kriteria_id: kriteria.id,
                parameter_id: kriteria.eviden ? kriteria.eviden.parameter_id : null,
                eviden: kriteria.eviden ? kriteria.eviden.eviden || [] : []
              })
            }
          });
        });
      });

      this.isReady = true
    },
    async submit(showSuccessNotif = true) {
      this.isBusy.submit = true

      try {
        await axios.post('/lke/eviden', {
          data: this.form,
        })

        if (showSuccessNotif) {
          Swal.fire('Berhasil', 'Berhasil simpan data', 'success')
        }
      } catch (error) {
        const { status } = error.response || {}
        
        if (status != 422) {
          Swal.fire('Gagal!', 'Gagal simpan data', 'error')
          throw error
        }
      } finally {
        this.isBusy.submit = false
      }
    },
    async upload(e, ref, kriteriaId, indexEviden) {
      const file = e.target.files[0]

      if (!file) {
        return alert('Pilih berkas terlebih dahulu!')
      }

      const fileSize = file.size / 1000

      if (fileSize > 2048) {
        alert('Ukuran berkas terlalu besar. Ukuran maksimal adalah 2MB!')

        return
      }

      const formData = new FormData();
      formData.append('file', e.target.files[0])

      this.isBusy.upload = ref

      try {
        const { data } = await axios.post('/lke/eviden/upload', formData, {
          headers: {
            'Content-Type': 'multipart/form-data',
          },
        })

        this.$set(this.form[kriteriaId].eviden, indexEviden, data)

        this.$refs[ref][0].reset()
      } catch (error) {
        const { status } = error.response || {}
        
        if (status != 422) {
          Swal.fire('Gagal!', 'Gagal simpan data', 'error')
          throw error
        }
      } finally {
        this.isBusy.upload = false
      }
    },
    submitPenilaianConfirm() {
      Swal.fire({
        title: 'Submit Validasi',
        text:  'Apakah Anda yakin? Data tidak dapat diubah setelah di-submit. Pastikan data sudah di simpan sebelum melakukan submit penilaian.',
        type: 'question',
        showConfirmButton: true,
        showCancelButton: true,
        confirmButtonText: 'Submit'
      })
      .then(({value}) => {
        if (value) {
          this.submitPenilaian()
        }
      })
    },
    async submitPenilaian() {
      try {
        await this.submit(false)
      } catch (error) {
        return false
      }

      this.isBusy.submitPenilaian = true

      try {
        const { data: { success, message, status } } = await axios.post('/lke/eviden/penilaian')

        if (!success) {
          Swal.fire('Gagal!', message, 'error')
          return
        }

        Swal.fire('Berhasil!', status == 'in-assessment-1' ? 'Berhasil mengajukan verifikasi' : 'Berhasil mengajukan validasi', 'success')
          .then(() => location.reload())
      } catch (error) {
        const { status } = error.response || {}
        
        if (status != 422) {
          Swal.fire('Gagal!', 'Gagal simpan data', 'error')
          throw error
        }
      } finally {
        this.isBusy.submitPenilaian = false
      }
    },
    getLastRiwayat(riwayat) {
      return riwayat[riwayat.length - 1]
    },
  }
}
</script>

<template>
  <b-card>
    <!-- button ini ada di bawah juga, pastikan kodenya sama -->
    <div class="text-right" v-if="!statusPenilaian">
      <b-button variant="success" @click="submitPenilaianConfirm" :disabled="isBusy.submitPenilaian">
        <b-spinner small v-if="isBusy.submitPenilaian"></b-spinner>
        Submit Validasi
      </b-button>

      <b-button variant="primary" @click="submit" :disabled="isBusy.submit">
        <b-spinner small v-if="isBusy.submit"></b-spinner>
        Simpan
      </b-button>
    </div>

    <b-alert variant="info" :show="statusPenilaian == STATUS_IN_ASSESSMENT">
      Sedang dalam tahap validasi
    </b-alert>
    <b-alert variant="success" :show="statusPenilaian == STATUS_DONE">
      Penilaian sudah selesai
    </b-alert>

    <b-table-simple class="mt-3 pb-5" responsive bordered hover>
      <b-thead>
        <b-tr>
          <b-th>No</b-th>
          <b-th>Komponen/Sub Komponen/Kriteria</b-th>
          <b-th>Jawaban</b-th>
          <b-th>Eviden</b-th>
          <b-th v-if="showHasilPenilaian">Hasil Penilaian</b-th>
          <b-th v-if="showHasilPenilaian">Keterangan</b-th>
          <b-th v-if="statusPenilaian == STATUS_DONE">Poin</b-th>
        </b-tr>
      </b-thead>
      <b-tbody v-if="isReady">
        <template v-for="(komponen, komponenIndex) in komponenList">
          <b-tr :key="komponen.id">
            <b-td :colspan="showHasilPenilaian ? 6 : 4"><h5>{{ komponen.nomor }}. {{ komponen.nama }}</h5></b-td>
            <b-td v-if="statusPenilaian == STATUS_DONE">
              <b>{{ komponen.total_skor }}</b>
            </b-td>
          </b-tr>

          <template v-for="(subKomponen, subKomponenIndex) in komponen.sub_komponen">
            <b-tr :key="subKomponen.id">
              <b-td><h6>{{ komponen.nomor }}.{{ subKomponen.nomor }}</h6></b-td>
              <b-td :colspan="showHasilPenilaian ? 5 : 4"><h6>{{ subKomponen.nama }}</h6></b-td>
              <b-td v-if="statusPenilaian == STATUS_DONE">
                <b>{{ subKomponen.total_skor }}</b>
              </b-td>
            </b-tr>

            <b-tr v-for="kriteria in subKomponen.kriteria" :key="kriteria.id" :class="{'bg-pink': showHasilPenilaian && (form[kriteria.id].parameter_id != getLastRiwayat(kriteria.eviden.riwayat).nilai || !getLastRiwayat(kriteria.eviden.riwayat).status) }">
              <b-td>{{ kriteria.nomor_full }}</b-td>
              <b-td>{{ kriteria.nama }}</b-td>
              <b-td>
                <v-select
                  v-if="!statusPenilaian"
                  :options="kriteria.parameter"
                  label="nama"
                  v-model="form[kriteria.id].parameter_id"
                  :reduce="opt => opt.id"
                  :clearable="false"
                  :disabled="kriteria.is_locked"
                >
                  <template #option="opt">
                    <div class="text-wrap">
                      <b>{{ opt.nama }}</b>
                      <div v-if="opt.nama != opt.keterangan">{{ opt.keterangan }}</div>
                    </div>
                  </template>
                </v-select>
                <span v-else>
                  <b>{{ getLastRiwayat(kriteria.eviden.riwayat).parameter.nama }}</b>
                  <div v-if="getLastRiwayat(kriteria.eviden.riwayat).parameter.nama != getLastRiwayat(kriteria.eviden.riwayat).parameter.keterangan">{{ getLastRiwayat(kriteria.eviden.riwayat).parameter.keterangan }}</div>
                </span>
              </b-td>
              <b-td>
                <!-- tampilkan updated_at khusus
                  (50) 3.1.5 	Dokumen Laporan Kinerja telah disampaikan tepat waktu.
                -->
                <div v-if="'3.1.5' == kriteria.nomor_full && kriteria.eviden" class="mb-2 badge badge-warning">
                  Upload pertama kali tanggal 31 Maret 2024
                </div>

                <!-- 1.2.2 Terus keterangan yg dihighlight kuning “Perubahan data terakhir pada 15 Agustus 2023 11:34” 
                  direname hardcode jadi tulisan “Upload Dokumen (Pertama) pada 31 Maret 2024 Pukul 23.59" -->
                <div v-if="'1.2.2' == kriteria.nomor_full && kriteria.eviden" class="mb-2 badge badge-warning">
                  Upload Dokumen (Pertama) sebelum 31 Maret 2024 Pukul 23.59
                </div>
                
                <!-- jika jumlah_eviden = 0 artinya eviden dinamis
                semua input eviden dinamis di disable dan perubahan data melalui inject ke db
                TODO: page penilaian -->
                <div v-if="!kriteria.jumlah_eviden">
                  <div v-for="(eviden, evidenIndex) of form[kriteria.id].eviden" :key="evidenIndex">
                    {{ evidenIndex + 1 }}. <a :href="eviden" target="_blank">{{ eviden }}</a>
                  </div>
                </div>

                <template v-else>
                  <div v-for="(keterangan, indexEviden) of kriteria.keterangan_eviden" :key="indexEviden">
                    <span v-if="kriteria.keterangan_eviden.length > 1">{{ indexEviden + 1 }}.</span> <a :href="form[kriteria.id].eviden[indexEviden]" target="_blank">{{ form[kriteria.id].eviden[indexEviden] }}</a>
                    
                    <b-form-input
                      v-if="(!statusPenilaian) && !kriteria.is_auto && keterangan == 'INPUT TEXT'"
                      class="mt-1"
                      :value="form[kriteria.id].eviden[indexEviden]"
                      @change="$set(form[kriteria.id].eviden, indexEviden, $event)"
                    >
                    </b-form-input>
                    <b-form-file
                      v-else-if="(!statusPenilaian) && !kriteria.is_auto && !keterangan.includes('https')"
                      class="mt-1"
                      @change="upload($event, `file-${komponenIndex}-${subKomponenIndex}-${kriteria.nomor}-${indexEviden}`, kriteria.id, indexEviden)"
                      :ref="`file-${komponenIndex}-${subKomponenIndex}-${kriteria.nomor}-${indexEviden}`"
                      :disabled="isBusy.upload == `file-${komponenIndex}-${subKomponenIndex}-${kriteria.nomor}-${indexEviden}`"
                    ></b-form-file>
                  </div>
                </template>
              </b-td>
              <b-td v-if="showHasilPenilaian">
                <b>{{ getLastRiwayat(kriteria.eviden.riwayat).parameter_nilai.nama }}</b> <br>
                {{ getLastRiwayat(kriteria.eviden.riwayat).parameter_nilai.keterangan }}
              </b-td>
              <b-td v-if="showHasilPenilaian">
                {{ getLastRiwayat(kriteria.eviden.riwayat).catatan }}
              </b-td>
              <b-td v-if="statusPenilaian == STATUS_DONE">
                <b>{{ kriteria.skor_akhir }}</b>
              </b-td>
            </b-tr>
          </template>
        </template>
      </b-tbody>
    </b-table-simple>

    <!-- button ini ada di atas juga, pastikan kodenya sama -->
    <div class="text-right" v-if="!statusPenilaian">
      <b-button variant="success" @click="submitPenilaianConfirm" :disabled="isBusy.submitPenilaian">
        <b-spinner small v-if="isBusy.submitPenilaian"></b-spinner>
        Submit Validasi
      </b-button>

      <b-button variant="primary" @click="submit" :disabled="isBusy.submit">
        <b-spinner small v-if="isBusy.submit"></b-spinner>
        Simpan
      </b-button>
    </div>
  </b-card>
</template>

<style scoped>
.bg-pink {
  background-color: pink;
}

.table-hover tbody tr.bg-pink:hover {
  background-color: rgb(249, 169, 182);
}
</style>