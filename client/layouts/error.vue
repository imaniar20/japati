<script>
export default {
  name: 'NuxtError',

  layout({ route }) {
    const guestRouteName = ['welcome', 'login', 'public-display']
    let layout = 'default'

    guestRouteName.forEach(name => {
      if (route.name && route.name.includes(name)) layout = 'guest'
    });

    return layout
  },

  props: {
    error: {
      type: Object,
      default: null
    }
  },

  computed: {
    statusCode () {
      return (this.error && this.error.statusCode) || 500
    },
    message () {
      return this.error.message || 'Error'
    }
  },

  head () {
    return {
      title: this.message,
      meta: [
        {
          name: 'viewport',
          content: 'width=device-width,initial-scale=1.0,minimum-scale=1.0'
        }
      ]
    }
  }
}
</script>

<template>
<div class="container">
  <div class="text-center mt-5">
    <svg xmlns="http://www.w3.org/2000/svg" width="90" height="90" fill="#DBE1EC" viewBox="0 0 48 48">
      <path d="M22 30h4v4h-4zm0-16h4v12h-4zm1.99-10C12.94 4 4 12.95 4 24s8.94 20 19.99 20S44 35.05 44 24 35.04 4 23.99 4zM24 40c-8.84 0-16-7.16-16-16S15.16 8 24 8s16 7.16 16 16-7.16 16-16 16z" />
    </svg>

    <h3 class="mt-4">{{ message }}</h3>
    <div v-if="statusCode === 404">
      Halaman tidak ditemukan
      <NuxtLink to="/">Kembali ke halaman depan</NuxtLink>
    </div>
  </div>
</div>
</template>
