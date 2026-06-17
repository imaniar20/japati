<script>
import WelcomeCard from '~/components/welcome/Card.vue'
import WelcomeInfografis from '~/components/welcome/Infografis.vue'
import WelcomeFooter from '~/components/welcome/Footer.vue'

export default {
  layout: 'guest',

  components: {
    WelcomeCard,
    WelcomeInfografis,
    WelcomeFooter,
  },
}
</script>

<template>
  <div style="background-color: #FAFAFA; max-width: 1700px;  margin: 0 auto;">
    <section class="bg-hero">
      <div class="w-full h-full flex flex-col justify-center lg:px-0 mx-auto">
        <div class="flex flex-col items-center text-center">
          <h2 class="text-white text-sm font-black mt-5">SAKTI</h2>
          <h3 class="text-white">Sistem Akutanbilitas Kinerja Terintegrasi Kabupaten Kuningan</h3>
          <WelcomeInfografis  style="width: 100%; max-width: 1200px;" />
        </div>
      </div>
    </section>
    <div style="max-width: 1500px; margin: auto;">
      <div class="flex justify-center">
        <WelcomeCard />
      </div>
    </div>

    <WelcomeFooter class="mt-4" />
  </div>
</template>

<style scoped lang="scss">
/* Section styles */
.bg-hero {
  //background-color: #1E88E5;
  /* bg-blue-600 */
  width: 100%;
  /* w-full */
  // max-width: 1700px;
  margin-left: auto;
  /* mx-auto */
  margin-right: auto;
  /* mx-auto */
  padding-top: 5rem;
  /* py-20 */
  padding-bottom: 5rem;
  /* py-20 */
  //background-size: cover;
  background-image: url('/img/pemda-kic.png');
  overflow: hidden;
  /* bg-cover */
  background-repeat: no-repeat;
  /* bg-no-repeat */
  background-position: center bottom;
  /* bg-[center_bottom] */
}

/* Responsive section styles */
@media (min-width: 1024px) {
  .bg-hero {
    background-color: #2488e1;
    /* lg:bg-white */
    // background-image: url('/img/japati-background-home.png');
    /* lg:bg-[url('/images/background-hero.png')] */
    height: 470px;
    /* lg:h-[737px] */
    // margin-top: 5rem; /* lg:mt-20 */
    padding-top: 5rem;
    /* lg:py-0 */
    padding-bottom: 0;
    /* lg:py-0 */
  }
}

/* First div styles */
.bg-hero>div {
  width: 100%;
  /* w-full */
  height: 100%;
  /* h-full */
  display: flex;
  /* flex */
  flex-direction: column;
  /* flex-col */
  gap: 0.5rem;
  /* gap-2 */
  padding-left: 1rem;
  /* px-4 */
  padding-right: 1rem;
  /* px-4 */
  margin-left: auto;
  /* mx-auto */
  margin-right: auto;
  /* mx-auto */
  max-width: 80rem;
}

/* Responsive first div styles */
@media (min-width: 1024px) {
  .bg-hero>div {
    max-width: 80rem;
    /* lg:max-w-7xl */
    padding-left: 0;
    /* lg:px-0 */
    padding-right: 0;
    /* lg:px-0 */
  }
}

/* Second div styles */
.bg-hero>div>div {
  display: flex;
  /* flex */
  flex-direction: column;
  /* flex-col */
  align-items: center;
  /* items-center */
  text-align: center;
  /* text-center */
}

/* h1 styles */
h1 {
  color: white;
  /* text-white */
  font-size: 87px;
  /* text-[87px] */
  font-weight: 900;
  /* font-black */
  line-height: normal;
  /* leading-normal */
}

/* h4 styles */
h4 {
  color: white;
  /* text-white */
  font-size: 1.25rem;
  /* text-xl */
  line-height: 1.75rem;
  /* leading-7 */
}

/* Responsive h4 styles */
@media (min-width: 1024px) {
  h4 {
    width: 24rem;
    /* lg:w-96 */
  }
}
</style>