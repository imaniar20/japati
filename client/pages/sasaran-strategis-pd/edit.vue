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
        <b-form-group label-cols="12" label-cols-md="2" label="Relasi Sasaran Strategis RPJMD & IKU Bupati" label-class="font-weight-bold" label-for="sasaran_strategis_rpjmd">
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
                <div>Sasaran strategis: <b>{{ opt.sasaran_strategis?.sasaran }}</b></div>
                <div>IKU Gubernur: <b>{{ opt.indikator_sasaran_strategis?.indikator }}</b></div>
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
        
        <OptionSasaranStrategis v-model="form.sasaran_strategis_id" :ids="sasaranStrategisRpjmd.map(el => el.sasaran_strategis_id)" />
        <OptionIndikatorSasaranStrategis v-model="form.indikator_sasaran_strategis_id" :ids="sasaranStrategisRpjmd.map(el => el.indikator_sasaran_strategis_id)" />

        <b-form-group label-class="font-weight-bold pt-0" label-cols="12" label-cols-md="2" label="Kinerja Cross-Cutting" label-for="kinerja-bayangan">
          <v-select id="kinerja-bayangan" 
            v-model="form.kinerja_bayangan_id" 
            :options="kinerjaBayangan" 
            :reduce="opt => opt.id" 
            :filterBy="$helper.vSelectFilterBy('sasaran', 'indikator')"
            placeholder="Pilih Kinerja Cross-Cutting"
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
        
        <b-form-group label-cols="12" label-cols-md="2" label="Sasaran Strategis PD" label-class="font-weight-bold" label-for="sasaran-strategis-satker">
          <b-form-input id="sasaran-strategis-satker" v-model="form.sasaran_strategis_satker"></b-form-input>
        </b-form-group>
        <b-form-group label-cols="12" label-cols-md="2" label="IKU PD" label-class="font-weight-bold" label-for="iku">
          <b-form-input id="iku" v-model="form.iku" required></b-form-input>
        </b-form-group>
        <b-form-group label-cols="12" label-cols-md="2" label="Satuan" label-class="font-weight-bold" label-for="satuan">
          <b-form-input id="satuan" v-model="form.satuan" required></b-form-input>
        </b-form-group>
        <b-form-group label-cols="12" label-cols-md="2" label="Why (Penjelasan Kenapa Sasaran Startegis PD ditetapkan)" label-class="font-weight-bold" label-for="why">
          <b-form-input id="why" v-model="form.why"></b-form-input>
        </b-form-group>
        <b-form-group label-cols="12" label-cols-md="2" label="Apakah masuk rapor kinerja?" label-class="font-weight-bold" label-for="is_rapor_kinerja">
          <b-form-radio-group
            id="is_rapor_kinerja"
            v-model="form.is_rapor_kinerja"
            name="is_rapor_kinerja"
            class="mb-2"
          >
            <b-form-radio :value="true">Iya</b-form-radio>
            <b-form-radio :value="false">Tidak</b-form-radio>
          </b-form-radio-group>
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


        <b-card class="my-3">
          <b-row>
            <b-col sm md="3">
              <b-form-group :label="`Target kinerja baseline ${form.tahun_mulai - 1}`" label-class="font-weight-bold"  label-for="target_baseline">
                <b-form-input type="number" step="0.00001" id="target_baseline" v-model="form.target_baseline" required></b-form-input>
              </b-form-group>
            </b-col>
            <b-col sm md="3">
              <b-form-group :label="`Realisasi kinerja baseline ${form.tahun_mulai - 1}`" label-class="font-weight-bold"  label-for="realisasi_baseline">
                <b-form-input type="number" step="0.00001" id="realisasi_baseline" v-model="form.realisasi_baseline"></b-form-input>
              </b-form-group>
            </b-col>
            <b-col sm md="3">
              <b-form-group :label="`Capaian kinerja baseline ${form.tahun_mulai - 1} (%)`" label-class="font-weight-bold"  label-for="capaian_baseline">
                <b-form-input type="number" step="0.01" id="capaian_baseline" v-model="form.capaian_baseline"></b-form-input>
              </b-form-group>
            </b-col>
            <b-col sm md="3">
              <b-form-group v-if="form.capaian_baseline < 100" :label="`Penyebab kegagalan baseline ${form.tahun_mulai - 1}`" label-class="font-weight-bold" label-for="penyebab_kegagalan_baseline">
                <b-form-textarea id="penyebab_kegagalan_baseline" v-model="form.penyebab_kegagalan_baseline" placeholder="Tuliskan penyebab kegagalan" rows="3"></b-form-textarea>
              </b-form-group>
            </b-col>
          </b-row>
          <b-button v-b-toggle:kinerja-baseline-triwulan variant="success">
            <span class="when-open">Sembunyikan</span><span class="when-closed">Tampilkan</span> kinerja triwulan baseline {{ form.tahun_mulai - 1 }}
          </b-button>
          <b-collapse id="kinerja-baseline-triwulan">
            <hr>
            <b-row v-for="tw in 4" :key="tw" class="mt-4">
              <b-col sm md="3">
                <b-form-group :label="`Target kinerja baseline ${form.tahun_mulai - 1} TW ${tw}`" label-class="font-weight-bold"  :label-for="`target_baseline_${tw}`">
                  <b-form-input type="number" step="0.00001" :id="`target_baseline_${tw}`" v-model="form.target_baseline_triwulan[tw]"></b-form-input>
                </b-form-group>
              </b-col>
              <b-col sm md="3">
                <b-form-group :label="`Realisasi kinerja baseline ${form.tahun_mulai - 1} TW ${tw}`" label-class="font-weight-bold"  :label-for="`realisasi_baseline_${tw}`">
                  <b-form-input type="number" step="0.00001" :id="`realisasi_baseline_${tw}`" v-model="form.realisasi_baseline_triwulan[tw]"></b-form-input>
                </b-form-group>
              </b-col>
              <b-col sm md="3">
                <b-form-group :label="`Capaian kinerja baseline ${form.tahun_mulai - 1} TW ${tw} (%)`" label-class="font-weight-bold"  :label-for="`capaian_baseline_${tw}`">
                  <b-form-input type="number" step="0.01" :id="`capaian_baseline_${tw}`" v-model="form.capaian_baseline_triwulan[tw]"></b-form-input>
                </b-form-group>
              </b-col>
            </b-row>
          </b-collapse>
        </b-card>

        <b-card v-for="(kinerja, index) of kinerjaTahunList" :key="index" class="mb-3">
          <b-row>
            <b-col sm md="3">
              <b-form-group :label="`Target kinerja tahun ${form.tahun_mulai + index}`" label-class="font-weight-bold" :label-for="kinerja.target">
                <b-form-input type="number" step="0.00001" :id="kinerja.target" v-model="form[kinerja.target]" required></b-form-input>
              </b-form-group>
            </b-col>
            <b-col sm md="3">
              <b-form-group :label="`Realisasi kinerja tahun ${form.tahun_mulai + index}`" label-class="font-weight-bold" :label-for="kinerja.realisasi">
                <b-form-input type="number" step="0.00001" :id="kinerja.realisasi" v-model="form[kinerja.realisasi]"></b-form-input>
              </b-form-group>
            </b-col>
            <b-col sm md="3">
              <b-form-group :label="`Capaian kinerja tahun ${form.tahun_mulai + index} (%)`" label-class="font-weight-bold" :label-for="kinerja.capaian">
                <b-form-input type="number" step="0.01" :id="kinerja.capaian" v-model="form[kinerja.capaian]"></b-form-input>
              </b-form-group>
            </b-col>
            <b-col sm md="3">
              <b-form-group v-if="form[kinerja.capaian] < 100" :label="`Penyebab kegagalan tahun ${form.tahun_mulai + index}`" label-class="font-weight-bold" :label-for="kinerja.penyebab_kegagalan">
                <b-form-textarea :id="kinerja.penyebab_kegagalan" v-model="form[kinerja.penyebab_kegagalan]" placeholder="Tuliskan penyebab kegagalan" rows="3"></b-form-textarea>
              </b-form-group>
            </b-col>
          </b-row>
          <b-button v-b-toggle="`kinerja-${index}-triwulan`" variant="success">
            <span class="when-open">Sembunyikan</span><span class="when-closed">Tampilkan</span> kinerja triwulan {{ form.tahun_mulai + index }}
          </b-button>
          <b-collapse :id="`kinerja-${index}-triwulan`">
            <hr>
            <b-row v-for="tw in 4" :key="tw" class="mt-4">
              <b-col sm md="3">
                <b-form-group :label="`Target kinerja tahun ${form.tahun_mulai + index} TW ${tw}`" label-class="font-weight-bold"  :label-for="`${kinerja.target_triwulan}_${tw}`">
                  <b-form-input type="number" step="0.00001" :id="`${kinerja.target_triwulan}_${tw}`" v-model="form[kinerja.target_triwulan][tw]"></b-form-input>
                </b-form-group>
              </b-col>
              <b-col sm md="3">
                <b-form-group :label="`Realisasi kinerja tahun ${form.tahun_mulai + index} TW ${tw}`" label-class="font-weight-bold"  :label-for="`${kinerja.realisasi_triwulan}_${tw}`">
                  <b-form-input type="number" step="0.00001" :id="`${kinerja.realisasi_triwulan}_${tw}`" v-model="form[kinerja.realisasi_triwulan][tw]"></b-form-input>
                </b-form-group>
              </b-col>
              <b-col sm md="3">
                <b-form-group :label="`Capaian kinerja tahun ${form.tahun_mulai + index} TW ${tw} (%)`" label-class="font-weight-bold"  :label-for="`${kinerja.capaian_triwulan}_${tw}`">
                  <b-form-input type="number" step="0.01" :id="`${kinerja.capaian_triwulan}_${tw}`" v-model="form[kinerja.capaian_triwulan][tw]"></b-form-input>
                </b-form-group>
              </b-col>
            </b-row>
          </b-collapse>
        </b-card>

        <b-form-group label-cols="12" label-cols-md="2" label="Rata-Rata Realisasi Nasional" label-class="font-weight-bold" label-for="rata_nasional">
          <b-form-input id="rata_nasional" type="number" min="0" step="0.00001" v-model="form.rata_nasional"></b-form-input>
        </b-form-group>
        <b-form-group label-cols="12" label-cols-md="2" label="Peringkat di Nasional" label-class="font-weight-bold" label-for="peringkat_nasional">
          <b-form-input id="peringkat_nasional" type="number" min="0" v-model="form.peringkat_nasional"></b-form-input>
        </b-form-group>
        <b-form-group label-cols="12" label-cols-md="2" label="Rata-Rata Realisasi Internasional" label-class="font-weight-bold" label-for="rata_internasional">
          <b-form-input id="rata_internasional" type="number" min="0" step="0.00001" v-model="form.rata_internasional"></b-form-input>
        </b-form-group>
        <b-form-group label-cols="12" label-cols-md="2" label="Peringkat di Internasional" label-class="font-weight-bold" label-for="peringkat_internasional">
          <b-form-input id="peringkat_internasional" type="number" min="0" v-model="form.peringkat_internasional"></b-form-input>
        </b-form-group>
        <b-form-group label-cols="12" label-cols-md="2" label="Narasi Keberhasilan/Narasi Ketidakberhasilan" label-class="font-weight-bold" label-for="narasi_keberhasilan">
          <b-form-textarea id="narasi_keberhasilan" v-model="form.narasi_keberhasilan" rows="3"></b-form-textarea>
        </b-form-group>
        <h5 class="mb-3">Penghargaan Tahun Berjalan</h5>
        <b-form-group label-cols="12" label-cols-md="2" label="Redaksi" label-class="font-weight-bold" label-for="redaksi">
          <b-form-textarea id="redaksi" v-model="form.redaksi" rows="3"></b-form-textarea>
        </b-form-group>

        <b-form-group label-cols="12" label-cols-md="2" label="Lampiran" label-class="font-weight-bold" label-for="lampiran">
          <b-form-file ref="lampiran" placeholder="Pilih foto" @change="uploadLampiran($event)" accept=".jpg, .jpeg, .png" :disabled="isLampiranBusy"></b-form-file>
          <small v-if="isLampiranBusy" class="text-primary">Mengunggah berkas...</small>
          <small v-else>Maksimal ukuran foto 2MB</small>
          
          <div class="mt-4">
            <b-row v-for="(images, index) of arrayChunk2(form.lampiran, 6)" :key="index">
              <b-col v-for="(image, indexImage) of images" :key="indexImage" cols="6" sm="4" md="2" class="text-center mb-3">
                <b-card :img-src="image" :img-alt="image.substring(image.lastIndexOf('/') + 1)" img-top>
                  <div class="d-flex justify-content-center">
                    <a :href="image" class="btn btn-sm btn-success" target="_blank" title="Lihat"><i class="fa fa-eye" aria-hidden="true"></i></a>
                    <b-btn @click="removeLampiran(indexImage)" size="sm" variant="danger" class="mx-1" title="Hapus"><i class="fa fa-trash" aria-hidden="true"></i></b-btn>
                  </div>
                </b-card>
              </b-col>
            </b-row>
          </div>
        </b-form-group>

        <div class="text-right mt-5">
          <b-button variant="primary" :disabled="isBusy" type="submit">
            <i v-if="isBusy" class="fa fa-spinner fa-pulse" aria-hidden="true"></i>
            <i v-else class="fa fa-floppy-o" aria-hidden="true"></i>
            Simpan
          </b-button>
        </div>
      </form>
    </b-card>

    <b-card title="Diagnosa Critical Success Factor (CSF) Gagal" class="mt-2">
      <KinerjaTidakTercapai type="sasaran-strategis-pd" :id="id" />
    </b-card>
  </div>
</template>

<script>
import axios from 'axios'
import { mapGetters } from 'vuex'
import Swal from 'sweetalert2'
import { arrayChunk2 } from '~/plugins/utils'
import KinerjaTidakTercapai from '~/components/KinerjaTidakTercapai.vue'

export default {
  components: {
    KinerjaTidakTercapai,
  },

  middleware({ $role }) {
    // restrict biro, view-only
    const restrictBiro = !$role.isBiro();

    $role.hasRoles(`auth && ${restrictBiro} && (super_admin || perangkat_daerah || setda)`)
  },
  async asyncData({ params }) {
    const id = parseInt(params.id)
    const { data: {
      form,
      sasaranStrategisRpjmd,
      kinerjaBayangan,
    }} = await axios.get(`sasaran-strategis-pd/${id}/edit`)

    return {
      form,
      id,
      sasaranStrategisRpjmd,
      kinerjaBayangan,
    }
  },
  data() {
    let kinerjaTahunList = []

    for (let index = 1; index <= 5; index++) {
      kinerjaTahunList.push({
        target: `target_${index}`,
        realisasi: `realisasi_${index}`,
        capaian: `capaian_${index}`,
        penyebab_kegagalan: `penyebab_kegagalan_${index}`,
        target_triwulan: `target_${index}_triwulan`,
        realisasi_triwulan: `realisasi_${index}_triwulan`,
        capaian_triwulan: `capaian_${index}_triwulan`,
      })
    }

    return {
      kinerjaTahunList,
      isBusy: false,
      isSasaranBusy: false,
      isLampiranBusy: false,
      isSyncBusy: false,
    }
  },
  computed: {
    renderedMath() {
      let rumus = this.form.do_rumus == null ? "" :this.form.do_rumus;
      return this.$katex.renderToString(rumus, {
        throwOnError: false,
      });
    },
    ...mapGetters({
      user: 'auth/user'
    }),
    capaianBaseline: function () {
      return Math.round(((this.form.realisasi_baseline / this.form.target_baseline * 100) + Number.EPSILON) * 100) / 100
    },
    capaian1: function () {
      return Math.round(((this.form.realisasi_1 / this.form.target_1 * 100) + Number.EPSILON) * 100) / 100
    },
    capaian2: function () {
      return Math.round(((this.form.realisasi_2 / this.form.target_2 * 100) + Number.EPSILON) * 100) / 100
    },
    capaian3: function () {
      return Math.round(((this.form.realisasi_3 / this.form.target_3 * 100) + Number.EPSILON) * 100) / 100
    },
    capaian4: function () {
      return Math.round(((this.form.realisasi_4 / this.form.target_4 * 100) + Number.EPSILON) * 100) / 100
    },
    capaian5: function () {
      return Math.round(((this.form.realisasi_5 / this.form.target_5 * 100) + Number.EPSILON) * 100) / 100
    },
  },
  methods: {
    arrayChunk2,
    async update() {
      this.isBusy = true

      this.isBusy = true;
      if (!(await this.uploadDefinisiOperasional())) {
          return;
        }

      axios.patch(`sasaran-strategis-pd/${this.id}`, this.form)
      .then(() => {
        Swal.fire({
          type: 'success',
          title: 'Berhasil simpan data'
        })
      }).catch(() => {

        Swal.fire({
          type: 'error',
          title: 'Gagal simpan data!',
        })
      }).then(() => this.isBusy = false)
    },
    uploadLampiran(e) {
      const file = e.target.files[0]

      if (!file) {
        return alert('Pilih berkas terlebih dahulu!')
      }

      const fileSize = file.size / 1000

      if (fileSize > 2048) {
        alert('Ukuran berkas terlalu besar. Ukuran maksimal adalah 2MB!')

        return
      }

      this.isLampiranBusy = true
      const formData = new FormData();
      formData.append('file', e.target.files[0])

      axios.post('sasaran-strategis-pd/upload', formData, {
          headers: {
            'Content-Type': 'multipart/form-data',
          },
        }).then(({ data }) => {
          this.form.lampiran.push(data)
        }).catch(() => {
          Swal.fire({
            title: 'Gagal upload berkas',
            type: 'error'
          })
        }).then(() => {
          this.isLampiranBusy = false
          this.$refs.lampiran.reset()
        })

    },
    removeLampiran(index) {
      const confirmed = confirm('Apakah Anda yakin akan menghapus berkas ini?')

      if (!confirmed) return false;

      this.form.lampiran.splice(index, 1)
    },
    syncTrk() {
      this.isSyncBusy = true

      axios.post(`sasaran-strategis-pd/${this.id}/sync`)
        .then(({ data }) => {
          this.form[data.realisasi_field] = data.realisasi
          this.form[data.capaian_field] = data.capaian

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
      this.timKerja.push(data);
      this.form.tim_kerja_id = data.id;
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
        const file = this.$refs[`definisi-operasional-img`].files[0];

        const formData = new FormData();
        formData.append("file", file);

        const { data } = await axios.post(
          "sasaran-strategis-pd/upload-definisi-operasional",
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
    capaianBaseline: function (newVal) {
      this.form.capaian_baseline = newVal
    },
    capaian1: function (newVal) {
      this.form.capaian_1 = newVal
    },
    capaian2: function (newVal) {
      this.form.capaian_2 = newVal
    },
    capaian3: function (newVal) {
      this.form.capaian_3 = newVal
    },
    capaian4: function (newVal) {
      this.form.capaian_4 = newVal
    },
    capaian5: function (newVal) {
      this.form.capaian_5 = newVal
    },
  }
}
</script>

<style scoped
.not-collapsed > .when-open {
  display: inherit
}

.collapsed > .when-open,
.not-collapsed > .when-closed {
  display: none;
}
</style>