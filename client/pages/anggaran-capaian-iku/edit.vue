<script>
import axios from 'axios'
import Swal from 'sweetalert2'
import { Money } from 'v-money'
import { moneyFormat } from '~/utils/formater'

export default {
  middleware: ['role-super'],

  components: {
    Money,
  },

  async asyncData({ params }) {
    const { data: form } = await axios.get(`anggaran-capaian-iku/${params.id}`)

    return {
      form,
    }
  },

  data() {
    return {
      isBusy: false,
      money: moneyFormat,
    }
  },

  methods: {
    async update() {
      try {
        this.isBusy = true

        await axios.patch(`anggaran-capaian-iku/${this.form.id}`, this.form)

        Swal.fire({
          type: 'success',
          title: 'Berhasil simpan data'
        })

        this.$router.push('/anggaran-capaian-iku')
      } catch (error) {
        Swal.fire({
          type: 'error',
          title: error.response?.data?.message || 'Gagal simpan data!',
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
      <div>
       <b-form-group label-cols="12" label-cols-md="2" label="Anggaran" label-class="font-weight-bold" label-for="terpakai">
          <money class="form-control" id="terpakai" v-model="form.terpakai" v-bind="money" required></money>
        </b-form-group>

        <b-form-group label-cols="12" label-cols-md="2" label="Realisasi" label-class="font-weight-bold" label-for="tidak_terpakai">
          <money class="form-control" id="tidak_terpakai" v-model="form.tidak_terpakai" v-bind="money" required></money>
        </b-form-group>

        <b-form-group label-cols="12" label-cols-md="2" label="Efisiensi" label-class="font-weight-bold" label-for="efisiensi">
          <money class="form-control" id="efisiensi" v-model="form.efisiensi" v-bind="money" required></money>
        </b-form-group>

        <div class="text-right mt-5">
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
