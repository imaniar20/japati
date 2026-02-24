<template>
  <div>
    <b-card>
      <!-- <div class="text-right mb-3">
        <b-button variant="primary" @click="syncTrk()" :disabled="isSyncBusy">
          <i v-if="isSyncBusy" class="fa fa-spinner fa-pulse" aria-hidden="true"></i>
          Sync TRK
        </b-button>
      </div> -->

      <form @submit.prevent="update()">
        <b-form-group v-if="$role.isSuper()" label-class="font-weight-bold pt-0" label-cols="12" label-cols-md="2" label="Satuan Kerja" label-for="satuan-kerja">
          <b-form-input :value="form.satuan_kerja.satuan_kerja_nama" plaintext></b-form-input>
        </b-form-group>
        <!-- <b-form-group label-cols="12" label-cols-md="2" label="Relasi Sasaran Strategis RPJMD & IKU Gubernur" label-class="font-weight-bold" label-for="sasaran_strategis_rpjmd">
          <v-select id="sasaran_strategis_rpjmd" 
            v-model="form.sasaran_strategis_rpjmd_id" 
            :options="sasaranStrategisRpjmd" 
            :reduce="opt => opt.id" 
            label="id" 
            placeholder="Pilih relasi"
            :clearable="false"
            :filterBy="$helper.vSelectFilterBy('sasaran_strategis.sasaran', 'indikator_sasaran_strategis.indikator')"
          >
            <template #search="{attributes, events}">
              <input
                class="vs__search"
                v-bind="attributes"
                v-on="events"
              />
            </template>
            <template #option="opt">
              <div class="border-bottom py-2 my-1">
                <div>Sasaran strategis: <b>{{ opt.sasaran_strategis.sasaran }}</b></div>
                <div>IKU Gubernur: <b>{{ opt.indikator_sasaran_strategis.indikator }}</b></div>
                <div>Capaian {{ $helper.getTahunKinerja() }}: <b>{{ opt[$helper.getKeyTahun('capaian')] ? opt[$helper.getKeyTahun('capaian')] + ' %' : '-' }}</b></div>
                <div>Target {{ $helper.getTahunKinerja() }}: <b>{{ opt[$helper.getKeyTahun('target')] }}</b></div>
              </div>
            </template>
            <template #selected-option="opt">
              <div style="display: flex; align-items: baseline">
                <div>{{ opt.sasaran_strategis?.sasaran }} | {{ opt.indikator_sasaran_strategis?.indikator }}</div>
              </div>
            </template>
          </v-select>
        </b-form-group>
        <b-form-group label-class="font-weight-bold pt-0" label-cols="12" label-cols-md="2" label="Kinerja Cross-Cutting">
          <v-select id="kinerja-bayangan" 
            v-model="form.kinerja_bayangan_id" 
            :options="kinerjaBayangan" 
            :reduce="opt => opt.id" 
            placeholder="Pilih Kinerja Cross-Cutting"
            :filterBy="$helper.vSelectFilterBy('sasaran', 'indikator')"
          >
            <template #search="{attributes, events}">
              <input
                class="vs__search"
                v-bind="attributes"
                v-on="events"
              />
            </template>

            <template #option="item">
              <div><b>Sasaran:</b> {{ item.sasaran }}</div>
              <div><b>Indikator:</b> {{ item.indikator }}</div>
              <hr>
            </template>
            <template #selected-option="item">
              <div style="display: flex; align-items: baseline">
                {{ item.sasaran }} - {{ item.indikator }}
              </div>
            </template>
          </v-select>
        </b-form-group>
        <b-form-group label-cols="12" label-cols-md="2" label="Relasi Sasaran Strategis Perangkat Daerah" label-class="font-weight-bold" label-for="sasaran_strategis_pd">
          <v-select id="sasaran_strategis_pd" 
            v-model="form.sasaran_strategis_pd_id" 
            :options="sasaranStrategisPd" 
            :reduce="opt => opt.id" 
            label="id" 
            placeholder="Pilih relasi"
            :clearable="false"
            :filterBy="$helper.vSelectFilterBy('sasaran_strategis_satker', 'iku')"
          >
            <template #search="{attributes, events}">
              <input
                class="vs__search"
                v-bind="attributes"
                v-on="events"
              />
            </template>
            <template #option="opt">
              <div class="border-bottom py-2 my-1">
                <div>Sasaran Strategis PD: <b>{{ opt.sasaran_strategis_satker }}</b></div>
                <div>IKU PD: <b>{{ opt.iku }}</b></div>
                <div>Target {{ $helper.getTahunKinerja() }}: <b>{{ opt[$helper.getKeyTahun('target')] }}</b></div>
              </div>
            </template>
            <template #selected-option="opt">
              <div style="display: flex; align-items: baseline">
                <div>{{ opt.sasaran_strategis_satker }} | {{ opt.iku }}</div>
              </div>
            </template>
          </v-select>
        </b-form-group> -->
        <b-form-group label-cols="12" label-cols-md="2" label="Relasi Kinerja Program" label-class="font-weight-bold" label-for="kinerja_program">
          <v-select id="kinerja_program" 
            v-model="form.kinerja_program_id" 
            :options="kinerjaProgram" 
            :reduce="opt => opt.id" 
            label="id" 
            placeholder="Pilih relasi"
            :clearable="false"
            :filterBy="$helper.vSelectFilterBy('program.nama', 'indikator')"
          >
            <template #search="{attributes, events}">
              <input
                class="vs__search"
                v-bind="attributes"
                v-on="events"
              />
            </template>
            <template #option="opt">
              <div class="border-bottom py-2 my-1">
                <div>Sasaran: <b>{{ opt.sasaran }}</b></div>
                <div>Program: <b>{{ opt.program ? opt.program.nama : '-' }}</b></div>
                <div>Indikator program: <b>{{ opt.indikator }}</b></div>
                <div>Pagu anggaran: <b>{{ opt.anggaran | rupiah }}</b></div>
              </div>
            </template>
            <template #selected-option="opt">
              <div style="display: flex; align-items: baseline">
                <div v-if="opt.program">{{ opt.sasaran }} | {{ opt.program.nama }} | {{ opt.indikator }}</div>
              </div>
            </template>
          </v-select>
        </b-form-group>
        <!-- <b-form-group label-cols="12" label-cols-md="2" label="Program" label-class="font-weight-bold" label-for="program">
          <v-select id="program" 
            v-model="form.program_id" 
            :options="program" 
            :reduce="opt => opt.id" 
            label="nama" 
            placeholder="Pilih program"
            :clearable="false"
          >
            <template #search="{attributes, events}">
              <input
                class="vs__search"
                :required="!form.program_id"
                v-bind="attributes"
                v-on="events"
              />
            </template>
          </v-select>
        </b-form-group> -->
        <!-- <b-form-group label-cols="12" label-cols-md="2" label="Sasaran Program" label-class="font-weight-bold" label-for="sasaran-program">
          <v-select id="sasaran-program" 
            v-model="form.sasaran_program" 
            :options="sasaranProgramOptions" 
            placeholder="Pilih sasaran program"
            :clearable="false"
          >
            <template #search="{attributes, events}">
              <input
                class="vs__search"
                :required="!form.sasaran_program"
                v-bind="attributes"
                v-on="events"
              />
            </template>
          </v-select>
        </b-form-group> -->
        <b-form-group label-cols="12" label-cols-md="2" label="Sasaran Kegiatan" label-class="font-weight-bold" label-for="sasaran">
          <b-form-input id="sasaran" v-model="form.sasaran"></b-form-input>
        </b-form-group>
        <b-form-group label-cols="12" label-cols-md="2" label="Kegiatan" label-class="font-weight-bold" label-for="kegiatan">
          <v-select id="kegiatan" 
            v-model="form.kegiatan_id" 
            :options="kegiatan" 
            :reduce="opt => opt.id"
            label="nama" 
            placeholder="Pilih kegiatan"
          >
          </v-select>
        </b-form-group>
        <b-form-group label-cols="12" label-cols-md="2" label="Indikator Kegiatan" label-class="font-weight-bold" label-for="indikator">
          <b-form-input id="indikator" v-model="form.indikator" required></b-form-input>
        </b-form-group>
        <b-form-group label-cols="12" label-cols-md="2" label="Pengampu" label-class="font-weight-bold" label-for="pengampu">
          <b-form-radio-group
            id="pengampu"
            v-model="form.pengampu"
            name="pengampu"
            class="mb-2"
          >
            <b-form-radio value="unit-kerja">Unit Kerja</b-form-radio>
            <b-form-radio value="tim-kerja">Tim Kerja</b-form-radio>
          </b-form-radio-group>

          <v-select id="vso"
            v-if="form.pengampu == 'unit-kerja'"
            v-model="form.v_struktur_organisasi_id"
            :options="vso"
            :reduce="opt => opt.id"
            label="unit_kerja_nama" 
            placeholder="Pilih unit kerja"
            :filterBy="$helper.vSelectFilterBy('nama', 'ketua.peg_nama')"
          >
          </v-select>

          <div v-if="form.pengampu == 'tim-kerja'">
            <v-select id="tim-kerja"
              v-model="form.tim_kerja_id"
              :options="timKerja"
              :reduce="opt => opt.id"
              label="nama" 
              placeholder="Pilih Tim Kerja"
            >
              <template #option="{ nama, ketua }">
                {{ nama }} - {{ ketua?.peg_nama }}
              </template>
              <template #selected-option="{ nama, ketua }">
                {{ nama }} - {{ ketua?.peg_nama }}
              </template>
            </v-select>

            <div class="mt-2 text-right">
              <b-button size="sm" variant="primary" @click="$nuxt.$emit('show-modal-tambah-tim-kerja', 'kinerja-kegiatan')">
                <i class="ti-plus" aria-hidden="true"></i> Tambah Tim Kerja
              </b-button>
            </div>
          </div>
        </b-form-group>
        <b-form-group label-cols="12" label-cols-md="2" label="Satuan" label-class="font-weight-bold" label-for="satuan">
          <b-form-input id="satuan" v-model="form.satuan" required></b-form-input>
        </b-form-group>
        <b-form-group label-cols="12" label-cols-md="2" label="Target Kinerja" label-class="font-weight-bold" label-for="target">
          <b-form-input id="target" type="number" step="0.001" v-model="form.target" required></b-form-input>
        </b-form-group>
        <b-form-group label-cols="12" label-cols-md="2" label="Realisasi Kinerja" label-class="font-weight-bold" label-for="realisasi">
          <b-form-input id="realisasi" type="number" step="0.001" v-model="form.realisasi"></b-form-input>
        </b-form-group>
        <b-form-group label-cols="12" label-cols-md="2" label="Capaian Kinerja (%)" label-class="font-weight-bold" label-for="capaian">
          <b-form-input type="number" step="0.01" id="capaian" v-model="form.capaian"></b-form-input>
        </b-form-group>
        <b-form-group v-if="form.capaian < 100" label-cols="12" label-cols-md="2" label="Penyebab Kegagalan" label-class="font-weight-bold" label-for="penyebab_kegagalan">
          <b-form-textarea id="penyebab_kegagalan" v-model="form.penyebab_kegagalan" placeholder="Tuliskan penyebab kegagalan" rows="3"></b-form-textarea>
        </b-form-group>
        <b-form-group label-cols="12" label-cols-md="2" label="Pagu Anggaran" label-class="font-weight-bold" label-for="anggaran">
          <money class="form-control" id="anggaran" v-model="form.anggaran" v-bind="money" required></money>
        </b-form-group>
        <b-form-group label-cols="12" label-cols-md="2" label="Realisasi Anggaran" label-class="font-weight-bold" label-for="realisasi_anggaran">
          <money class="form-control" id="realisasi_anggaran" v-model="form.realisasi_anggaran" v-bind="money" required></money>
        </b-form-group>
        <b-form-group label-cols="12" label-cols-md="2" label="Capaian Anggaran (%)" label-class="font-weight-bold" label-for="capaian_anggaran">
          <b-form-input type="number" step="0.01" id="capaian_anggaran" v-model="form.capaian_anggaran" disabled></b-form-input>
        </b-form-group>
        <b-card title="Definisi Operasional">
          <b-form-group
            label-cols="12"
            label-cols-md="2"
            label="Narasi"
            label-class="font-weight-bold"
            label-for="narasi"
          >
            <b-form-input
              id="target"
              v-model="form.do_narasi"
              placeholder="Narasi Definisi Operasional"
            ></b-form-input>
          </b-form-group>
          <b-form-group
            label-cols="12"
            label-cols-md="2"
            label="Rumus"
            label-class="font-weight-bold"
            label-for="rumus"
          >
            <b-form-file
              :id="`definisi-operasional-img`"
              :ref="`definisi-operasional-img`"
              @change="validateUploadDefinisiOperasional($event)"
              placeholder="Pilih Gambar"
              accept=".jpg, .png"
            ></b-form-file>
          </b-form-group>

          <img v-if="form.do_rumus" :src="form.do_rumus"  width="400" height="200" class="mb-2 mt-2 rounded mx-auto d-block" alt="Gambar Rumus">

          <b-form-group
            label-cols="12"
            label-cols-md="2"
            label="Keterangan"
            label-class="font-weight-bold"
            label-for="keterangan"
          >
            <b-form-textarea
              id="keterangan"
              v-model="form.do_keterangan"
              placeholder="Keterangan Definisi Operasional"
            ></b-form-textarea>
          </b-form-group>

          <b-form-group
            label-cols="12"
            label-cols-md="2"
            label="Sumber"
            label-class="font-weight-bold"
            label-for="sumber"
          >
            <b-form-input
              id="sumber"
              v-model="form.do_sumber"
              placeholder="Sumber Data Definisi Operasional"
            ></b-form-input>
          </b-form-group>
        </b-card>

        <b-row class="mt-5">
          <b-col xs md="6" v-for="(arrMonth, index) of arrayChunk2($const.months, 6)" :key="index">
            <b-row v-for="(month, monthIndex) of arrMonth" :key="monthIndex">
              <b-col xs sm="6">
                <b-form-group :label="`Target Kinerja ${month[1]}`" label-class="font-weight-bold" :label-for="`target-${month[0]}`">
                  <b-form-input type="number" step="0.001" :id="`target-${month[0]}`" v-model="form.target_bulanan[month[0]]" required></b-form-input>
                </b-form-group>
              </b-col>
              <b-col xs sm="6">
                <b-form-group :label="`Realisasi Kinerja ${month[1]}`" label-class="font-weight-bold" :label-for="`realisasi-${month[0]}`">
                  <b-form-input type="number" step="0.001" :id="`realisasi-${month[0]}`" v-model="form.realisasi_bulanan[month[0]]"></b-form-input>
                </b-form-group>
              </b-col>
            </b-row>
          </b-col>
        </b-row>

        <b-row class="mt-5">
          <b-col xs md="6" v-for="(arrMonth, index) of arrayChunk2($const.months, 6)" :key="index">
            <b-row v-for="(month, monthIndex) of arrMonth" :key="monthIndex">
              <b-col xs sm="6">
                <b-form-group :label="`Pagu Anggaran ${month[1]}`" label-class="font-weight-bold" :label-for="`anggaran-${month[0]}`">
                  <money class="form-control" :id="`anggaran-${month[0]}`" v-model="form.anggaran_bulanan[month[0]]"  v-bind="money"></money>
                </b-form-group>
              </b-col>
              <b-col xs sm="6">
                <b-form-group :label="`Realisasi Anggaran ${month[1]}`" label-class="font-weight-bold" :label-for="`realisasi-anggaran-${month[0]}`">
                  <money class="form-control" :id="`realisasi-anggaran-${month[0]}`" v-model="form.realisasi_anggaran_bulanan[month[0]]" v-bind="money"></money>
                </b-form-group>
              </b-col>
            </b-row>
          </b-col>
        </b-row>

        <div class="text-right mt-5">
          <b-button variant="primary" :disabled="isBusy" type="submit">
            <i v-if="isBusy" class="fa fa-spinner fa-pulse" aria-hidden="true"></i>
            <i v-else class="fa fa-floppy-o" aria-hidden="true"></i>
            Simpan
          </b-button>
        </div>
      </form>

      <TambahTimKerja :satuan-kerja-id="form.satuan_kerja_id" @submit="setTimKinerja" />
    </b-card>

    <b-card title="Diagnosa Critical Success Factor (CSF) Gagal" class="mt-2">
      <KinerjaTidakTercapai type="kinerja-kegiatan" :id="id" />
    </b-card>

    <b-card title="Diagnosa Critical Success Factor (CSF)" class="mt-2">
      <KinerjaTercapai type="kinerja-kegiatan" :id="id" />
    </b-card>

    <b-card title="Solusi dari Sasaran Kinerja Tahun Lalu:" class="mt-2">
      <SolusiKinerja type="kinerja-kegiatan" :id="id" :solusi="form.solusi" />
    </b-card>
  </div>
</template>

<script>
import axios from 'axios'
import Swal from 'sweetalert2'
import { Money } from 'v-money'
import { arrayChunk2 } from '~/plugins/utils'
import TambahTimKerja from '~/components/modals/TambahTimKerja'
import KinerjaTidakTercapai from '~/components/KinerjaTidakTercapai.vue'
import SolusiKinerja from '~/components/SolusiKinerja.vue'
import KinerjaTercapai from '~/components/KinerjaTercapai.vue'
import { moneyFormat } from '~/utils/formater'

export default {
  middleware: [
    'auth',
    'role-perangkat-daerah',
    function({ store, redirect }) {
      const canEditValidasiPerencanaan = store.getters['validasi-perencanaan/canEdit']

      if (!canEditValidasiPerencanaan) {
        return redirect('/kinerja-kegiatan')
      }
    },
  ],
  components: {
    Money,
    TambahTimKerja,
    KinerjaTidakTercapai,
    SolusiKinerja,
    KinerjaTercapai,
  },
  async asyncData({ params }) {
    const id = parseInt(params.id)
    const { data: {
      program,
      kinerjaProgram,
      kegiatan,
      form,
      sasaranStrategisRpjmd,
      sasaranStrategisPd,
      vso,
      timKerja,
      kinerjaBayangan,
    }} = await axios.get(`kinerja-kegiatan/${id}/edit`)

    return {
      program,
      kinerjaProgram,
      kegiatan,
      form,
      id,
      sasaranStrategisRpjmd,
      sasaranStrategisPd,
      vso,
      timKerja,
      kinerjaBayangan,
    }
  },
  data() {
    return {
      isBusy: false,
      isKinerjaProgramBusy: false,
      money: moneyFormat,
      isSyncBusy: false,
    }
  },
  computed: {
    sasaranProgramOptions: function () {
      if (!this.kinerjaProgram.length) return [];
      
      const formatted = this.kinerjaProgram.filter(el => el.sasaran).map(el => el.sasaran.trim())
      
      return [...new Set(formatted)]
    },
    realisasiAnggaran: function () {
      return Object.values(this.form.realisasi_anggaran_bulanan).reduce((acc, next) => acc + next)
    },
    capaianAnggaran: function () {
      return Math.round(((this.realisasiAnggaran / this.form.anggaran * 100) + Number.EPSILON) * 1000) / 1000
    },
    capaian: function () {
      return Math.round(((this.form.realisasi / this.form.target * 100) + Number.EPSILON) * 100) / 100
    },
  },
  methods: {
    arrayChunk2,
    async update() {
      this.isBusy = true;
      if (!(await this.uploadDefinisiOperasional())) {
        return;
      }

      axios.patch(`kinerja-kegiatan/${this.id}`, this.form)
      .then(() => {
        Swal.fire({
          type: 'success',
          title: 'Berhasil menyimpan data'
        }).then(() =>  {
          if(this.$route.query.from == 'cascading' ){
            this.$router.push('/display-mikro/cascading')
          }
          else{
            this.$router.push('/kinerja-program')
          }
          
        })
      }).catch(() => {

        Swal.fire({
          type: 'error',
          title: 'Gagal menyimpan data!',
        })
      }).then(() => this.isBusy = false)
    },
    syncTrk() {
      this.isSyncBusy = true

      axios.post(`kinerja-kegiatan/${this.id}/sync`)
        .then(({ data }) => {
          this.form.realisasi = data.realisasi
          this.form.capaian = data.capaian
          this.form.realisasi_bulanan = Object.assign({}, data.realisasi_bulanan)
          
          Swal.fire({
            type: 'success',
            title: 'Berhasil sinkronisasi data TRK'
          })
        }).catch(({ response }) => {
          if (response && response.data && response.data.message) {
            Swal.fire({
              type: 'error',
              title: 'Gagal mengambil data',
              text: response.data.message,
            })
          }
        }).then(() => this.isSyncBusy = false)
    },
    setTimKinerja(data) {
      this.timKerja.push(data)
      this.form.tim_kerja_id = data.id
    },
    
    resetDefinisiOperasional() {
      this.$refs[`definisi-operasional-img`].reset();
    },
    validateUploadDefinisiOperasional(e) {
      const file = e.target.files[0];

      if (!file) {
        return alert("Pilih berkas terlebih dahulu!");
      }

      const fileSize = file.size / 1000;

      if (fileSize > 2048) {
        this.resetDefinisiOperasional();
        alert("Ukuran berkas terlalu besar. Ukuran maksimal adalah 2MB!");

        return;
      }
    },
    async uploadDefinisiOperasional() {
      const file = this.$refs[`definisi-operasional-img`].files[0];
      if(file){
          try {
          const formData = new FormData();
          formData.append("file", file);

          const { data } = await axios.post(
            "kinerja-kegiatan/upload-definisi-operasional",
            formData,
            {
              headers: {
                "Content-Type": "multipart/form-data",
              },
            }
          );

          this.form.do_rumus = data;
          this.resetDefinisiOperasional();

          return true;
        } catch (error) {
          console.log(error);
          Swal.fire({
            title: "Gagal upload Gambar DO",
            type: "error",
          });

          return false;
        }
      }
      else{
        return true;
      }
      
    },
  },
  watch: {
    'form.kegiatan_id': function (newVal) {
      const anggaran = this.kegiatan.find(el => el.id == newVal).anggaran
      
      this.form.anggaran = anggaran
    },
    realisasiAnggaran: function (newVal) {
      this.form.realisasi_anggaran = newVal
    },
    capaianAnggaran: function (newVal) {
      this.form.capaian_anggaran = newVal
    },
    capaian: function (newVal) {
      this.form.capaian = newVal
    },
  },
}
</script>