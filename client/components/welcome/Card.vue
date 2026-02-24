<script>
import EmblaCarousel from '~/components/EmblaCarousel.vue'

export default {
  name: 'WelcomeCard',

  components: {
    EmblaCarousel
  },

  data() {
    // TODO: ambil dari user jika sedang login
    const satker = 1030

    return {
      data: [
         {
          nama: 'Pohon Kinerja',
          icon: '/icons/data-2.svg',
          link: '/public-display/pohon-kinerja',
        },
        {
          nama: 'Cascading',
          icon: '/icons/data-2.svg',
          link: '/public-display/display-mikro/cascading',
        },
        {
          nama: 'Arsitektur Kinerja',
          icon: '/icons/note-2.svg',
          link: '/public-display/arsitektur-kinerja',
        },
        {
          nama: 'Kinerja makro',
          icon: '/icons/chart-square.svg',
          link: '/public-display/progres-kinerja-makro',
        },
        {
          nama: 'Kinerja IKU',
          icon: '/icons/graph.svg',
          link: '/public-display/progres-rata-capaian-iku',
        },
        {
          nama: 'Nilai AKIP',
          icon: '/icons/presention-chart.svg',
          link: '/public-display/progres-nilai-sakip-pemda',
        },
        {
          nama: 'Rencana Aksi',
          icon: '/icons/clipboard-text.svg',
          link: `/public-display/display-mikro/cascading?tahun_kinerja=2025&satuan_kerja_id=${satker}&autoShowModalType=rencana-aksi`,
        },
        {
          nama: 'Realisasi Rencana Aksi',
          icon: '/icons/task-square.svg',
          link: `/public-display/display-mikro/cascading?tahun_kinerja=2025&satuan_kerja_id=${satker}&autoShowModalType=realisasi-rencana-aksi`,
        },
        {
          nama: 'Kamus Indikator',
          icon: '/icons/book.svg',
          link: '/kamus-indikator',
        },
        {
          nama: 'Cross Cuting',
          icon: '/icons/programming-arrows.svg',
          link: `/public-display/display-mikro/cascading?tahun_kinerja=2025&satuan_kerja_id=${satker}&autoShowModalType=kinerja-kegiatan-cross-cutting&autoShowModalId=38943`,
        },
        {
          nama: 'Kinerja Eksternal',
          icon: '/icons/rotate-left.svg',
          link: `/public-display/display-mikro/cascading?tahun_kinerja=2025&satuan_kerja_id=1016&autoShowModalType=kinerja-kegiatan-eksternal&autoShowModalId=44249`,
        },
        // {
        //   nama: 'rapor kinerja',
        //   icon: '/icons/note-2.svg',
        //   link: '/rapor-kinerja/1/diagram',
        // },
        
        
    
        
        
        {
          nama: 'Progress Realisasi IKU',
          icon: '/icons/status-up.svg',
          link: '/public-display/progres-rata-kenaikan-realisasi',
        },
      ]
    }
  }
}
</script>

<template>
  <EmblaCarousel :slides-per-view="10" :spacing="0" :slides="data">
    <template #default="{ data }">
      <div class="bg-white space-y-2 rounded-xl p-20 flex flex-col" style="width: 140px; height: 100%; justify-content: space-between;">
        <div class="space-y-4">
          <div class="size-10 border border-gray-300 rounded-lg flex items-center justify-center">
            <img :src="data.icon" alt="">
          </div>
          <div class="flex flex-col">
            <h4 class="text-sm text-gray-900 font-bold leading-8 capitalize">{{ data.nama }}
            </h4>
            <!-- <p class="text-sm text-gray-700 leading-6">Lihat data {{ data.nama }}</p> -->
          </div>
        </div>
        <NuxtLink :to="data.link" class="bg-transparent border-0 flex items-center gap-2 text-sm text-blue-600 font-bold leading-6">
          Selengkapnya
          <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
              d="M12.5389 16.2631L17.7783 11.0237C17.8273 10.9676 17.8677 10.9045 17.8981 10.8365L17.943 10.7767C17.9741 10.6851 17.9893 10.5889 17.9879 10.4922C17.9949 10.4301 17.9949 10.3673 17.9879 10.3051L17.9879 10.2078C17.9605 10.1594 17.9279 10.1142 17.8906 10.0731L17.8232 9.96081L12.5838 4.7214C12.5142 4.65125 12.4314 4.59556 12.3402 4.55756C12.249 4.51956 12.1512 4.5 12.0524 4.5C11.9536 4.5 11.8557 4.51956 11.7645 4.55756C11.6733 4.59556 11.5905 4.65125 11.521 4.7214C11.4508 4.79098 11.3951 4.87377 11.3571 4.96498C11.3191 5.05619 11.2996 5.15402 11.2996 5.25283C11.2996 5.35164 11.3191 5.44947 11.3571 5.54068C11.3951 5.63189 11.4508 5.71467 11.521 5.78425L15.458 9.74375L4.14836 9.74375C3.94985 9.74375 3.75947 9.8226 3.6191 9.96297C3.47873 10.1033 3.39987 10.2937 3.39987 10.4922C3.39987 10.6907 3.47873 10.8811 3.6191 11.0215C3.75947 11.1619 3.94985 11.2407 4.14836 11.2407L15.4281 11.2407L11.4461 15.2002C11.376 15.2698 11.3203 15.3526 11.2823 15.4438C11.2443 15.535 11.2247 15.6328 11.2247 15.7316C11.2247 15.8304 11.2443 15.9283 11.2823 16.0195C11.3203 16.1107 11.376 16.1935 11.4461 16.2631C11.5161 16.3378 11.6007 16.3975 11.6947 16.4382C11.7887 16.479 11.8901 16.5 11.9925 16.5C12.0949 16.5 12.1963 16.479 12.2903 16.4382C12.3843 16.3975 12.4689 16.3378 12.5389 16.2631Z"
              fill="#1E88E5" />
          </svg>
        </NuxtLink>
      </div>
    </template>
  </EmblaCarousel>
</template>

<style scoped>
/* Main container styles */
.bg-white {
  background-color: white;
}

.w-full {
  width: 100%;
}

.space-y-2>*+* {
  margin-top: 0.5rem;
  /* space-y-2 */
}

.rounded-xl {
  border-radius: 0.75rem;
}

.p-5 {
  padding: 1.25rem;
}

/* Inner container styles */
.space-y-4>*+* {
  margin-top: 1rem;
  /* space-y-4 */
}

/* Icon container styles */
.size-14 {
  width: 3.5rem;
  height: 3.5rem;
}

.border {
  border-width: 1px;
}

.border-gray-300 {
  border-color: #d1d5db;
}

.rounded-lg {
  border-radius: 0.5rem;
}

.flex {
  display: flex;
}

.items-center {
  align-items: center;
}

.justify-center {
  justify-content: center;
}

/* Text container styles */
.flex-col {
  flex-direction: column;
}

/* Heading styles */
.text-xl {
  font-size: 1.25rem;
  line-height: 1.75rem;
}

.text-gray-900 {
  color: #111827;
}

.font-bold {
  font-weight: 700;
}

.leading-8 {
  line-height: 2rem;
}

.capitalize {
  text-transform: capitalize;
}

.whitespace-nowrap {
  white-space: nowrap;
}

/* Paragraph styles */
.text-sm {
  font-size: 0.875rem;
  line-height: 1.25rem;
}

.text-gray-700 {
  color: #374151;
}

.leading-6 {
  line-height: 1.5rem;
}

/* Button styles */
.gap-2 {
  gap: 0.5rem;
}

.text-blue-600 {
  color: #1E88E5;
}
</style>