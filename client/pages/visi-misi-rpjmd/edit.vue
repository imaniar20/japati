<template>
  <div>
    <b-card>
      <form @submit.prevent="update()">
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

    <b-card title="Diagnosa Critical Success Factor (CSF) Gagal" class="mt-2">
      <KinerjaTidakTercapai type="visi-misi-rpjmd" :id="id" />
    </b-card>
  </div>
</template>

<script>
import axios from 'axios'
import Swal from 'sweetalert2'
import KinerjaTidakTercapai from '~/components/KinerjaTidakTercapai.vue'

export default {
  components: {
    KinerjaTidakTercapai,
  },

  middleware({ $role }) {
    // restrict biro, view-only
    const restrictBiro = !$role.isBiro();

    $role.hasRoles(`auth && ${restrictBiro} && (super_admin || pemerintah_daerah || setda)`)
  },
  async asyncData({ params }) {
    const id = parseInt(params.id)
    const { data: {
      visi,
      form,
    }} = await axios.get(`visi-misi-rpjmd/${id}/edit`)

    return {
      visi,
      form,
      id,
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
      isBusy: false,
    }
  },
  methods: {
    update() {
      this.isBusy = true

      axios.patch(`visi-misi-rpjmd/${this.id}`, this.form)
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
      }).then(() => this.isBusy = false)
    }
  }
}
</script>

<style>

</style>