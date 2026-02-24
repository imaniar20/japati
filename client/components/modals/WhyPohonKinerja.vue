<script>
const componentId = "modal-why-pohon-kinerja";
import axios from "axios";

export default {
  name: componentId,

  data() {
    return {
      id: componentId,
      sasaran: "",
      pohonKinerjaId: "",
      iku: "",
      showEventId: `show-${componentId}`,
      hideEventId: `hide-${componentId}`,
      data: null,
      dataDetail: null,
      isBusy: {
        getData: false,
      },
    };
  },

  mounted() {
    this.$nuxt.$on(this.showEventId, (sasaran, pohonKinerjaId) => {
      this.sasaran = sasaran;
      this.pohonKinerjaId = pohonKinerjaId;
      this.reset();
      this.getData();
      this.$bvModal.show(this.id);
    });

    this.$nuxt.$on(this.hideEventId, () => {
      this.$bvModal.hide(this.id);
    });
  },
  computed: {
     scoreAsPercentage() {
      return (this.dataDetail.data[0]?.score_ai || 0) * 100;
    },
    badgeVariant() {
      const label = this.detailData?.label_ai?.toLowerCase();
      if (label === 'positive') return 'success';
      if (label === 'negative') return 'danger';
      return 'secondary'; // Neutral or other cases
    }
  },

  methods: {
    formatSasaran(sasaran) {
      if (typeof sasaran !== "string" || !sasaran.trim()) {
        return ""; // Return an empty string if input is invalid
      }

      const words = sasaran.split(" ");
      const firstWord = words[0] || "";
      if (firstWord.startsWith("Ter") || firstWord.startsWith("Di")) {
        return `Belum ${sasaran}`;
      }
      if (!firstWord.startsWith("Ter") && sasaran.includes("mendapatkan")) {
        return sasaran.replace("mendapatkan", "belum mendapatkan");
      }
      if (!firstWord.startsWith("Ter") && sasaran.includes("dimanfaatkan")) {
        return sasaran.replace("dimanfaatkan", "belum dimanfaatkan");
      }
      if (firstWord === "Meningkatnya") {
        return sasaran.replace("Meningkatnya", "Belum Optimalnya");
      }
      return sasaran;
    },

    async getData(page = 1) {
      this.isBusy.getData = true;
      console.log(this.pohonKinerjaId);
      let data = this.formatSasaran(this.sasaran);

      let dataDetail  = await axios.get(
        "public-display/pohon-kinerja-detail",
        {
          params: {
            pohon_kinerja_id: this.pohonKinerjaId,
          },
        }
      );
      this.dataDetail = dataDetail;
      this.data = data;
      this.isBusy.getData = false;
    },
    reset() {
      this.data = null;
    },

    renderedMath(rumus) {
      return this.$katex.renderToString(rumus, {
        throwOnError: false,
      });
    },
    // goToIndikator(indikatorId) {
    //   this.$bvModal.hide(this.id)
    //   this.$emit('go-to-indikator', indikatorId)
    // },
  },
};
</script>

<template>
  <b-modal :id="id" title="Detail Analisis Kinerja (Why)" size="xl" centered>
    <div v-if="isBusy.getData" class="text-center">
      <b-spinner label="Loading..."></b-spinner>
      <p class="mt-2">Memuat data...</p>
    </div>

    <div v-else-if="dataDetail">
      <b-card no-body>
        <b-card-header header-bg-variant="primary" header-text-variant="white">
          <h5 class="mb-0">
            <i class="fa fa-bullseye mr-2"></i> Sasaran & Indikator
          </h5>
        </b-card-header>
        <b-card-body>
          <h6><strong>Sasaran:</strong></h6>
          <p class="text-muted">{{ dataDetail.data[0].sasaran }}</p>
          <hr />
          <h6><strong>Indikator:</strong></h6>
          <p class="text-muted">{{ dataDetail.data[0].indikator }}</p>
        </b-card-body>
      </b-card>

      <b-card no-body class="mt-3">
        <b-card-header header-bg-variant="info" header-text-variant="white">
          <h5 class="mb-0">
            <i class="fa fa-microchip mr-2"></i> Analisis AI
          </h5>
        </b-card-header>
        <b-list-group flush>
          <b-list-group-item class="d-flex justify-content-between align-items-center">
            <span>Hubungan Kinerja</span>
            <b-badge :variant="badgeVariant" pill class="px-3 py-2">{{ dataDetail.data[0].label_ai }}</b-badge>
          </b-list-group-item>

          <b-list-group-item>
            <span>Skor Kepercayaan AI</span>
            <b-progress :value="scoreAsPercentage" :variant="badgeVariant" striped animated class="mt-1"></b-progress>
             <div class="text-right">
                <small><strong>{{ scoreAsPercentage.toFixed(2) }}%</strong></small>
            </div>
          </b-list-group-item>

          <b-list-group-item>
            <h6>Alasan Korelasi:</h6>
            <p class="mb-0 font-italic">"{{ dataDetail.data[0].reasoning_korelasi_ai }}"</p>
          </b-list-group-item>

          <b-list-group-item>
            <h6>Alasan Kinerja Harus Dikerjakan:</h6>
            <p class="mb-0 font-italic">"{{ dataDetail.data[0].reasoning_kinerja_ai }}"</p>
          </b-list-group-item>
        </b-list-group>
      </b-card>
    </div>

    <div v-else class="text-center">
      <p>Data tidak ditemukan.</p>
    </div>

  </b-modal>
</template>

<style scoped>
.clickable {
  cursor: pointer;
}

.clickable:hover {
  text-decoration: underline;
}
</style>