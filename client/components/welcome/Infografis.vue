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
  watch: {
    tahunKinerja() {
      this.getData();
    }
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
        <div class="banner-slide" role="button" @click="showModal(slideData)">
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
  margin: 0 0 1.2rem;
  color: rgba(255, 255, 255, 0.95);
  font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
  font-size: 1.25rem;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: 0.08em;
  line-height: 1.2;
  text-align: center;
  text-shadow: 0 2px 12px rgba(15, 64, 110, 0.2);
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
  overflow: hidden;
  border: 2px solid transparent;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.banner-slide:hover {
  border-color: #1E88E5;
  box-shadow: 0 16px 32px rgba(30, 136, 229, 0.18);
  transform: translateY(-3px);
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
  transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
}

.banner-slide:hover img {
  transform: scale(1.04);
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
