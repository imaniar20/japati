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
  <div class="welcome-page">
    <section class="bg-hero">
      <div class="w-full h-full flex flex-col justify-center lg:px-0 mx-auto">
        <div class="hero-copy">
          <h2 class="hero-title">SAKTI</h2>
          <h3 class="hero-subtitle">Sistem Akuntabilitas Kinerja Terintegrasi Kabupaten Kuningan</h3>
          <WelcomeInfografis  style="width: 100%; max-width: 1200px;" />
        </div>
      </div>
    </section>
    <div class="welcome-card-section">
      <div class="flex justify-center">
        <WelcomeCard />
      </div>
    </div>

    <WelcomeFooter class="mt-4" />
  </div>
</template>

<style scoped lang="scss">
.welcome-page {
  background-color: #FAFAFA;
  max-width: 1700px;
  margin: 0 auto;
}

.welcome-card-section {
  max-width: 1500px;
  margin: -64px auto 0;
  padding: 0 1rem;
  position: relative;
  z-index: 2;
}

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
  padding-top: 4.5rem;
  /* py-20 */
  padding-bottom: 5rem;
  /* py-20 */
  min-height: 660px;
  //background-size: cover;
  background-image: url('/img/pemda-kic.png');
  background-size: cover;
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
    height: auto;
    min-height: 310px;
    /* lg:h-[737px] */
    // margin-top: 5rem; /* lg:mt-20 */
    padding-top: 2.5rem;
    /* lg:py-0 */
    padding-bottom: 4.5rem;
    /* lg:py-0 */
  }
}

/* First div styles */
.bg-hero>div {
  width: 100%;
  /* w-full */
  height: 100%;
  min-height: inherit;
  /* h-full */
  display: flex;
  /* flex */
  flex-direction: column;
  justify-content: flex-start;
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

@media (max-width: 767px) {
  .bg-hero {
    min-height: 560px;
    padding-top: 3rem;
    padding-bottom: 3rem;
  }

  .welcome-card-section {
    margin-top: -32px;
    padding-top: 0;
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

.hero-copy {
  color: #ffffff;
  font-family: "Segoe UI", Arial, sans-serif;
  gap: 0.35rem;
  text-shadow: 0 2px 12px rgba(15, 64, 110, 0.28);
}

.hero-title {
  margin: 0;
  font-size: 2.35rem;
  font-weight: 800;
  line-height: 1.12;
  letter-spacing: 0;
}

.hero-subtitle {
  max-width: 760px;
  margin: 0;
  color: rgba(255, 255, 255, 0.94);
  font-size: 1.45rem;
  font-weight: 600;
  line-height: 1.35;
  letter-spacing: 0;
}

@media (max-width: 767px) {
  .hero-title {
    font-size: 1.9rem;
  }

  .hero-subtitle {
    font-size: 1.05rem;
  }
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
