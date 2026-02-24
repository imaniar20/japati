<template>
  <div>
    <b-card>
      <form @submit.prevent="update()">
        <b-form-group v-if="$role.isSuper()" label-class="font-weight-bold pt-0" label-cols="12" label-cols-md="2" label="Satuan Kerja" label-for="satuan-kerja">
          <b-form-input :value="form.satuan_kerja.satuan_kerja_nama" plaintext></b-form-input>
        </b-form-group>
        <b-form-group label-cols="12" label-cols-md="2" label="Urutan" label-class="font-weight-bold" label-for="order">
          <b-form-input type="number" step="1" id="order" v-model="form.order" min="0" required></b-form-input>
        </b-form-group>
        <b-form-group label-cols="12" label-cols-md="2" label="Sasaran Strategis PD" label-class="font-weight-bold" label-for="sasaran-strategis-pd">
          <v-select id="sasaran-strategis-pd" 
            v-model="form.sasaran_strategis_pd_id" 
            :options="sasaranStrategisPd" 
            :reduce="opt => opt.id" 
            placeholder="Pilih Sasaran Strategis PD"
            label="sasaran_strategis_satker"
            :clearable="false"
            disabled
          >
            <template #search="{attributes, events}">
              <input
                class="vs__search"
                :required="!form.sasaran_strategis_pd_id"
                v-bind="attributes"
                v-on="events"
              />
            </template>
          </v-select>
          <small>Sasaran Strategis PD akan mengikuti saat IKU PD diubah. Data ini berdasarkan data pada menu <nuxt-link to="/sasaran-strategis-pd">Sasaran Strategis Perangkat Daerah</nuxt-link>.</small>
        </b-form-group>
        <b-form-group label-cols="12" label-cols-md="2" label="IKU PD" label-class="font-weight-bold" label-for="satker-iku">
          <template v-if="canEditValidasiPerencanaanTahap1">
            <v-select id="satker-iku" 
              v-model="form.satker_iku_id" 
              :options="sasaranStrategisPd" 
              :reduce="opt => opt.id" 
              placeholder="Pilih IKU PD"
              label="iku"
              :clearable="false"
            >
              <template #search="{attributes, events}">
                <input
                  class="vs__search"
                  :required="!form.satker_iku_id"
                  v-bind="attributes"
                  v-on="events"
                />
              </template>
            </v-select>
          </template>
          <template v-else>
            <b-form-input :value="sasaranStrategisPd.find(opt => opt.id === form.satker_iku_id)?.iku" disabled></b-form-input>
            <small class="text-warning">Tidak dapat mengubah data karena sudah divalidasi</small>
          </template>
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
        <b-form-group label-cols="12" label-cols-md="2" label="Sasaran Program" label-class="font-weight-bold" label-for="sasaran">
          <template v-if="canEditValidasiPerencanaanTahap1">
            <v-select id="sasaran"
              v-if="!isCustomSasaran"
              v-model="form.sasaran" 
              :options="sasaranOptions" 
              placeholder="Pilih sasaran program"
              :clearable="false"
            >
              <template #search="{attributes, events}">
                <input
                  class="vs__search"
                  v-bind="attributes"
                  v-on="events"
                />
              </template>
            </v-select>
            <b-form-input v-else id="sasaran" v-model="form.sasaran" placeholder="Tulis sasaran program"></b-form-input>
            <b-form-checkbox v-model="isCustomSasaran" name="checkbox-sasaran" :value="true" :unchecked-value="false">
              Tulis manual sasaran program
            </b-form-checkbox>
          </template>
          <template v-else>
            <b-form-input :value="form.sasaran" disabled></b-form-input>
            <small class="text-warning">Tidak dapat mengubah data karena sudah divalidasi</small>
          </template>
        </b-form-group>
        <b-form-group label-cols="12" label-cols-md="2" label="Program" label-class="font-weight-bold" label-for="program">
          <v-select id="program" 
            v-if="canEditValidasiPerencanaanTahap1"
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
          <template v-else>
            <b-form-input :value="program.find(_ => _.id == form.program_id)?.nama" disabled></b-form-input>
            <small class="text-warning">Tidak dapat mengubah data karena sudah divalidasi</small>
          </template>
        </b-form-group>
        <b-form-group label-cols="12" label-cols-md="2" label="Indikator Program" label-class="font-weight-bold" label-for="indikator">
          <template v-if="canEditValidasiPerencanaanTahap1">
            <v-select id="indikator"
              v-if="!isCustomIndikator"
              v-model="form.indikator" 
              :options="indikatorOptions" 
              placeholder="Pilih indikator program"
              :clearable="false"
            >
              <template #search="{attributes, events}">
                <input
                  class="vs__search"
                  :required="!form.indikator"
                  v-bind="attributes"
                  v-on="events"
                />
              </template>
            </v-select>
            <b-form-input v-else id="indikator" v-model="form.indikator" placeholder="Tulis indikator program" required></b-form-input>
            <b-form-checkbox v-model="isCustomIndikator" name="checkbox-indikator" :value="true" :unchecked-value="false">
              Tulis manual indikator program
            </b-form-checkbox>
          </template>
          <template v-else>
            <b-form-input :value="form.indikator" disabled></b-form-input>
            <small class="text-warning">Tidak dapat mengubah data karena sudah divalidasi</small>
          </template>
        </b-form-group>
          <b-form-group label-cols="12" label-cols-md="2" label="Indikator Program Versi Kemendagri" label-class="font-weight-bold" label-for="indikator_kemendagri">
          <b-form-input id="indikator_kemendagri" v-model="form.indikator_kemendagri"></b-form-input>
        </b-form-group>
        <b-form-group label-cols="12" label-cols-md="2" label="Pengampu" label-class="font-weight-bold" label-for="pengampu">
          <template v-if="canEditValidasiPerencanaanTahap3">
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
            >
            </v-select>

            <div v-if="form.pengampu == 'tim-kerja'">
              <v-select id="tim-kerja"
                v-model="form.tim_kerja_id"
                :options="timKerja"
                :reduce="opt => opt.id"
                label="nama" 
                placeholder="Pilih Tim Kerja"
                :filterBy="$helper.vSelectFilterBy('nama', 'ketua.peg_nama')"
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
          </template>
          <template v-else>
            <b-form-input
              :value="form.pengampu == 'tim-kerja'
                ? `${timKerja.find(_ => _.id == form.tim_kerja_id)?.nama} - ${timKerja.find(_ => _.id == form.tim_kerja_id)?.ketua?.peg_nama}`
                : vso.find(_ => _.id == form.v_struktur_organisasi_id)?.unit_kerja_nama
              "
              disabled
            ></b-form-input>
            <small class="text-warning">Tidak dapat mengubah data karena sudah divalidasi</small>
          </template>
        </b-form-group>
        <b-form-group label-cols="12" label-cols-md="2" label="Satuan" label-class="font-weight-bold" label-for="satuan">
          <v-select id="satuan"
            v-if="!isCustomSatuan"
            v-model="form.satuan" 
            :options="satuanOptions" 
            placeholder="Pilih satuan"
            :clearable="false"
          >
            <template #search="{attributes, events}">
              <input
                class="vs__search"
                :required="!form.satuan"
                v-bind="attributes"
                v-on="events"
              />
            </template>
          </v-select>
          <b-form-input v-else id="satuan" v-model="form.satuan" placeholder="Tulis satuan" required></b-form-input>
          <b-form-checkbox v-model="isCustomSatuan" name="checkbox-satuan" :value="true" :unchecked-value="false">
            Tulis manual satuan
          </b-form-checkbox>
        </b-form-group>
        <b-form-group label-cols="12" label-cols-md="2" label="Target Kinerja" label-class="font-weight-bold" label-for="target">
          <b-form-input id="target" v-model="form.target" required></b-form-input>
        </b-form-group>
        <b-form-group label-cols="12" label-cols-md="2" label="Realisasi Kinerja" label-class="font-weight-bold" label-for="realisasi">
          <b-form-input id="realisasi" v-model="form.realisasi"></b-form-input>
        </b-form-group>
        <b-form-group label-cols="12" label-cols-md="2" label="Capaian Kinerja (%)" label-class="font-weight-bold" label-for="capaian">
          <b-form-input type="number" step="0.01" id="capaian" v-model="form.capaian"></b-form-input>
        </b-form-group>
        <b-form-group v-if="form.capaian < 100" label-cols="12" label-cols-md="2" label="Penyebab Kegagalan" label-class="font-weight-bold" label-for="penyebab_kegagalan">
          <b-form-textarea id="penyebab_kegagalan" v-model="form.penyebab_kegagalan" placeholder="Tuliskan penyebab kegagalan" rows="3"></b-form-textarea>
        </b-form-group>
        <b-form-group label-cols="12" label-cols-md="2" label="Pagu Anggaran" label-class="font-weight-bold" label-for="anggaran">
          <money v-if="canEditValidasiPerencanaanTahap2" class="form-control" id="anggaran" v-model="form.anggaran" v-bind="money" required></money>
          <template v-else>
            <b-form-input :value="form.anggaran | rupiah" disabled></b-form-input>
            <small class="text-warning">Tidak dapat mengubah data karena sudah divalidasi</small>
          </template>
        </b-form-group>
        <b-form-group label-cols="12" label-cols-md="2" label="Realisasi Anggaran" label-class="font-weight-bold" label-for="realisasi_anggaran">
          <money class="form-control" id="realisasi_anggaran" v-model="form.realisasi_anggaran" v-bind="money" required disabled></money>
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
                  <b-form-input :id="`target-${month[0]}`" v-model="form.target_bulanan[month[0]]" required></b-form-input>
                </b-form-group>
              </b-col>
              <b-col xs sm="6">
                <b-form-group :label="`Realisasi Kinerja ${month[1]}`" label-class="font-weight-bold" :label-for="`realisasi-${month[0]}`">
                  <b-form-input :id="`realisasi-${month[0]}`" v-model="form.realisasi_bulanan[month[0]]"></b-form-input>
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
      <KinerjaTidakTercapai type="kinerja-program" :id="id" />
    </b-card>

    <b-card title="Diagnosa Critical Success Factor (CSF)" class="mt-2">
      <KinerjaTercapai type="kinerja-program" :id="id" />
    </b-card>

    <b-card title="Solusi dari Sasaran Kinerja Tahun Lalu:" class="mt-2">
      <SolusiKinerja type="kinerja-program" :id="id" :solusi="form.solusi" />
    </b-card>
  </div>
</template>

<script>
import axios from 'axios'
import Swal from 'sweetalert2'
import { Money } from 'v-money'
import { arrayChunk2 } from '~/plugins/utils'
import KinerjaTidakTercapai from '~/components/KinerjaTidakTercapai.vue'
import SolusiKinerja from '~/components/SolusiKinerja.vue'
import KinerjaTercapai from '~/components/KinerjaTercapai.vue'
import TambahTimKerja from '~/components/modals/TambahTimKerja'
import { mapGetters } from 'vuex'
import { moneyFormat } from '~/utils/formater'

export default {
  middleware: [
    'auth',
    'role-perangkat-daerah',
    function({ store, redirect }) {
      const canEditValidasiPerencanaan = store.getters['validasi-perencanaan/canEdit']

      if (!canEditValidasiPerencanaan) {
        return redirect('/kinerja-program')
      }
    },
  ],
  components: {
    Money,
    KinerjaTidakTercapai,
    SolusiKinerja,
    KinerjaTercapai,
    TambahTimKerja,
  },
  async asyncData({ params }) {
    const id = parseInt(params.id)
    const { data: {
      program,
      sasaranStrategisPd,
      form,
      vso,
      kinerjaBayangan,
      timKerja,
    }} = await axios.get(`kinerja-program/${id}/edit`)

    return {
      program,
      sasaranStrategisPd,
      form,
      id,
      vso,
      kinerjaBayangan,
      timKerja,
    }
  },
  created() {
    // set checkbox custom value
    this.isCustomSasaran = !this.sasaranOptions.includes(this.form.sasaran)
    this.isCustomIndikator = !this.indikatorOptions.includes(this.form.indikator)
    this.isCustomSatuan = !this.satuanOptions.includes(this.form.satuan)
  },
  data() {
    return {
      isBusy: false,
      isSasaranStrategisBusy: false,
      isCustomSasaran: false,
      isCustomIndikator: false,
      isCustomSatuan: false,
      money: moneyFormat,
    }
  },
  computed: {
    sasaranOptions: function () {
      if (!this.sasaranStrategisPd.length) return [];
      
      const formatted = this.sasaranStrategisPd.filter(el => el.sasaran_strategis_satker).map(el => el.sasaran_strategis_satker.trim())
      
      return [...new Set(formatted)]
    },
    indikatorOptions: function () {
      if (!this.sasaranStrategisPd.length) return [];
      
      const formatted = this.sasaranStrategisPd.map(el => el.iku.trim())
      
      return [...new Set(formatted)]
    },
    satuanOptions: function () {
      if (!this.sasaranStrategisPd.length) return [];
      
      const formatted = this.sasaranStrategisPd.map(el => el.satuan.trim())
      
      return [...new Set(formatted)]
    },
    realisasiAnggaran: function () {
      return Object.values(this.form.realisasi_anggaran_bulanan).reduce((acc, next) => acc + next)
    },
    capaianAnggaran: function () {
      return Math.round(((this.realisasiAnggaran / this.form.anggaran * 100) + Number.EPSILON) * 1000) / 1000
    },
    ...mapGetters({
      canEditValidasiPerencanaan: 'validasi-perencanaan/canEdit',
      canEditValidasiPerencanaanTahap1: 'validasi-perencanaan/canEditTahap1',
      canEditValidasiPerencanaanTahap2: 'validasi-perencanaan/canEditTahap2',
      canEditValidasiPerencanaanTahap3: 'validasi-perencanaan/canEditTahap3',
    }),
  },
  methods: {
    arrayChunk2,
    async update() {
      this.isBusy = true;
      if (!(await this.uploadDefinisiOperasional())) {
        return;
      }

      axios.patch(`kinerja-program/${this.id}`, this.form)
      .then(() => {
        Swal.fire({
          type: 'success',
          title: 'Berhasil menyimpan data'
        })
      }).catch(() => {

        Swal.fire({
          type: 'error',
          title: 'Gagal menyimpan data!',
        })
      }).then(() =>  {
          if(this.$route.query.from == 'cascading' ){
            this.$router.push('/display-mikro/cascading')
          }
          else{
            this.$router.push('/kinerja-program')
          }
          
        })
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
            "kinerja-program/upload-definisi-operasional",
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
      }else{
        return true;
      }
    },
  },
  watch: {
    'form.program_id': function (newVal) {
      const anggaran = this.program.find(el => el.id == newVal).anggaran
      
      this.form.anggaran = anggaran
    },
    realisasiAnggaran: function (newVal) {
      this.form.realisasi_anggaran = newVal
    },
    capaianAnggaran: function (newVal) {
      this.form.capaian_anggaran = newVal
    },
    'form.satker_iku_id': function (newVal) {
      this.form.sasaran_strategis_pd_id = newVal
    },
  },
}
</script>