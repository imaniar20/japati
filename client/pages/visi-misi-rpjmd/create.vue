<template>
  <b-card>
    <form @submit.prevent="store()">
      <OptionSatuanKerja v-if="$role.isSuper()" v-model="form.satuan_kerja_id" />
      <b-form-group label-class="font-weight-bold pt-0" label-cols="12" label-cols-md="2" label="Visi" label-for="visi">
        <b-form-input :value="visi.visi" plaintext></b-form-input>
      </b-form-group>
      <OptionMisi v-model="form.misi_id" />
      <OptionTujuan v-model="form.tujuan_id" />
      <OptionIndikatorTujuan v-model="form.indikator_tujuan_id" />
      <b-form-group label-class="font-weight-bold pt-0" label-cols="12" label-cols-md="2" label="Satuan" label-for="satuan">
        <b-form-input id="satuan" v-model="form.satuan" required></b-form-input>
      </b-form-group>

      <b-row class="mt-4">
        <b-col sm md="4">
          <b-form-group :label="`Target kinerja tahun baseline ${form.tahun_mulai - 1}`" label-class="font-weight-bold"  label-for="target_baseline">
            <b-form-input id="target_baseline" v-model="form.target_baseline" required></b-form-input>
          </b-form-group>
        </b-col>
        <b-col sm md="4">
          <b-form-group :label="`Realisasi kinerja tahun baseline ${form.tahun_mulai - 1}`" label-class="font-weight-bold"  label-for="realisasi_baseline">
            <b-form-input id="realisasi_baseline" v-model="form.realisasi_baseline"></b-form-input>
          </b-form-group>
        </b-col>
        <b-col sm md="4">
          <b-form-group :label="`Capaian kinerja tahun baseline ${form.tahun_mulai - 1} (%)`" label-class="font-weight-bold"  label-for="capaian_baseline">
            <b-form-input type="number" step="0.01" id="capaian_baseline" v-model="form.capaian_baseline"></b-form-input>
          </b-form-group>
        </b-col>
      </b-row>

      <b-row v-for="(kinerja, index) of kinerjaTahunList" :key="index">
        <b-col sm md="4">
          <b-form-group :label="`Target kinerja tahun ${form.tahun_mulai + index}`" label-class="font-weight-bold" :label-for="kinerja.target">
            <b-form-input :id="kinerja.target" v-model="form[kinerja.target]" required></b-form-input>
          </b-form-group>
        </b-col>
        <b-col sm md="4">
          <b-form-group :label="`Realisasi kinerja tahun ${form.tahun_mulai + index}`" label-class="font-weight-bold" :label-for="kinerja.realisasi">
            <b-form-input :id="kinerja.realisasi" v-model="form[kinerja.realisasi]"></b-form-input>
          </b-form-group>
        </b-col>
        <b-col sm md="4">
          <b-form-group :label="`Capaian kinerja tahun ${form.tahun_mulai + index} (%)`" label-class="font-weight-bold" :label-for="kinerja.capaian">
            <b-form-input type="number" step="0.01" :id="kinerja.capaian" v-model="form[kinerja.capaian]"></b-form-input>
          </b-form-group>
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
  </b-card>
</template>

<script>
import axios from 'axios'
import Swal from 'sweetalert2'
import { mapGetters } from 'vuex'

export default {
  middleware({ $role }) {
    // restrict biro, view-only
    const restrictBiro = !$role.isBiro();

    $role.hasRoles(`auth && ${restrictBiro} && (super_admin || pemerintah_daerah || setda)`)
  },
  async asyncData() {
    const { data: {
      visi,
    }} = await axios.get('visi-misi-rpjmd/create')

    return {
      visi,
    }
  },
  data() {
    let kinerjaTahunList = []

    for (let index = 1; index <= 5; index++) {
      kinerjaTahunList.push({
        target: `target_${index}`,
        realisasi: `realisasi_${index}`,
        capaian: `capaian_${index}`,
      })
    }

    return {
      kinerjaTahunList,
      form: {
        satuan_kerja_id: null,
        visi_id: null,
        misi_id: null,
        tujuan_id: null,
        indikator_tujuan_id: null,
        satuan: null,
        tahun_mulai: this.$helper.getTahunMulai(),
        target_baseline: null,
        target_1: null,
        target_2: null,
        target_3: null,
        target_4: null,
        target_5: null,
        realisasi_baseline: null,
        realisasi_1: null,
        realisasi_2: null,
        realisasi_3: null,
        realisasi_4: null,
        realisasi_5: null,
        capaian_baseline: null,
        capaian_1: null,
        capaian_2: null,
        capaian_3: null,
        capaian_4: null,
        capaian_5: null,
      },
      isBusy: false,
    }
  },
  created() {
    /** set default visi */
    this.form.visi_id = this.visi.id

    /** set default satuan kerja */
    this.form.satuan_kerja_id = this.user.satuan_kerja_id
  },
  computed: {
    ...mapGetters({
      user: 'auth/user'
    }),
  },
  methods: {
    store() {
      this.isBusy = true

      axios.post('visi-misi-rpjmd', this.form)
      .then(() => {
        Swal.fire({
          type: 'success',
          title: 'Berhasil simpan data'
        }).then(() => this.$router.push('/visi-misi-rpjmd'))
      }).catch(() => {

        Swal.fire({
          type: 'error',
          title: 'Gagal simpan data!',
        })
      }).then(() => this.isBusy = false)
    }
  }
}
</script>

<style>

</style>