<script>
import axios from 'axios'
import { destroy as doDestroy } from '~/plugins/swal'

export default {
  middleware: ['auth', 'role-super'],
  
  data() {
    return {
      users: [],
      isBusy: {
        getData: false,
        setActive: false,
      }
    }
  },

  mounted() {
    this.getData()
  },

  methods: {
    async getData() {
      this.isBusy.getData = true

      const { data } = await axios.get('admin/users')

      this.users = data
      this.isBusy.getData = false
    },
    async setActive(user, index, isActive) {
      this.isBusy.setActive = user.id

      await axios.post(isActive ? `admin/users/${user.id}/enable` : `admin/users/${user.id}/disable`)

      this.$set(this.users, index, {...this.users[index], ...{is_active: isActive}})
      this.isBusy.setActive = false
    },
    destroy(id) {
      doDestroy({
        preConfirm: async () => {
          await axios.delete(`admin/users/${id}`)
          this.getData()
          return true
        }
      })
    }
  }
}
</script>

<template>
  <b-card>
    <div class="text-right mb-2">
      <nuxt-link to="/admin/users/create" class="btn btn-primary">
        <i class="ti-plus" aria-hidden="true"></i> Tambah
      </nuxt-link>
    </div>

    <b-table-simple :aria-busy="isBusy.getData" responsive bordered hover>
      <b-thead class="text-center align-middle" head-variant="info">
        <b-tr>
          <b-th>ID</b-th>
          <b-th>Username</b-th>
          <b-th>Nama</b-th>
          <b-th>Role</b-th>
          <b-th>Satuan Kerja</b-th>
          <b-th>Status</b-th>
          <b-th>Aksi</b-th>
        </b-tr>
      </b-thead>
      <b-tbody>
        <b-tr v-for="(user, index) of users" :key="user.id" :variant="user.is_active ? null : 'danger'">
          <b-td>{{ user.id }}</b-td>
          <b-td>{{ user.username }}</b-td>
          <b-td>{{ user.nama }}</b-td>
          <b-td>{{ user.role ? user.role.name : '-' }}</b-td>
          <b-td>{{ user.satuan_kerja ? user.satuan_kerja.satuan_kerja_nama : '-' }}</b-td>
          <b-td>
            <b-badge :variant="user.is_active ? 'success' : 'danger'">
              {{ user.is_active ? 'Aktif' : 'Nonaktif' }}
            </b-badge>
          </b-td>
          <b-td>
            <div class="text-nowrap">
              <nuxt-link :to="`/admin/users/${user.id}/edit`" class="btn btn-outline-warning btn-sm m-1 rounded-circle" title="Edit">
                <i class="fa fa-pencil" aria-hidden="true"></i>
              </nuxt-link>
              <b-button v-if="user.is_active" @click="setActive(user, index, false)" variant="outline-danger" size="sm" class="m-1 rounded-circle" title="Nonaktifkan" :disabled="isBusy.setActive == user.id">
                <div class="fa fa-lock"></div>
              </b-button>
              <b-button v-else variant="outline-success" size="sm" class="m-1 rounded-circle" @click="setActive(user, index, true)" title="Aktifkan" :disabled="isBusy.setActive == user.id">
                <div class="fa fa-unlock"></div>
              </b-button>
              <b-button @click="destroy(user.id)" variant="outline-danger" size="sm" class="m-1 rounded-circle" title="Hapus">
                <i class="fa fa-trash" aria-hidden="true"></i>
              </b-button>
            </div>
          </b-td>
        </b-tr>
      </b-tbody>
    </b-table-simple>
  </b-card>
</template>
