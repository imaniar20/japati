<script>
import EmblaCarousel from "~/components/EmblaCarousel.vue";
import axios from "axios";

export default {
  name: "WelcomeInfografis",
  components: {
    EmblaCarousel,
  },
  data() {
    return {
      data: [],
      selected: null,
      isBusy: false,
    };
  },
  computed: {
    tahunKinerja() {
      return this.$store.getters['auth/check']
        ? this.$helper.getTahunKinerja()
        : this.$helper.getTahunKinerjaPublic();
    },
    tahunKinerjaLabel() {
      const option = this.$const.tahun_kinerja_list.find((tahun) => Number(tahun.key) === Number(this.tahunKinerja));

      return option ? option.display : this.tahunKinerja;
    },
  },
  methods: {
   showModal(item) {
      this.selected = item.gambar_url;
      this.$bvModal.show("bv-modal-infografis");
    },
    async getData() {
      this.isBusy = true;
      try {
        const response = await axios.get(`infografis`, {
          params: {
            tahun_kinerja: this.tahunKinerja,
          },
        });
        this.data = response.data;
      } catch (error) {
        console.error("Error fetching data:", error);
      } finally {
        this.isBusy = false;
      }
    },
  },
  mounted() {
    this.getData();
  },
};
</script>
<template>
  <section class="space-y-1">
    <h4 class="report-title">Laporan Kinerja Tahun {{ tahunKinerjaLabel }}</h4>
    <EmblaCarousel v-if="data.length" :slides-per-view="4" :spacing="24" :slides="data">
      <template #default="{ data: slideData }">
        <div
          class="banner-slide"
          role="button"
          @click="showModal(slideData)"
        >
          <img :src="slideData.gambar_url" :alt="slideData.judul || ''" />
        </div>
      </template>
    </EmblaCarousel>
     <b-modal id="bv-modal-infografis" size="xl" hide-header hide-footer>
      <div>
        <img :src="selected" class="w-full h-full" alt="" />
      </div>
    </b-modal>
  </section>
</template>

<style scoped>
/* Section styles */
section {
  /* space-y-2 */
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.report-title {
  margin: 0 0 1rem;
  color: rgba(255, 255, 255, 0.96);
  font-family: "Segoe UI", Arial, sans-serif;
  font-size: 1.45rem;
  font-weight: 700;
  line-height: 1.25;
  letter-spacing: 0;
  text-align: center;
  text-shadow: 0 2px 10px rgba(15, 64, 110, 0.25);
}

/* Heading styles */
h2 {
  /* text-4xl */
  font-size: 2.25rem;
  /* 36px */
  line-height: 2.5rem;
  /* 40px */

  /* text-gray-900 */
  color: #111827;

  /* font-medium */
  font-weight: 500;

  /* text-center */
  text-align: center;
}

/* Container styles */
.bg-white {
  background-color: #ffffff;
}

.w-full {
  width: 100%;
}

.rounded-lg {
  border-radius: 0.5rem;
  /* 8px */
}

.p-2 {
  padding: 0.5rem;
  /* 8px */
}

.banner-slide {
  width: 100%;
  height: 400px;
  padding: 0.5rem;
  border-radius: 8px;
  background: #ffffff;
  box-shadow: 0 12px 24px rgba(20, 80, 120, 0.12);
  cursor: pointer;
}

/* Image styles */
img {
  /* w-full */
  width: 100%;

  /* h-full */
  height: 100%;

  /* Ensure image fits container */
  object-fit: contain;
  display: block;
}

.banner-slide img {
  border-radius: 4px;
}

@media (max-width: 1023px) {
  .report-title {
    font-size: 1.2rem;
  }

  .banner-slide {
    height: 320px;
  }
}

@media (max-width: 575px) {
  .report-title {
    font-size: 1rem;
  }

  .banner-slide {
    height: 240px;
  }
}
</style>
