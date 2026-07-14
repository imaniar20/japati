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
  <div class="services-container">
    <div class="services-header text-center">
      <h3 class="services-section-title">Menu Utama Public Display</h3>
      <p class="services-section-subtitle">Silakan pilih menu di bawah ini untuk mengakses visualisasi data akuntabilitas kinerja terintegrasi.</p>
    </div>
    
    <div class="grid-services">
      <div v-for="(item, index) in data" :key="index" class="service-card">
        <div class="service-top">
          <div class="service-icon-wrapper">
            <img :src="item.icon" alt="" />
          </div>
          <div class="service-content">
            <h4 class="service-title">{{ item.nama }}</h4>
            <p class="service-desc">Akses data dan infografis perkembangan {{ item.nama.toLowerCase() }} Kabupaten Kuningan secara terintegrasi.</p>
          </div>
        </div>
        <NuxtLink :to="item.link" class="service-link">
          <span>Lihat Data</span>
          <i class="ti-arrow-right ml-1 icon-arrow"></i>
        </NuxtLink>
      </div>
    </div>
  </div>
</template>

<style scoped lang="scss">
.services-container {
  padding: 40px 15px;
  width: 100%;
}

.services-header {
  margin-bottom: 35px;

  .services-section-title {
    font-size: 1.85rem;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 8px;
  }

  .services-section-subtitle {
    font-size: 1rem;
    color: #64748b;
    max-width: 600px;
    margin: 0 auto;
  }
}

.grid-services {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
  gap: 20px;
  width: 100%;
}

.service-card {
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 16px;
  padding: 24px;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  height: 230px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);

  &:hover {
    transform: translateY(-5px);
    border-color: #1E88E5;
    box-shadow: 0 12px 20px -5px rgba(30, 136, 229, 0.08), 0 8px 8px -5px rgba(30, 136, 229, 0.04);

    .service-icon-wrapper {
      background-color: rgba(30, 136, 229, 0.08);
      transform: scale(1.05);
    }

    .service-title {
      color: #1E88E5;
    }

    .service-link .icon-arrow {
      transform: translateX(4px);
    }
  }
}

.service-top {
  display: flex;
  gap: 16px;
  align-items: flex-start;
}

.service-icon-wrapper {
  flex-shrink: 0;
  width: 48px;
  height: 48px;
  background-color: #f8fafc;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.25s ease;
  border: 1px solid #f1f5f9;

  img {
    width: 24px;
    height: 24px;
    object-fit: contain;
  }
}

.service-content {
  flex-grow: 1;

  .service-title {
    font-size: 1.05rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 6px 0;
    transition: color 0.25s ease;
    line-height: 1.3;
  }

  .service-desc {
    font-size: 0.82rem;
    color: #64748b;
    line-height: 1.4;
    margin: 0;
    display: -webkit-box;
    -webkit-line-clamp: 4;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }
}

.service-link {
  display: inline-flex;
  align-items: center;
  font-size: 0.88rem;
  font-weight: 700;
  color: #1E88E5;
  text-decoration: none !important;
  margin-top: 15px;
  padding-top: 12px;
  border-top: 1px solid #f1f5f9;
  width: 100%;

  .icon-arrow {
    transition: transform 0.2s ease;
    font-size: 0.85rem;
  }
}

@media (max-width: 767px) {
  .services-container {
    padding: 20px 5px;
  }

  .services-header {
    margin-bottom: 25px;

    .services-section-title {
      font-size: 1.5rem;
    }
  }

  .service-card {
    height: auto;
    min-height: 180px;
    padding: 20px;
  }
}
</style>