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
      // data: [
      //   'https://kinerja.jabarprov.go.id/files15/sakip/infografis/2024/1. Rata-rata Lama Sekolah.png',
      //   'https://kinerja.jabarprov.go.id/files15/sakip/infografis/2024/2. Harapan Lama Sekolah.png',
      //   'https://kinerja.jabarprov.go.id/files15/sakip/infografis/2024/3. Angka Harapan Hidup.png',
      //   'https://kinerja.jabarprov.go.id/files15/sakip/infografis/2024/4. Prevalensi Stunting.png',
      //   'https://kinerja.jabarprov.go.id/files15/sakip/infografis/2024/5. Pengeluaran Perkapita.png',
      //   'https://kinerja.jabarprov.go.id/files15/sakip/infografis/2024/6. Persentase Penduduk Miskin.png',
      //   'https://kinerja.jabarprov.go.id/files15/sakip/infografis/2024/7. Indeks Pemberdayaan Gender (IDG).png',
      //   'https://kinerja.jabarprov.go.id/files15/sakip/infografis/2024/8. Indeks Perlindungan Anak (IPA).png',
      //   'https://kinerja.jabarprov.go.id/files15/sakip/infografis/2024/9. Indeks Pembangunan Pemuda.png',
      //   'https://kinerja.jabarprov.go.id/files15/sakip/infografis/2024/10. Laju Pertumbuhan Penduduk.png',
      //   'https://kinerja.jabarprov.go.id/files15/sakip/infografis/2024/11. Laju Pertumbuhan Sektor Industri.png',
      //   'https://kinerja.jabarprov.go.id/files15/sakip/infografis/2024/12. Laju Pertumbuhan Sektor Perdagangan.png',
      //   'https://kinerja.jabarprov.go.id/files15/sakip/infografis/2024/13. Laju Pertumbuhan Sektor Pertanian, Kehutanan, dan Perikanan.png',
      //   'https://kinerja.jabarprov.go.id/files15/sakip/infografis/2024/14. Nilai Tukar Petani (NTP).png',
      //   'https://kinerja.jabarprov.go.id/files15/sakip/infografis/2024/15. Skor Pola Pangan Harapan (SPPH).png',
      //   'https://kinerja.jabarprov.go.id/files15/sakip/infografis/2024/16. Laju Pertumbuhan Sektor Penyediaan Akomodasi Makan Minum.png',
      //   'https://kinerja.jabarprov.go.id/files15/sakip/infografis/2024/17. Pembentukan Modal Tetap Bruto (PMTB) ADHB.png',
      //   'https://kinerja.jabarprov.go.id/files15/sakip/infografis/2024/18. Proporsi Kredit UMKM Terhadap Total Kredit.png',
      //   'https://kinerja.jabarprov.go.id/files15/sakip/infografis/2024/19. Tingkat Pengangguran Terbuka.png',
      //   'https://kinerja.jabarprov.go.id/files15/sakip/infografis/2024/20. Tingkat Konektivitas Antar Wilayah.png',
      //   'https://kinerja.jabarprov.go.id/files15/sakip/infografis/2024/21. Indeks Kualitas Infrastruktur.png',
      //   'https://kinerja.jabarprov.go.id/files15/sakip/infografis/2024/22. Persentase Rumah Tangga Hunian Layak.png',
      //   'https://kinerja.jabarprov.go.id/files15/sakip/infografis/2024/23. Indeks Kualitas Lingkungan Hidup (IKLH).png',
      //   'https://kinerja.jabarprov.go.id/files15/sakip/infografis/2024/24. Tingkat Penurunan Emisi Gas Rumah Kaca.png',
      //   'https://kinerja.jabarprov.go.id/files15/sakip/infografis/2024/25. Indeks Risiko Bencana.png',
      //   'https://kinerja.jabarprov.go.id/files15/sakip/infografis/2024/26. Indeks Desa Membangun.png',
      //   'https://kinerja.jabarprov.go.id/files15/sakip/infografis/2024/27. Indeks Demokrasi Indonesia (IDI) Jawa Barat.png',
      //   'https://kinerja.jabarprov.go.id/files15/sakip/infografis/2024/28. Indeks Reformasi Birokrasi.png',
      //   'https://kinerja.jabarprov.go.id/files15/sakip/infografis/2024/29. Indeks Inovasi Daerah.png',
      // ],
      selected: null,
      selectedType: 'image'
    };
  },
  methods: {
   showModal(item) {
      if (item.pdf_url) {
        this.selected = item.pdf_url;
        this.selectedType = 'pdf';
      } else {
        this.selected = item.gambar_url;
        this.selectedType = 'image';
      }
      this.$bvModal.show("bv-modal-infografis");
    },
    async getData() {
      this.isBusy = true;
      try {
        const response = await axios.get(`infografis`); // Added await and this.$axios
        this.data = response.data; // Access the data property of the response
        console.log(this.data); // This should now work
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
    <h4 class="text-md font-medium text-white">Laporan Kinerja Tahun 2025</h4>
    <!-- <EmblaCarousel :slides-per-view="4" :spacing="24" :slides="data">
      <template #default="{ data: slideData }">
        <div
          class="bg-white rounded-lg p-12"
          role="button"
          @click="showModal(slideData)"
        >
          <img :src="slideData.gambar_url" class="w-full h-full" alt="" />
        </div>
      </template>
    </EmblaCarousel> -->
     <b-modal id="bv-modal-infografis" size="xl" hide-header hide-footer>
      <div v-if="selectedType === 'image'">
        <img :src="selected" class="w-full h-full" alt="" />
      </div>
      <div v-else-if="selectedType === 'pdf'" class="pdf-container">
        <iframe 
          :src="selected" 
          class="w-full pdf-iframe"
          frameborder="0">
        </iframe>
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

.pdf-container {
  width: 100%;
  height: 80vh;
}

.pdf-iframe {
  width: 100%;
  height: 100%;
}
</style>