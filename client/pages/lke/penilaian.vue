<script>
import axios from 'axios'
import Swal from 'sweetalert2'
import { mapGetters } from 'vuex'

export default {
  middleware: ['role-validator-lke'],

  data() {
    return {
      STATUS_NULL: null, // PD belum submit validasi
      STATUS_IN_ASSESSMENT: 'in-assessment', // PD sudah submit validasi tapi belum atau sedang dinilai validasi
      STATUS_DONE: 'done',

      form: {},
      penilaianKomponen: {},
      isReady: false,
      isBusy: {
        getData: false,
        submit: false,
        close: false,
        cancelClose: false,
        exportExcel: false,
      },
      filter: {
        satuan_kerja_id: null,
      },
      komponenList: [],
      statusPenilaian: null,
    }
  },

  computed: {
    isDisablePenilaian() {
      return [this.STATUS_NULL, this.STATUS_DONE].includes(this.statusPenilaian)
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
    sendEvaluasiAI(index) {
      Swal.fire({
        title: "Menggenerate Evaluasi Berdasarkan AI",
        text: "Apakah Anda yakin?",
        type: "question",
        showConfirmButton: true,
        showCancelButton: true,
        confirmButtonColor: "#10944c",
        confirmButtonText: "Konfirmasi",
      }).then(async ({ value }) => {
        if (value) {
          this.isBusy = true;
          let dataDetail = await axios.get("evaluasi-ai", {
            params: {
              evaluasi_id: index,
            },
          });
          this.isBusy = false;
          this.getData();
        }
      });
    },
    async getData() {
      this.isBusy.getData = true

      const { data: {data: komponenList, statusPenilaian, penilaianKomponen} } = await axios.get('/lke/penilaian', {
        params: this.filter,
      })

      this.komponenList = komponenList
      this.statusPenilaian = statusPenilaian
      this.penilaianKomponen = penilaianKomponen

      this.populateForm()
      
      this.isBusy.getData = false
    },
    populateForm() {
      this.komponenList.forEach(komponen => {
        komponen.sub_komponen.forEach(subKomponen => {
          subKomponen.kriteria.forEach(kriteria => {
            const riwayat = this.getLastRiwayat(kriteria.eviden.riwayat)
            const riwayatId = riwayat.id
            const firstRiwayat = kriteria.eviden.riwayat[0]

            this.$set(this.form, riwayatId, {
              id: riwayatId,
              parameter_id: riwayat.parameter_id,
              eviden: riwayat.eviden || [],
              nilai: riwayat.nilai,
              catatan: riwayat.catatan,
              is_revisi: firstRiwayat.nilai != firstRiwayat.parameter_id,
              status: riwayat.status,
              kriteria: kriteria.nama,
            })
          });
        });
      });

      this.isReady = true
    },
    async submit(showSuccessNotif = true) {
      this.isBusy.submit = true

      try {
        await axios.post('/lke/penilaian', {
          satuan_kerja_id: this.filter.satuan_kerja_id,
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
        await this.submit(false)
      } catch (error) {
        return false
      }

      for (const key in this.form) {
        if (this.form[key].status === null) {
          Swal.fire('Gagal!', `Ada kriteria yang belum divalidasi. [${this.form[key].kriteria}]`, 'error')
          return false
        }
      }

      this.isBusy.close = true

      try {
        const { data: { status }} = await axios.post('/lke/penilaian/close', this.filter)

        Swal.fire('Berhasil', 'Berhasil Validasi', 'success')
        this.statusPenilaian = status
      } catch (error) {
        const { status } = error.response || {}
        
        if (status != 422) {
          Swal.fire('Gagal!', 'Gagal simpan data', 'error')
          throw error
        }
      } finally {
        this.isBusy.close = false
      }
    },
    getLastRiwayat(riwayat) {
      return riwayat[riwayat.length - 1]
    },
    cancelCloseConfirm() {
      Swal.fire({
        title: 'Batal Validasi',
        text:  'Apakah Anda yakin?',
        type: 'warning',
        showCancelButton: true,
        focusCancel: true,
        showConfirmButton: true,
        confirmButtonText: 'Batalkan',
        confirmButtonColor: '#bd2130',
      })
      .then(({value}) => {
        if (value) {
          this.cancelClose()
        }
      })
    },
    async cancelClose() {
      this.isBusy.cancelClose = true

      try {
        const { data: { status }} = await axios.post('/lke/penilaian/cancel-close', this.filter)

        Swal.fire('Berhasil', 'Berhasil Batal Validasi', 'success')
        this.statusPenilaian = status
      } catch (error) {
        const { status } = error.response || {}
        
        if (status != 422) {
          Swal.fire('Gagal!', 'Gagal simpan data', 'error')
          throw error
        }
      } finally {
        this.isBusy.cancelClose = false
      }
    },
    setStatus(riwayatId, status) {
      this.$set(this.form, riwayatId, {
        ...this.form[riwayatId],
        status,
      })
    },
    async exportExcel() {
      this.isBusy.exportExcel = true

      try {
        const { data } = await axios.get('/lke/penilaian/export', {
          params: this.filter,
          responseType: 'blob',
        })
        
        const url = window.URL.createObjectURL(new Blob([data]))
        const link = document.createElement('a')

        link.href = url
        link.setAttribute('download', 'Penilaian LKE Tahap Awal.xlsx')
        document.body.appendChild(link)
        link.click()
      } catch (error) {
        Swal.fire('Gagal!', 'Gagal export excel', 'error')
      } finally {
        this.isBusy.exportExcel = false
      }
    },
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
      <!-- button ini ada di bawah juga, pastikan kodenya sama -->
      <div class="text-right" v-if="statusPenilaian == STATUS_IN_ASSESSMENT">
        <b-button v-if="statusPenilaian == STATUS_IN_ASSESSMENT" variant="success" @click="closeConfirm()" :disabled="isBusy.close">
          <b-spinner small v-if="isBusy.close"></b-spinner>
          Validasi
        </b-button>
        <b-button variant="primary" @click="submit" :disabled="isBusy.submit">
          <b-spinner small v-if="isBusy.submit"></b-spinner>
          Simpan
        </b-button>
      </div>
      
      <template v-if="statusPenilaian == STATUS_DONE">
        <div class="text-right mb-3">
          <b-button variant="danger" @click="cancelCloseConfirm()" :disabled="isBusy.cancelClose">
            <b-spinner small v-if="isBusy.cancelClose"></b-spinner>
            Batal Validasi
          </b-button>
          <b-button variant="success" @click="exportExcel()" :disabled="isBusy.exportExcel">
            <b-spinner small v-if="isBusy.exportExcel"></b-spinner>
            Export
          </b-button>
        </div>

        <b-alert :show="true" variant="success">
          Anda sudah selesai melakukan penilaian
        </b-alert>
      </template>

      <h5 v-if="statusPenilaian == STATUS_NULL" class="text-center my-5">
        Perangkat Daerah belum melakukan submit penilaian
      </h5>
      <b-table-simple v-else class="mt-3 pb-5" responsive bordered hover>
        <b-thead>
          <b-tr>
            <b-th class="text-center align-middle">No</b-th>
            <b-th class="text-center align-middle">Komponen/Sub Komponen/Kriteria</b-th>
            <b-th class="text-center align-middle">Jawaban</b-th>
            <b-th class="text-center align-middle">Penilaian</b-th>
            <b-th class="text-center align-middle">Eviden</b-th>
            <b-th class="text-center align-middle">Terima/Tolak</b-th>
             <b-th class="text-center align-middle">Generate AI</b-th>
             <b-th class="text-center align-middle">Label AI</b-th>
             <b-th class="text-center align-middle">Score AI</b-th>
             <b-th class="text-center align-middle">Reasoning AI</b-th>
            <b-th class="text-center align-middle">Catatan</b-th>
          </b-tr>
        </b-thead>
        <b-tbody v-if="isReady">
          <b-td colspan="11" class="text-left text-primary">
          <h2><strong>Total Nilai: {{
            komponenList.reduce((total, komponen) => {
              return total + komponen.sub_komponen.reduce((subTotal, subKomponen) => {
                return subTotal + subKomponen.kriteria.reduce((sum, kriteria) => {
                  const lastRiwayat = getLastRiwayat(kriteria.eviden.riwayat);
                  const nilai = form[lastRiwayat.id]?.nilai;
                  const parameter = kriteria.parameter.find(p => p.id === nilai);
                  return sum + (parameter ? parameter.skor * kriteria.bobot : 0);
                }, 0);
              }, 0);
            }, 0).toFixed(2)
          }}</strong>
          </h2>
        </b-td>
          <template v-for="(komponen, komponenIndex) in komponenList">
            <b-tr :key="komponen.id">
              <b-td colspan="11"><h5>{{ komponen.nomor }}. {{ komponen.nama }}</h5>
                <p>Total Nilai Komponen: {{
                komponen.sub_komponen.reduce((total, subKomponen) => {
                  return total + subKomponen.kriteria.reduce((sum, kriteria) => {
                    const lastRiwayat = getLastRiwayat(kriteria.eviden.riwayat);
                    const nilai = form[lastRiwayat.id]?.nilai;
                    const parameter = kriteria.parameter.find(p => p.id === nilai);
                    return sum + (parameter ? parameter.skor * kriteria.bobot : 0);
                  }, 0);
                }, 0).toFixed(2)
              }}</p></b-td>
            </b-tr>

            <template v-for="(subKomponen, subKomponenIndex) in komponen.sub_komponen">
              <b-tr :key="subKomponen.id">
                <b-td><h6>{{ komponen.nomor }}.{{ subKomponen.nomor }}</h6></b-td>
                <b-td colspan="10"><h6>{{ subKomponen.nama }}</h6>
                  <p>Total Nilai Sub Komponen: {{
                  subKomponen.kriteria.reduce((sum, kriteria) => {
                    const lastRiwayat = getLastRiwayat(kriteria.eviden.riwayat);
                    const nilai = form[lastRiwayat.id]?.nilai;
                    const parameter = kriteria.parameter.find(p => p.id === nilai);
                    return sum + (parameter ? parameter.skor * kriteria.bobot : 0);
                  }, 0).toFixed(2)
                }}</p></b-td>
              </b-tr>

              <b-tr v-for="(kriteria, kriteriaIndex) in subKomponen.kriteria" :key="kriteria.id" :class="{'bg-pink': form[getLastRiwayat(kriteria.eviden.riwayat).id].is_revisi || form[getLastRiwayat(kriteria.eviden.riwayat).id].nilai != getLastRiwayat(kriteria.eviden.riwayat).parameter_id }">
                <b-td>{{ kriteria.nomor_full }}</b-td>
                <b-td>{{ kriteria.nama }}</b-td>

                <b-td>
                  <b>{{ kriteria.eviden.riwayat[0].parameter.nama }}</b> <br>
                  {{ kriteria.eviden.riwayat[0].parameter.keterangan }}
                </b-td>

                <b-td>
                  <div v-if="isDisablePenilaian">
                    <b>{{ kriteria.parameter.find(_ => _.id == form[getLastRiwayat(kriteria.eviden.riwayat).id].nilai)?.nama }}</b>
                    <div>{{ kriteria.parameter.find(_ => _.id == form[getLastRiwayat(kriteria.eviden.riwayat).id].nilai)?.keterangan }}</div>
                  </div>
                  <div v-else>
                    <div class="mb-2 font-weight-bold">Skor: {{ kriteria.parameter.find(_ => _.id == form[getLastRiwayat(kriteria.eviden.riwayat).id].nilai)?.skor }}</div>
                    <v-select
                      :options="kriteria.parameter"
                      label="nama"
                      v-model="form[getLastRiwayat(kriteria.eviden.riwayat).id].nilai"
                      :reduce="opt => opt.id"
                      :clearable="false"
                    >
                      <template #option="opt">
                        <b>{{ opt.nama }}</b>
                        <div v-if="opt.nama != opt.keterangan">{{ opt.keterangan }}</div>
                      </template>
                    </v-select>
                  </div>
                </b-td>
                <b-td>
                  <!-- tampilkan updated_at khusus
                    (50) 3.1.5 	Dokumen Laporan Kinerja telah disampaikan tepat waktu.
                  -->
                  <div v-if="'3.1.5' == kriteria.nomor_full" class="mb-2 badge badge-warning">
                    Upload pertama kali tanggal 31 Maret 2024
                  </div>

                  <!-- 1.2.2 Terus keterangan yg dihighlight kuning “Perubahan data terakhir pada 15 Agustus 2023 11:34” 
                    direname hardcode jadi tulisan “Upload Dokumen (Pertama) pada 31 Maret 2024 Pukul 23.59" -->
                  <div v-if="'1.2.2' == kriteria.nomor_full && kriteria.eviden" class="mb-2 badge badge-warning">
                    Upload Dokumen (Pertama) sebelum 31 Maret 2025 Pukul 23.59
                  </div>

                  <div v-for="(eviden, indexEviden) of form[getLastRiwayat(kriteria.eviden.riwayat).id].eviden" :key="`${komponenIndex}-${subKomponenIndex}-${kriteriaIndex}-${indexEviden}`">
                    <span v-if="form[getLastRiwayat(kriteria.eviden.riwayat).id].eviden.length > 1">{{ indexEviden + 1 }}.</span> <a :href="form[getLastRiwayat(kriteria.eviden.riwayat).id].eviden[indexEviden]" target="_blank">{{ form[getLastRiwayat(kriteria.eviden.riwayat).id].eviden[indexEviden] }}</a>
                  </div>
                </b-td>
                <b-td class="text-center">
                  <b-badge v-if="form[getLastRiwayat(kriteria.eviden.riwayat).id].status === true" variant="success">
                    Diterima
                  </b-badge>
                  <b-badge v-else-if="form[getLastRiwayat(kriteria.eviden.riwayat).id].status === false" variant="danger">
                    Ditolak
                  </b-badge>

                  <div v-if="!isDisablePenilaian" class="mt-2 d-flex text-center">
                    <b-button @click="setStatus(getLastRiwayat(kriteria.eviden.riwayat).id, true)" variant="success" size="sm" class="m-1 rounded-circle" v-b-tooltip.hover title="Terima">
                      <i class="fa fa-check" aria-hidden="true"></i>
                    </b-button>
                    <b-button @click="setStatus(getLastRiwayat(kriteria.eviden.riwayat).id, false)" variant="danger" size="sm" class="m-1 rounded-circle" v-b-tooltip.hover title="Tolak">
                      <i class="fa fa-times" aria-hidden="true"></i>
                    </b-button>
                  </div>
                </b-td>
                <b-td>
                   <b-button @click="sendEvaluasiAI(getLastRiwayat(kriteria.eviden.riwayat).id, false)" variant="primary" size="sm" class="m-1 rounded-circle" v-b-tooltip.hover title="Generate AI">
                      <i class="fa fa-microchip" aria-hidden="true"></i>
                    </b-button>
                </b-td>
                 <b-td>
                  {{ getLastRiwayat(kriteria.eviden.riwayat).label_ai }}
                </b-td>
                 <b-td>
                  {{ getLastRiwayat(kriteria.eviden.riwayat).score_ai }}
                </b-td>
                 <b-td>
                  {{ getLastRiwayat(kriteria.eviden.riwayat).reasoning_ai }}
                </b-td>

                <b-td style="min-width: 300px;">
                  <div v-if="isDisablePenilaian">
                    {{ form[getLastRiwayat(kriteria.eviden.riwayat).id].catatan }}
                  </div>
                  <b-form-textarea
                    v-else
                    v-model="form[getLastRiwayat(kriteria.eviden.riwayat).id].catatan"
                    rows="3"
                  ></b-form-textarea>
                </b-td>
              </b-tr>
            </template>
          </template>
        </b-tbody>
      </b-table-simple>

      <!-- button ini ada di atas juga, pastikan kodenya sama -->
      <div class="text-right" v-if="statusPenilaian == STATUS_IN_ASSESSMENT">
        <b-button v-if="statusPenilaian == STATUS_IN_ASSESSMENT" variant="success" @click="closeConfirm()" :disabled="isBusy.close">
          <b-spinner small v-if="isBusy.close"></b-spinner>
          Validasi
        </b-button>
        <b-button variant="primary" @click="submit" :disabled="isBusy.submit">
          <b-spinner small v-if="isBusy.submit"></b-spinner>
          Simpan
        </b-button>
      </div>
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