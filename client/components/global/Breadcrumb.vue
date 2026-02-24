<template>
  <div>
    <ul class="breadcrumb border bg-lightyellow">
      <!-- <li class="breadcrumb-item">
                <a id='sidebar-toggle' class="sidebar-toggle text-black" href="javascript:void(0);">
                    <i class="ti-align-left"></i>
                </a>
            </li> -->
      <li
        class="breadcrumb-item"
        :class="{ 'text-success': $route.matched[0] && !$route.matched[0].meta.breadCrumb }"
      >
        <nuxt-link to="/dashboard" class="text-muted">Dashboard</nuxt-link>
      </li>
      <template v-for="(breadcrumb, idx) in breadcrumbs">
        <li
          class="breadcrumb-item"
          :key="idx"
          v-if="idx < breadcrumbs.length - 1"
        >
          <nuxt-link :to="breadcrumb.path" class="text-muted">{{
            breadcrumb.meta.label
          }}</nuxt-link>
        </li>
        <li class="breadcrumb-item text-success" :key="idx" v-else>
          {{ breadcrumb.meta.label }}
        </li>
      </template>
    </ul>
  </div>
</template>
<style scoped>
.breadcrumb-item + .breadcrumb-item::before {
  padding-top: 5px;
}
</style>
<script>
export default {
  computed: {
    breadcrumbs() {
      let breadcrumbs = [];

      if (!this.$route.matched[0]) return breadcrumbs;

      let parent = this.$route.matched[0].meta.breadCrumbParent;

      if (this.$route.matched[0].meta.label) {
        breadcrumbs.unshift(this.$route.matched[0]);
      }

      while (parent) {
        const par = this.$router.resolve(parent);
        if (par) {
          const par2 = par.resolved;
          breadcrumbs.unshift(par2);
          parent = par2.meta.breadCrumbParent;
        } else {
          parent = null;
        }
      }
      
      return breadcrumbs;
    },
  },
};
</script>
