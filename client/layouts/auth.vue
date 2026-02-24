<template>
  <div>
    <div class="page-container">
      <main class='main-content bgc-grey-100'>

        <div id='mainContent'>
          <nuxt />
        </div>
      </main>

      <footer class="bdT ta-c p-30 lh-0 fsz-sm c-grey-600">
        <span>Copyright © {{ new Date().getFullYear() }} Pemerintah Provinsi Jawa Barat</span>
      </footer>
    </div>
  </div>
</template>

<script>
import * as $ from 'jquery';

export default {
  head: {
    bodyAttrs: {
      class: 'app'
    }
  },
};
</script>
