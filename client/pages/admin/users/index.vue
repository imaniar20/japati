<script>
import axios from 'axios'

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
    }
  }
}
</script>

<template>
  <b-card>
    <b-table-simple :aria-busy="isBusy.getData" responsive bordered hover>
      <b-thead class="text-center align-middle" head-variant="info">
        <b-tr>
          <b-th>ID</b-th>
          <b-th>Username</b-th>
          <b-th>Nama</b-th>
          <b-th>Satuan Kerja</b-th>
          <b-th>Aksi</b-th>
        </b-tr>
      </b-thead>
      <b-tbody>
        <b-tr v-for="(user, index) of users" :key="user.id" :variant="user.is_active ? null : 'danger'">
          <b-td>{{ user.id }}</b-td>
          <b-td>{{ user.username }}</b-td>
          <b-td>{{ user.nama }}</b-td>
          <b-td>{{ user.satuan_kerja.satuan_kerja_nama }}</b-td>
          <b-td>
            <b-button v-if="user.is_active" @click="setActive(user, index, false)" variant="danger" title="Nonaktifkan" :disabled="isBusy.setActive == user.id">
              <div class="fa fa-lock"></div>
            </b-button>
            <b-button v-else variant="success" @click="setActive(user, index, true)" title="Aktifkan" :disabled="isBusy.setActive == user.id">
              <div class="fa fa-unlock"></div>
            </b-button>
          </b-td>
        </b-tr>
      </b-tbody>
    </b-table-simple>
  </b-card>
</template>