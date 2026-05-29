<script>
import axios from 'axios'
import Swal from 'sweetalert2'

export default {
  middleware: ['auth', 'role-super'],

  async asyncData() {
    const [{ data: roles }, { data: satuanKerja }] = await Promise.all([
      axios.get('admin/users/roles'),
      axios.get('option/satuan-kerja'),
    ])

    return {
      roles,
      satuanKerja,
    }
  },

  data() {
    return {
      form: {
        nama: '',
        username: '',
        password: '',
        role_id: null,
        satuan_kerja_id: null,
        is_active: true,
      },
      isBusy: false,
    }
  },

  computed: {
    roleOptions() {
      return [
        { value: null, text: 'Pilih role' },
        ...this.roles.map(role => ({ value: role.id, text: role.name })),
      ]
    },
    satuanKerjaOptions() {
      return [
        { value: null, text: 'Tanpa satuan kerja' },
        ...this.satuanKerja.map(item => ({
          value: item.satuan_kerja_id,
          text: item.satuan_kerja_nama,
        })),
      ]
    },
  },

  methods: {
    async store() {
      try {
        this.isBusy = true

        await axios.post('admin/users', this.form)

        Swal.fire({
          type: 'success',
          title: 'Berhasil simpan data'
        })

        this.$router.push('/admin/users')
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
    <form @submit.prevent="store()">
      <b-form-group label-cols="12" label-cols-md="2" label="Nama" label-class="font-weight-bold" label-for="nama">
        <b-form-input id="nama" v-model="form.nama" required></b-form-input>
      </b-form-group>

      <b-form-group label-cols="12" label-cols-md="2" label="Username" label-class="font-weight-bold" label-for="username">
        <b-form-input id="username" v-model="form.username" required></b-form-input>
      </b-form-group>

      <b-form-group label-cols="12" label-cols-md="2" label="Password" label-class="font-weight-bold" label-for="password">
        <b-form-input id="password" v-model="form.password" type="password" required minlength="6"></b-form-input>
      </b-form-group>

      <b-form-group label-cols="12" label-cols-md="2" label="Role" label-class="font-weight-bold" label-for="role_id">
        <b-form-select id="role_id" v-model="form.role_id" :options="roleOptions" required></b-form-select>
      </b-form-group>

      <b-form-group label-cols="12" label-cols-md="2" label="Satuan Kerja" label-class="font-weight-bold" label-for="satuan_kerja_id">
        <b-form-select id="satuan_kerja_id" v-model="form.satuan_kerja_id" :options="satuanKerjaOptions"></b-form-select>
      </b-form-group>

      <b-form-group label-cols="12" label-cols-md="2" label="Status" label-class="font-weight-bold">
        <b-form-checkbox v-model="form.is_active" switch>
          Aktif
        </b-form-checkbox>
      </b-form-group>

      <div class="text-right mt-5">
        <nuxt-link to="/admin/users" class="btn btn-outline-secondary mr-2">
          Batal
        </nuxt-link>
        <b-button variant="primary" :disabled="isBusy" type="submit">
          <i v-if="isBusy" class="fa fa-spinner fa-pulse" aria-hidden="true"></i>
          <i v-else class="fa fa-floppy-o" aria-hidden="true"></i>
          Simpan
        </b-button>
      </div>
    </form>
  </b-card>
</template>
