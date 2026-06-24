<script>
export default {
  name: 'AdminProgramForm',

  props: {
    form: {
      type: Object,
      required: true,
    },
    isBusy: {
      type: Boolean,
      default: false,
    },
  },
}
</script>

<template>
  <b-card>
    <form @submit.prevent="$emit('submit')">
      <OptionSatuanKerja
        v-if="$role.isSuper()"
        id="program-satuan-kerja"
        v-model="form.satuan_kerja_id"
        label-title="Satuan Kerja / OPD"
      />

      <div v-if="form.satuan_kerja_id">
        <b-row>
          <b-col cols="12" md="4">
            <b-form-group label="Tahun Kinerja" label-class="font-weight-bold" label-for="tahun_kinerja">
              <b-form-input
                id="tahun_kinerja"
                v-model.number="form.tahun_kinerja"
                type="number"
                min="1900"
                max="2100"
                required
              ></b-form-input>
            </b-form-group>
          </b-col>
          <b-col cols="12" md="4">
            <b-form-group label="Kode Program" label-class="font-weight-bold" label-for="kode">
              <b-form-input id="kode" v-model="form.kode" required></b-form-input>
            </b-form-group>
          </b-col>
          <b-col cols="12" md="4">
            <b-form-group label="Anggaran" label-class="font-weight-bold" label-for="anggaran">
              <b-form-input
                id="anggaran"
                v-model.number="form.anggaran"
                type="number"
                min="0"
                step="0.01"
                required
              ></b-form-input>
            </b-form-group>
          </b-col>
        </b-row>

        <b-form-group label="Nama Program" label-class="font-weight-bold" label-for="nama">
          <b-form-textarea id="nama" v-model="form.nama" rows="3" required></b-form-textarea>
        </b-form-group>

        <div class="text-right mt-3">
          <nuxt-link to="/admin/program" class="btn btn-outline-secondary mr-2">
            Batal
          </nuxt-link>
          <b-button variant="primary" :disabled="isBusy" type="submit">
            <i v-if="isBusy" class="fa fa-spinner fa-pulse" aria-hidden="true"></i>
            <i v-else class="fa fa-floppy-o" aria-hidden="true"></i>
            Simpan
          </b-button>
        </div>
      </div>
    </form>
  </b-card>
</template>
