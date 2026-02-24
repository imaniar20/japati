<template>
  <div class="header navbar">
    <div class="header-container">
      <ul class="nav-left">
        <li>
          <a id='sidebar-toggle' class="sidebar-toggle" href="javascript:void(0);">
            <i class="ti-menu"></i>
          </a>
        </li>
        <li>
          <a href="javascript:void(0);">
            Tahun Kinerja: {{ $helper.getTahunKinerja() }}
          </a>
        </li>
      </ul>
      <ul class="nav-right">
        <li class="dropdown">
          <a href="" class="dropdown-toggle no-after peers fxw-nw ai-c lh-1" data-toggle="dropdown">
            <div class="peer mr-sm-2 d-flex d-sm-block">
              <span class="user-label">{{ user ? user.nama || '' : '' }}</span>
              <i class="fa fa-user-circle-o" aria-hidden="true"></i>
            </div>
          </a>
          <ul class="dropdown-menu fsz-sm">
            <li>
              <a @click.prevent="logout" href="#" class="d-b td-n pY-5 bgcH-grey-100 c-grey-700">
                <i class="ti-power-off mR-10"></i>
                <span>Logout</span>
              </a>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
export default {
  computed: mapGetters({
    user: 'auth/user'
  }),
  methods: {
    async logout() {
      await this.$store.dispatch('auth/logout')
      await this.$store.dispatch('tahun-kinerja/removeTahunKinerja')
      await this.$router.push('/login')
    }
  }
}
</script>

<style scoped>
  @media (max-width:500px) {
    .user-label{
      max-width: 230px;
      overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 1; /* number of lines to show */
            line-clamp: 1;
    -webkit-box-orient: vertical;
    margin-right: 15px;
    }
  }
</style>
