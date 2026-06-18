const webpack = require('webpack')

module.exports = {
  ssr: false, // Comment this for SSR

  srcDir: __dirname,

  env: {
    apiUrl: process.env.API_URL || process.env.APP_URL + '/api',
    baseRoute: process.env.BASE_ROUTE || "/",
    tahunKinerjaKey: process.env.TAHUN_KINERJA_KEY,
    tahunKinerjaPublicKey: process.env.TAHUN_KINERJA_PUBLIC_KEY,
  },

  head: {
    title: process.env.APP_NAME,
    titleTemplate: '%s - ' + process.env.APP_NAME,
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      { hid: 'description', name: 'description', content: 'E-Sakip - Pemerintah Kabupaten Kuningan' },
      { name: 'google', content: 'notranslate' },
    ],
    script: [
      // Nonaktif sementara widget Hotline Layanan Administrasi Pemerintahan.
      // Aktifkan lagi jika dibutuhkan:
      // { src: 'https://storage.googleapis.com/hotline-jabar-assets/hotline-adpem-widget.js'},
    ],
    link: [
      { rel: 'icon', type: 'image/x-icon', href: `${process.env.BASE_ROUTE || '/'}favicon.ico` },
      // Nonaktif sementara widget Hotline Layanan Administrasi Pemerintahan.
      // Aktifkan lagi jika dibutuhkan:
      // { rel: 'stylesheet', href: 'https://storage.googleapis.com/hotline-jabar-assets/hotline-adpem-widget.js' },
    ],
    bodyAttrs: {
      class: 'notranslate',
    },
  },

  loading: { color: '#ffc107' },

  router: {
    base: process.env.BASE_ROUTE || "/",
    middleware: ['check-auth']
  },

  css: [
    { src: '~assets/sass/app.scss', lang: 'scss' },
    { src: '~assets/adminator/styles/index.scss', lang: 'scss' },
  ],
  plugins: [
    '~components/global',
    '@/plugins/constants.js',
    '@/plugins/helpers.js',
    '~plugins/axios',
    '~plugins/nuxt-client-init', // Comment this for SSR
    "~plugins/vue-select",
    "~plugins/filters",
    "~plugins/panZoom",
    '@/plugins/vue-apexcharts.js',
    '@/plugins/role.js',
     '@/plugins/bootstrap-vue.js',
    { src: '~plugins/bootstrap', mode: 'client' }
  ],

  modules: [
    '@nuxtjs/router',
    'bootstrap-vue/nuxt',
  ],

  build: {
    extractCSS: true,
    plugins: [
      new webpack.ProvidePlugin({
        $: 'jquery',
        jQuery: 'jquery',
        'window.jQuery': 'jquery'
      })
    ],
    loaders: {
      vue: {
        compiler: require('vue-template-babel-compiler')
      },
      css: {
				modules: false, // set ke false karena bug HMR saat pakai tag style di komponen
			},
    },
  },
}
