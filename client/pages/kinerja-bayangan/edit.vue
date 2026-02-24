<script>
import axios from 'axios'
import Swal from 'sweetalert2'

export default {
  middleware({ $role }) {
    // restrict biro, view-only
    const restrictBiro = !$role.isBiro();

    $role.hasRoles(`auth && ${restrictBiro} && (super_admin || pemerintah_daerah || setda)`)
  },
  async asyncData({ params }) {
    const id = parseInt(params.id)

    const { data: {
      form,
    }} = await axios.get(`kinerja-bayangan/${id}/edit`)

    return {
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
    async update() {
      this.isBusy = true

      try {
        await axios.patch(`/kinerja-bayangan/${this.id}`, this.form)

        Swal.fire({
          type: 'success',
          title: 'Berhasil simpan data'
        })
      } catch (error) {
        Swal.fire({
          type: 'error',
          title: 'Gagal simpan data!',
        })
      } finally {
        this.isBusy = false
      }
    },
  },
}
</script>

<template>
  <b-card>
    <form @submit.prevent="update()">
      <b-form-group label-cols="12" label-cols-md="2" label="Sasaran" label-class="font-weight-bold" label-for="sasaran">
        <b-form-input id="sasaran" v-model="form.sasaran" required></b-form-input>
      </b-form-group>
      <b-form-group label-cols="12" label-cols-md="2" label="Indikator" label-class="font-weight-bold" label-for="indikator">
        <b-form-input id="indikator" v-model="form.indikator" required></b-form-input>
      </b-form-group>

      <b-form-group label-cols="12" label-cols-md="2" label="Satuan" label-class="font-weight-bold" label-for="satuan">
        <b-form-input id="satuan" v-model="form.satuan" required></b-form-input>
      </b-form-group>
      <b-form-group label-cols="12" label-cols-md="2" label="Tahun Pertama" label-class="font-weight-bold" label-for="tahun-mulai">
        <b-form-select id="tahun-mulai" v-model="form.tahun_mulai" :options="$const.tahun_mulai_list" required></b-form-select>
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