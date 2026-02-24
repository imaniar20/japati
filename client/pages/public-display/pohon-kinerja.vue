<script>
import axios from "axios";
import Swal from "sweetalert2";
import HandyScroll from "vue-handy-scroll";
import ModalWhyPohonKinerja from "~/components/modals/WhyPohonKinerja.vue";
export default {
  layout: "guest",

  components: {
    HandyScroll,
    ModalWhyPohonKinerja,
  },

  async asyncData() {
    const { data: satkerList } = await axios.get("/option/satuan-kerja");

    return {
      satkerList,
    };
  },

  data() {
    return {
      data: [],
      filter: {
        satuan_kerja_id: null,
        sasaran_pohon_kinerja_id: null,
      },
      isBusy: false,
      componentKey: 0,
      keyComponent: 1,
      fullscreen: false,
      teleport: true,
      showDiagram: true,
      renderedImage: null,
      visibility: {
        sasaran: true,
        indikator: true,
        pengampu: true,
        kinerja: true,
      },
      labels: {
        lv1: {
          sasaran: "Sasaran Strategis RPJMD ( Ultimate Outcome)",
          indikator: "Indikator Sasaran Strategis RPJMD ( Ultimate Outcome)",
        },
        lv2: {
          sasaran:
            "Sasaran Strategis Renstra PD ( Intermediate Outcome Bidang Urusan)",
          indikator:
            "Indikator Sasaran Strategis Renstra PD ( Intermediate Outcome Bidang Urusan)",
        },
        lv3: {
          sasaran: "Sasaran Program (Intermediate Outcome)",
          indikator: "Indikator Sasaran Program (Intermediate Outcome)",
        },
        lv4: {
          sasaran: "Sasaran Kegiatan (Immediate Outcome)",
          indikator: "Indikator Kegiatan (Immediate Outcome)",
        },
        lv5: {
          sasaran: "Sasaran Sub Kegiatan (Output)",
          indikator: "Indikator Sub Kegiatan (Output)",
        },
      },
      options: {
        export: {
          pixelRatio: 1,
        },
        showIconCapaian: false,
        showCross: false,
        showCrossIds: [],
        highlight: "",
        showIconEditKinerja: false,
      },
      kegagalanModalData: null,
      kinerjaTercapaiData: [],
      isBusy: {
        render: false,
        export: false,
        exportExcel: false,
      },
      kamusIndikatorData: {
        indikator: null,
        indikatorKemendagri: null,
      },
      autoShowModal: {
        type: this.$route.query.autoShowModalType || null,
        id: this.$route.query.autoShowModalId || null,
        highlight: "",
      },
      autoScrollToKinerjaSubKegiatan: {
        type: this.$route.query.autoScrollToKinerjaSubKegiatanType || null,
        id: this.$route.query.autoScrollToKinerjaSubKegiatanId || null,
        highlight: "",
      },
    };
  },

  watch: {
    filter: {
      handler: function () {
        this.getData();
      },
      deep: true,
    },
  },

  mounted() {
    this.$store.dispatch("tahun-kinerja/setTahunKinerjaPublic", 20252);
    this.$store.dispatch("tahun-kinerja/setTahunKinerja", 20252);

    this.filter.sasaran_pohon_kinerja_id =
      Number(this.$route.query.sasaran_pohon_kinerja_id) || null;
  },
  computed: {
    satker() {
      if (!this.satkerId) return null;

      return this.satkerList.find((el) => el.satuan_kerja_id == this.satkerId);
    },
  },
  methods: {
    sendKorelasiAI(index) {
      Swal.fire({
        title: "Menggenerate Korelasi Kinerja Berdasarkan AI",
        text: "Apakah Anda yakin?",
        type: "question",
        showConfirmButton: true,
        showCancelButton: true,
        confirmButtonColor: "#10944c",
        confirmButtonText: "Konfirmasi",
      }).then(async ({ value }) => {
        if (value) {
          this.isBusy = true;
          let dataDetail = await axios.get(
            "https://japati.jabarprov.go.id/ai-biro-organisasi/api/get_ai_korelasi_pohon_kinerja/" +
              index
          );
          this.isBusy = false;
          this.getData();
        }
      });
    },
    sendRekomendasiAI(index) {
      Swal.fire({
        title: "Menggenerate Rekomendasi Kinerja Pendukung Berdasarkan AI",
        text: "Apakah Anda yakin?",
        type: "question",
        showConfirmButton: true,
        showCancelButton: true,
        confirmButtonColor: "#10944c",
        confirmButtonText: "Konfirmasi",
      }).then(async ({ value }) => {
        this.isBusy = true;
        if (value) {
          this.isBusy = true;
          let dataDetail = await axios.get(
            "https://japati.jabarprov.go.id/ai-biro-organisasi/api/get_ai_rekomendasi_sasaran_pohon_kinerja/" +
              index
          );
          this.isBusy = false;
          this.getData();
        }
      });
    },
    toggle() {
      fullscreen.toggle(this.$el.querySelector(".fullscreen-wrapper"), {
        teleport: this.teleport,
        callback: (isFullscreen) => {
          this.fullscreen = isFullscreen;
        },
      });
    },
    async getData() {
      if (!this.filter.sasaran_pohon_kinerja_id) {
        this.data = [];
        return;
      }

      this.isBusy = true;
      console.log(this.filter.sasaran_pohon_kinerja_id);
      const { data } = await axios.get("/public-display/pohon-kinerja", {
        params: {
          sasaran_pohon_kinerja_id: this.filter.sasaran_pohon_kinerja_id,
        },
      });

      this.data = data;
      this.isBusy = false;
      this.componentKey++;
    },
    async renderImage() {
      this.isBusy.render = true;
      var node = document.getElementById("diagram");

      toPng(node, { pixelRatio: 1 })
        .then((image) => {
          this.renderedImage = image;
          this.showDiagram = false;
        })
        .catch(function (error) {
          console.error("oops, something went wrong!", error);
          alert("Mohon maaf, silahkan ulangi lagi proses export");
        })
        .finally(() => (this.isBusy.render = false));
    },
  },
};
</script>

<template>
  <b-card>
    <div>
      <!-- <b-row>
        <b-col sm="6" md="4">
          <FilterSatuanKerja v-model="filter.satuan_kerja_id" />
        </b-col>
      </b-row> -->
      <b-row>
        <b-col sm="6" md="4">
          <FilterSasaranPohonKinerja
            v-model="filter.sasaran_pohon_kinerja_id"
          ></FilterSasaranPohonKinerja>
        </b-col>
      </b-row>
    </div>

    <div style="font-family: 'Intro', sans-serif" class="pos-r">
      <!-- <b-button
        v-if="showDiagram"
        @click="renderImage"
        variant="primary"
        :disabled="isBusy.render"
      >
        <b-spinner small v-if="isBusy.render"></b-spinner>
        Render gambar
      </b-button>
      <b-button
        v-if="showDiagram"
        variant="success"
        @click="exportDiagramImage(satker ? satker.satuan_kerja_nama : '')"
        :disabled="isBusy.export"
      >
        <b-spinner small v-if="isBusy.export"></b-spinner>
        <i v-else class="fa fa-image"></i>
        Export
      </b-button>
      <b-button
        v-if="showDiagram"
        variant="success"
        @click="exportExcel(satker ? satker.satuan_kerja_nama : '')"
        :disabled="isBusy.exportExcel"
      >
        <b-spinner small v-if="isBusy.exportExcel"></b-spinner>
        <i v-else class="fa fa-file-excel-o"></i>
        Export Excel
      </b-button> -->

      <div
        v-if="!data.length || isBusy"
        class="text-center my-4 font-weight-bold"
      >
        <div v-if="!filter.sasaran_pohon_kinerja_id">
          Pilih sasaran terlebih dahulu
        </div>
        <div v-else>
          <span v-if="isBusy">Memuat Data....</span>
          <span v-else>Tidak ada data</span>
        </div>
      </div>

      <!-- <b-form-checkbox
        v-if="showDiagram"
        v-model="options.showCross"
        name="check-icon"
        switch
        class="mt-2"
      >
        Tampilkan cross cutting
      </b-form-checkbox> -->
      <b-alert show class="mt-1" v-if="$route.query.showOption == 'true'">
        <b-form-group label="Pixel Ratio" label-for="pixel-ratio">
          <b-form-input
            id="pixel-ratio"
            v-model="options.export.pixelRatio"
            type="number"
            step="0.1"
          ></b-form-input>
        </b-form-group>
      </b-alert>

      <b-form-checkbox
        v-if="renderedImage"
        v-model="showDiagram"
        name="show-diagram"
        switch
        class="mt-2"
      >
        Tampilkan dalam bentuk teks
      </b-form-checkbox>
      <div v-if="isBusy.render" class="text-center">
        <b-spinner small></b-spinner>
        Sedang merender data...
      </div>
      <button
        class="maximize-btn btn btn-light"
        type="button"
        @click="toggle"
        v-if="!fullscreen"
      >
        <i class="fa fa-arrows-alt"></i> Maximize
      </button>

      <div
        :key="keyComponent"
        class="fullscreen-wrapper"
        style="overflow-x: hidden; z-index: 1000; background: white"
        :style="{ 'overflow-y': showDiagram ? 'scroll' : 'hidden' }"
      >
        <VuePanZoom
          :options="{ minZoom: 1, maxZoom: 40 }"
          v-show="renderedImage ? !showDiagram : true"
        >
          <img
            id="img-diagram"
            :src="renderedImage"
            style="max-width: 100%"
            alt=""
          />
        </VuePanZoom>
        <button
          class="minimize-btn btn btn-light"
          type="button"
          v-if="fullscreen"
          @click="toggle"
        >
          <i class="fa fa-window-close-o"></i> Minimize
        </button>

        <HandyScroll v-if="!isBusy">
          <div
            id="diagram"
            class="justify-content-center"
            style="
              background: white;
              display: inline-flex;
              flex-direction: column;
              padding: 0 15px 0 30px;
              margin: 15px;
              padding-bottom: 50px;
            "
          >
            <div class="text-center mb-3 h4">
              <span v-if="satker">{{ satker.satuan_kerja_nama }}</span>
            </div>
            <div
              class="text-left align-items-center"
              style="display: inline-flex; flex-direction: column"
            >
              <ul class="tree m-0">
                <li v-for="sasaran_lv1 of data" :key="sasaran_lv1.id">
                  <span class="text-left level1-box sasaranRpjmd">
                     <b-button
                          variant="primary"
                          size="sm"
                          pill
                          v-b-tooltip.hover
                          title="Why"
                          @click="
                            $nuxt.$emit(
                              'show-modal-why-pohon-kinerja',
                              sasaran_lv1.sasaran_lv1,
                              sasaran_lv1.id_lv1
                            )
                          "
                        >
                          W
                        </b-button>
                    <div class="text-right w-100"></div>
                    <div class="d-flex align-items-baseline w-100">
                      <i class="fa fa-bullseye mr-2"></i>
                      <div>
                        <small>Kinerja Level 1</small>
                        <h6
                          class="m-0 mb-1 sasaran-title font-wight-bold"
                          style="word-wrap: anywhere"
                        >
                          {{ sasaran_lv1.sasaran_lv1 }}
                        </h6>
                      </div>
                    </div>
                    <div class="d-flex align-items-baseline w-100">
                      <i class="fa fa-tachometer mr-2"></i>
                      <div>
                        <small>Indikator Level 1</small>
                        <small>Kinerja Level 1</small>
                        <h6
                          class="m-0 mb-1 sasaran-title font-wight-bold"
                          style="word-wrap: anywhere"
                        >
                          {{ sasaran_lv1.indikator_lv1 }}
                        </h6>
                      </div>
                    </div>
                  </span>
                  <ul
                    v-if="
                      sasaran_lv1.children && sasaran_lv1.children.length > 0
                    "
                  >
                    <li
                      v-for="sasaran_lv2 of sasaran_lv1.children"
                      :key="sasaran_lv2.id"
                    >
                      <span class="text-left satkerSasaran level2-box">
                        <b-button
                          variant="primary"
                          size="sm"
                          pill
                          v-b-tooltip.hover
                          title="Why"
                          @click="
                            $nuxt.$emit(
                              'show-modal-why-pohon-kinerja',
                              sasaran_lv2.sasaran_lv2,
                              sasaran_lv2.id_lv2
                            )
                          "
                        >
                          W
                        </b-button>
                        <div class="text-right w-100"></div>
                        <div class="d-flex align-items-baseline w-100">
                          <i class="fa fa-bullseye mr-2"></i>
                          <div>
                            <small>Kinerja Level 2</small>
                            <h6
                              class="m-0 mb-1 sasaran-title font-wight-bold"
                              style="word-wrap: anywhere"
                            >
                              {{ sasaran_lv2.sasaran_lv2 }}
                            </h6>
                          </div>
                        </div>
                        <div class="d-flex align-items-baseline w-100">
                          <i class="fa fa-tachometer mr-2"></i>
                          <div>
                            <small>Indikator Level 2</small>
                            <h6
                              class="m-0 mb-1 sasaran-title font-wight-bold"
                              style="word-wrap: anywhere"
                            >
                              {{ sasaran_lv2.indikator_lv2 }}
                            </h6>
                          </div>
                        </div>
                        <hr
                          class="my-2"
                          style="border-color: black; width: 100%"
                        />
                        <div
                          v-if="sasaran_lv2.label_ai_lv2"
                          class="d-flex align-items-baseline w-100"
                        >
                          <i class="fa fa-microchip mr-2"></i>
                          <div>
                            <small
                              >Hubungan Kinerja Berdasarkan AI (Skor Keyakinan
                              AI)</small
                            >
                            <h6
                              class="m-0 mb-1 sasaran-title font-wight-bold"
                              style="word-wrap: anywhere"
                            >
                              {{ sasaran_lv2.label_ai_lv2 }}
                              {{ (sasaran_lv2.score_ai_lv2 * 100).toFixed(0) }}%
                            </h6>
                          </div>
                        </div>
                      </span>
                      <ul
                        v-if="
                          sasaran_lv2.children &&
                          sasaran_lv2.children.length > 0
                        "
                      >
                        <li
                          v-for="sasaran_lv3 of sasaran_lv2.children"
                          :key="sasaran_lv3.id"
                        >
                          <span class="text-left kinerjaProgram level3-box">
                            <b-button
                              v-if="sasaran_lv3.is_ai_rekomendasi_lv3"
                              variant="light"
                              class="mb-2"
                              size="sm"
                              pill
                              v-b-tooltip.hover
                            >
                              Sasaran Rekomandasi AI
                            </b-button>
                            <div class="d-flex justify-content-between">
                              <b-button
                                variant="primary"
                                size="sm"
                                pill
                                v-b-tooltip.hover
                                v-if="sasaran_lv3.is_crosscutting_lv3"
                              >
                                CC
                              </b-button>
                              <b-button
                                variant="primary"
                                size="sm"
                                pill
                                v-b-tooltip.hover
                                title="Why"
                                @click="
                                  $nuxt.$emit(
                                    'show-modal-why-pohon-kinerja',
                                    sasaran_lv3.sasaran_lv3,
                                    sasaran_lv3.id_lv3
                                  )
                                "
                              >
                                W
                              </b-button>
                            </div>
                            <div class="text-right w-100"></div>
                            <div class="d-flex align-items-baseline w-100">
                              <i class="fa fa-bullseye mr-2"></i>
                              <div>
                                <small>Kinerja Level 3</small>
                                <h6
                                  class="m-0 mb-1 sasaran-title font-wight-bold"
                                  style="word-wrap: anywhere"
                                >
                                  {{ sasaran_lv3.sasaran_lv3 }}
                                </h6>
                              </div>
                            </div>
                            <div class="d-flex align-items-baseline w-100">
                              <i class="fa fa-tachometer mr-2"></i>
                              <div>
                                <small>Indikator Level 3</small>
                                <h6
                                  class="m-0 mb-1 sasaran-title font-wight-bold"
                                  style="word-wrap: anywhere"
                                >
                                  {{ sasaran_lv3.indikator_lv3 }}
                                </h6>
                              </div>
                            </div>
                            <hr
                              class="my-2"
                              style="border-color: white; width: 100%"
                            />
                            <div class="d-flex justify-content-between">
                              <b-button
                                v-if="$role.isSuper()"
                                class="mr-2"
                                variant="primary"
                                size="sm"
                                pill
                                v-b-tooltip.hover
                                @click="sendKorelasiAI(sasaran_lv3.id_lv3)"
                              >
                                Korelasi AI
                              </b-button>
                              <b-button
                                v-if="$role.isSuper()"
                                variant="primary"
                                size="sm"
                                pill
                                v-b-tooltip.hover
                                @click="sendRekomendasiAI(sasaran_lv3.id_lv3)"
                              >
                                Rekomendasi AI
                              </b-button>
                            </div>
                            <div
                              v-if="sasaran_lv3.label_ai_lv3"
                              class="d-flex align-items-baseline w-100"
                            >
                              <i class="fa fa-microchip mr-2"></i>
                              <div>
                                <small
                                  >Hubungan Kinerja Berdasarkan AI (Skor
                                  Keyakinan AI)</small
                                >
                                <h6
                                  class="m-0 mb-1 sasaran-title font-wight-bold"
                                  style="word-wrap: anywhere"
                                >
                                  {{ sasaran_lv3.label_ai_lv3 }}
                                  {{
                                    (sasaran_lv3.score_ai_lv3 * 100).toFixed(0)
                                  }}%
                                </h6>
                              </div>
                            </div>
                          </span>
                          <ul
                            v-if="
                              sasaran_lv3.children &&
                              sasaran_lv3.children.length > 0
                            "
                          >
                            <li
                              v-for="sasaran_lv4 of sasaran_lv3.children"
                              :key="sasaran_lv4.id"
                            >
                              <span
                                class="text-left kinerjaKegiatan level4-box"
                              >
                                <b-button
                                  v-if="sasaran_lv4.is_ai_rekomendasi_lv4"
                                  variant="light"
                                  class="mb-2"
                                  size="sm"
                                  pill
                                  v-b-tooltip.hover
                                >
                                  Sasaran Rekomandasi AI
                                </b-button>
                                <div class="d-flex justify-content-between">
                                  <b-button
                                    v-if="sasaran_lv4.is_crosscutting_lv4"
                                    variant="primary"
                                    size="sm"
                                    pill
                                    v-b-tooltip.hover
                                  >
                                    CC
                                  </b-button>
                                  <b-button
                                    variant="primary"
                                    size="sm"
                                    pill
                                    v-b-tooltip.hover
                                    title="Why"
                                    @click="
                                      $nuxt.$emit(
                                        'show-modal-why-pohon-kinerja',
                                        sasaran_lv4.sasaran_lv4,
                                        sasaran_lv4.id_lv4
                                      )
                                    "
                                  >
                                    W
                                  </b-button>
                                </div>

                                <div class="text-right w-100"></div>
                                <div class="d-flex align-items-baseline w-100">
                                  <i class="fa fa-bullseye mr-2"></i>
                                  <div>
                                    <small>Kinerja Level 4</small>
                                    <h6
                                      class="m-0 mb-1 sasaran-title font-wight-bold"
                                      style="word-wrap: anywhere"
                                    >
                                      {{ sasaran_lv4.sasaran_lv4 }}
                                    </h6>
                                  </div>
                                </div>
                                <div class="d-flex align-items-baseline w-100">
                                  <i class="fa fa-tachometer mr-2"></i>
                                  <div>
                                    <small>Indikator Level 4</small>
                                    <h6
                                      class="m-0 mb-1 sasaran-title font-wight-bold"
                                      style="word-wrap: anywhere"
                                    >
                                      {{ sasaran_lv4.indikator_lv4 }}
                                    </h6>
                                  </div>
                                </div>
                                <hr
                                  class="my-2"
                                  style="border-color: white; width: 100%"
                                />
                                <div class="d-flex justify-content-between">
                                  <b-button
                                    v-if="$role.isSuper()"
                                    class="mr-2"
                                    variant="primary"
                                    size="sm"
                                    pill
                                    v-b-tooltip.hover
                                    @click="sendKorelasiAI(sasaran_lv4.id_lv4)"
                                  >
                                    Korelasi AI
                                  </b-button>
                                  <b-button
                                    v-if="$role.isSuper()"
                                    variant="primary"
                                    size="sm"
                                    pill
                                    v-b-tooltip.hover
                                    @click="
                                      sendRekomendasiAI(sasaran_lv4.id_lv4)
                                    "
                                  >
                                    Rekomendasi AI
                                  </b-button>
                                </div>
                                <div
                                  v-if="sasaran_lv4.label_ai_lv4"
                                  class="d-flex align-items-baseline w-100"
                                >
                                  <i class="fa fa-microchip mr-2"></i>
                                  <div>
                                    <small
                                      >Hubungan Kinerja Berdasarkan AI (Skor
                                      Keyakinan AI)</small
                                    >
                                    <h6
                                      class="m-0 mb-1 sasaran-title font-wight-bold"
                                      style="word-wrap: anywhere"
                                    >
                                      {{ sasaran_lv4.label_ai_lv4 }}
                                      {{
                                        (
                                          sasaran_lv4.score_ai_lv4 * 100
                                        ).toFixed(0)
                                      }}%
                                    </h6>
                                  </div>
                                </div>
                              </span>
                              <ul
                                v-if="
                                  sasaran_lv4.children &&
                                  sasaran_lv4.children.length > 0
                                "
                              >
                                <li
                                  v-for="sasaran_lv5 of sasaran_lv4.children"
                                  :key="sasaran_lv5.id"
                                >
                                  <span class="text-left level5-box">
                                    <b-button
                                      v-if="sasaran_lv5.is_ai_rekomendasi_lv5"
                                      variant="light"
                                      class="mb-2"
                                      size="sm"
                                      pill
                                      v-b-tooltip.hover
                                    >
                                      Sasaran Rekomandasi AI
                                    </b-button>
                                    <div class="d-flex justify-content-between">
                                      <b-button
                                        class="mr-2"
                                        variant="primary"
                                        size="sm"
                                        pill
                                        v-b-tooltip.hover
                                        v-if="sasaran_lv5.is_crosscutting_lv5"
                                      >
                                        CC
                                      </b-button>
                                      <b-button
                                        variant="primary"
                                        size="sm"
                                        pill
                                        v-b-tooltip.hover
                                        title="Why"
                                        @click="
                                          $nuxt.$emit(
                                            'show-modal-why-pohon-kinerja',
                                            sasaran_lv5.sasaran_lv5,
                                            sasaran_lv5.id_lv5
                                          )
                                        "
                                      >
                                        W
                                      </b-button>
                                    </div>

                                    <div class="text-right w-100"></div>
                                    <div
                                      class="d-flex align-items-baseline w-100"
                                    >
                                      <i class="fa fa-bullseye mr-2"></i>
                                      <div>
                                        <small>Kinerja Level 5</small>
                                        <h6
                                          class="m-0 mb-1 sasaran-title font-wight-bold"
                                          style="word-wrap: anywhere"
                                        >
                                          {{ sasaran_lv5.sasaran_lv5 }}
                                        </h6>
                                      </div>
                                    </div>
                                    <div
                                      class="d-flex align-items-baseline w-100"
                                    >
                                      <i class="fa fa-tachometer mr-2"></i>
                                      <div>
                                        <small>Indikator Level 5</small>
                                        <h6
                                          class="m-0 mb-1 sasaran-title font-wight-bold"
                                          style="word-wrap: anywhere"
                                        >
                                          {{ sasaran_lv5.indikator_lv5 }}
                                        </h6>
                                      </div>
                                    </div>
                                    <hr
                                      class="my-2"
                                      style="border-color: white; width: 100%"
                                    />
                                    <div class="d-flex justify-content-between">
                                      <b-button
                                        v-if="$role.isSuper()"
                                        class="mr-2"
                                        variant="primary"
                                        size="sm"
                                        pill
                                        v-b-tooltip.hover
                                        @click="
                                          sendKorelasiAI(sasaran_lv5.id_lv5)
                                        "
                                      >
                                        Korelasi AI
                                      </b-button>
                                      <b-button
                                        v-if="$role.isSuper()"
                                        variant="primary"
                                        size="sm"
                                        pill
                                        v-b-tooltip.hover
                                        @click="
                                          sendRekomendasiAI(sasaran_lv5.id_lv5)
                                        "
                                      >
                                        Rekomendasi AI
                                      </b-button>
                                    </div>
                                    <div
                                      v-if="sasaran_lv5.label_ai_lv5"
                                      class="d-flex align-items-baseline w-100"
                                    >
                                      <i class="fa fa-microchip mr-2"></i>
                                      <div>
                                        <small
                                          >Hubungan Kinerja Berdasarkan AI (Skor
                                          Keyakinan AI)</small
                                        >
                                        <h6
                                          class="m-0 mb-1 sasaran-title font-wight-bold"
                                          style="word-wrap: anywhere"
                                        >
                                          {{ sasaran_lv5.label_ai_lv5 }}
                                          {{
                                            (
                                              sasaran_lv5.score_ai_lv5 * 100
                                            ).toFixed(0)
                                          }}%
                                        </h6>
                                      </div>
                                    </div>
                                  </span>
                                  <ul
                                    v-if="
                                      sasaran_lv5.children &&
                                      sasaran_lv5.children.length > 0
                                    "
                                  >
                                    <li
                                      v-for="sasaran_lv6 of sasaran_lv5.children"
                                      :key="sasaran_lv6.id"
                                    >
                                      <span class="text-left level6-box">
                                        <b-button
                                          v-if="
                                            sasaran_lv6.is_ai_rekomendasi_lv6
                                          "
                                          variant="light"
                                          class="mb-2"
                                          size="sm"
                                          pill
                                          v-b-tooltip.hover
                                        >
                                          Sasaran Rekomandasi AI
                                        </b-button>
                                        <div
                                          class="d-flex justify-content-between"
                                        >
                                          <b-button
                                            class="mr-2"
                                            variant="primary"
                                            size="sm"
                                            pill
                                            v-b-tooltip.hover
                                            v-if="
                                              sasaran_lv6.is_crosscutting_lv6
                                            "
                                          >
                                            CC
                                          </b-button>
                                          <b-button
                                            variant="primary"
                                            size="sm"
                                            pill
                                            v-b-tooltip.hover
                                            title="Why"
                                            @click="
                                              $nuxt.$emit(
                                                'show-modal-why-pohon-kinerja',
                                                sasaran_lv6.sasaran_lv6,
                                                sasaran_lv6.id_lv6
                                              )
                                            "
                                          >
                                            W
                                          </b-button>
                                        </div>

                                        <div class="text-right w-100"></div>
                                        <div
                                          class="d-flex align-items-baseline w-100"
                                        >
                                          <i class="fa fa-bullseye mr-2"></i>
                                          <div>
                                            <small>Kinerja Level 6</small>
                                            <h6
                                              class="m-0 mb-1 sasaran-title font-wight-bold"
                                              style="word-wrap: anywhere"
                                            >
                                              {{ sasaran_lv6.sasaran_lv6 }}
                                            </h6>
                                          </div>
                                        </div>
                                        <div
                                          class="d-flex align-items-baseline w-100"
                                        >
                                          <i class="fa fa-tachometer mr-2"></i>
                                          <div>
                                            <small>Indikator Level 6</small>
                                            <h6
                                              class="m-0 mb-1 sasaran-title font-wight-bold"
                                              style="word-wrap: anywhere"
                                            >
                                              {{ sasaran_lv6.indikator_lv6 }}
                                            </h6>
                                          </div>
                                        </div>
                                        <hr
                                          class="my-2"
                                          style="
                                            border-color: white;
                                            width: 100%;
                                          "
                                        />
                                        <div
                                          class="d-flex justify-content-between"
                                        >
                                          <b-button
                                            v-if="$role.isSuper()"
                                            class="mr-2"
                                            variant="primary"
                                            size="sm"
                                            pill
                                            v-b-tooltip.hover
                                            @click="
                                              sendKorelasiAI(sasaran_lv6.id_lv6)
                                            "
                                          >
                                            Korelasi AI
                                          </b-button>
                                          <b-button
                                            v-if="$role.isSuper()"
                                            variant="primary"
                                            size="sm"
                                            pill
                                            v-b-tooltip.hover
                                            @click="
                                              sendRekomendasiAI(
                                                sasaran_lv6.id_lv6
                                              )
                                            "
                                          >
                                            Rekomendasi AI
                                          </b-button>
                                        </div>
                                        <div
                                          v-if="sasaran_lv6.label_ai_lv6"
                                          class="d-flex align-items-baseline w-100"
                                        >
                                          <i class="fa fa-microchip mr-2"></i>
                                          <div>
                                            <small
                                              >Hubungan Kinerja Berdasarkan AI
                                              (Skor Keyakinan AI)</small
                                            >
                                            <h6
                                              class="m-0 mb-1 sasaran-title font-wight-bold"
                                              style="word-wrap: anywhere"
                                            >
                                              {{ sasaran_lv6.label_ai_lv6 }}
                                              {{
                                                (
                                                  sasaran_lv6.score_ai_lv6 * 100
                                                ).toFixed(0)
                                              }}%
                                            </h6>
                                          </div>
                                        </div>
                                      </span>
                                      <ul
                                        v-if="
                                          sasaran_lv6.children &&
                                          sasaran_lv6.children.length > 0
                                        "
                                      >
                                        <li
                                          v-for="sasaran_lv7 of sasaran_lv6.children"
                                          :key="sasaran_lv7.id"
                                        >
                                          <span class="text-left level7-box">
                                            <b-button
                                              v-if="
                                                sasaran_lv7.is_ai_rekomendasi_lv7
                                              "
                                              variant="light"
                                              class="mb-2"
                                              size="sm"
                                              pill
                                              v-b-tooltip.hover
                                            >
                                              Sasaran Rekomandasi AI
                                            </b-button>
                                            <div
                                              class="d-flex justify-content-between"
                                            >
                                              <b-button
                                                class="mr-2"
                                                variant="primary"
                                                size="sm"
                                                pill
                                                v-b-tooltip.hover
                                                v-if="
                                                  sasaran_lv7.is_crosscutting_lv7
                                                "
                                              >
                                                CC
                                              </b-button>
                                              <b-button
                                                variant="primary"
                                                size="sm"
                                                pill
                                                v-b-tooltip.hover
                                                title="Why"
                                                @click="
                                                  $nuxt.$emit(
                                                    'show-modal-why-pohon-kinerja',
                                                    sasaran_lv7.sasaran_lv7,
                                                    sasaran_lv7.id_lv7
                                                  )
                                                "
                                              >
                                                W
                                              </b-button>
                                            </div>

                                            <div class="text-right w-100"></div>
                                            <div
                                              class="d-flex align-items-baseline w-100"
                                            >
                                              <i
                                                class="fa fa-bullseye mr-2"
                                              ></i>
                                              <div>
                                                <small>Kinerja Level 7</small>
                                                <h6
                                                  class="m-0 mb-1 sasaran-title font-wight-bold"
                                                  style="word-wrap: anywhere"
                                                >
                                                  {{ sasaran_lv7.sasaran_lv7 }}
                                                </h6>
                                              </div>
                                            </div>
                                            <div
                                              class="d-flex align-items-baseline w-100"
                                            >
                                              <i
                                                class="fa fa-tachometer mr-2"
                                              ></i>
                                              <div>
                                                <small>Indikator Level 7</small>
                                                <h6
                                                  class="m-0 mb-1 sasaran-title font-wight-bold"
                                                  style="word-wrap: anywhere"
                                                >
                                                  {{
                                                    sasaran_lv7.indikator_lv7
                                                  }}
                                                </h6>
                                              </div>
                                            </div>
                                            <hr
                                              class="my-2"
                                              style="
                                                border-color: white;
                                                width: 100%;
                                              "
                                            />
                                            <div
                                              class="d-flex justify-content-between"
                                            >
                                              <b-button
                                                v-if="$role.isSuper()"
                                                class="mr-2"
                                                variant="primary"
                                                size="sm"
                                                pill
                                                v-b-tooltip.hover
                                                @click="
                                                  sendKorelasiAI(
                                                    sasaran_lv7.id_lv7
                                                  )
                                                "
                                              >
                                                Korelasi AI
                                              </b-button>
                                              <b-button
                                                v-if="$role.isSuper()"
                                                variant="primary"
                                                size="sm"
                                                pill
                                                v-b-tooltip.hover
                                                @click="
                                                  sendRekomendasiAI(
                                                    sasaran_lv7.id_lv7
                                                  )
                                                "
                                              >
                                                Rekomendasi AI
                                              </b-button>
                                            </div>
                                            <div
                                              v-if="sasaran_lv7.label_ai_lv7"
                                              class="d-flex align-items-baseline w-100"
                                            >
                                              <i
                                                class="fa fa-microchip mr-2"
                                              ></i>
                                              <div>
                                                <small
                                                  >Hubungan Kinerja Berdasarkan
                                                  AI (Skor Keyakinan AI)</small
                                                >
                                                <h6
                                                  class="m-0 mb-1 sasaran-title font-wight-bold"
                                                  style="word-wrap: anywhere"
                                                >
                                                  {{ sasaran_lv7.label_ai_lv7 }}
                                                  {{
                                                    (
                                                      sasaran_lv7.score_ai_lv7 *
                                                      100
                                                    ).toFixed(0)
                                                  }}%
                                                </h6>
                                              </div>
                                            </div>
                                          </span>
                                          <ul
                                            v-if="
                                              sasaran_lv7.children &&
                                              sasaran_lv7.children.length > 0
                                            "
                                          >
                                            <li
                                              v-for="sasaran_lv8 of sasaran_lv7.children"
                                              :key="sasaran_lv8.id"
                                            >
                                              <span
                                                class="text-left level8-box"
                                              >
                                                <b-button
                                                  v-if="
                                                    sasaran_lv8.is_ai_rekomendasi_lv8
                                                  "
                                                  variant="light"
                                                  class="mb-2"
                                                  size="sm"
                                                  pill
                                                  v-b-tooltip.hover
                                                >
                                                  Sasaran Rekomandasi AI
                                                </b-button>
                                                <div
                                                  class="d-flex justify-content-between"
                                                >
                                                  <b-button
                                                    class="mr-2"
                                                    variant="primary"
                                                    size="sm"
                                                    pill
                                                    v-b-tooltip.hover
                                                    v-if="
                                                      sasaran_lv8.is_crosscutting_lv8
                                                    "
                                                  >
                                                    CC
                                                  </b-button>
                                                  <b-button
                                                    variant="primary"
                                                    size="sm"
                                                    pill
                                                    v-b-tooltip.hover
                                                    title="Why"
                                                    @click="
                                                      $nuxt.$emit(
                                                        'show-modal-why-pohon-kinerja',
                                                        sasaran_lv8.sasaran_lv8,
                                                        sasaran_lv8.id_lv8
                                                      )
                                                    "
                                                  >
                                                    W
                                                  </b-button>
                                                </div>

                                                <div
                                                  class="text-right w-100"
                                                ></div>
                                                <div
                                                  class="d-flex align-items-baseline w-100"
                                                >
                                                  <i
                                                    class="fa fa-bullseye mr-2"
                                                  ></i>
                                                  <div>
                                                    <small
                                                      >Kinerja Level 8</small
                                                    >
                                                    <h6
                                                      class="m-0 mb-1 sasaran-title font-wight-bold"
                                                      style="
                                                        word-wrap: anywhere;
                                                      "
                                                    >
                                                      {{
                                                        sasaran_lv8.sasaran_lv8
                                                      }}
                                                    </h6>
                                                  </div>
                                                </div>
                                                <div
                                                  class="d-flex align-items-baseline w-100"
                                                >
                                                  <i
                                                    class="fa fa-tachometer mr-2"
                                                  ></i>
                                                  <div>
                                                    <small
                                                      >Indikator Level 8</small
                                                    >
                                                    <h6
                                                      class="m-0 mb-1 sasaran-title font-wight-bold"
                                                      style="
                                                        word-wrap: anywhere;
                                                      "
                                                    >
                                                      {{
                                                        sasaran_lv8.indikator_lv8
                                                      }}
                                                    </h6>
                                                  </div>
                                                </div>
                                                <hr
                                                  class="my-2"
                                                  style="
                                                    border-color: white;
                                                    width: 100%;
                                                  "
                                                />
                                                <div
                                                  class="d-flex justify-content-between"
                                                >
                                                  <b-button
                                                    v-if="$role.isSuper()"
                                                    class="mr-2"
                                                    variant="primary"
                                                    size="sm"
                                                    pill
                                                    v-b-tooltip.hover
                                                    @click="
                                                      sendKorelasiAI(
                                                        sasaran_lv8.id_lv8
                                                      )
                                                    "
                                                  >
                                                    Korelasi AI
                                                  </b-button>
                                                  <b-button
                                                    v-if="$role.isSuper()"
                                                    variant="primary"
                                                    size="sm"
                                                    pill
                                                    v-b-tooltip.hover
                                                    @click="
                                                      sendRekomendasiAI(
                                                        sasaran_lv8.id_lv8
                                                      )
                                                    "
                                                  >
                                                    Rekomendasi AI
                                                  </b-button>
                                                </div>
                                                <div
                                                  v-if="
                                                    sasaran_lv8.label_ai_lv8
                                                  "
                                                  class="d-flex align-items-baseline w-100"
                                                >
                                                  <i
                                                    class="fa fa-microchip mr-2"
                                                  ></i>
                                                  <div>
                                                    <small
                                                      >Hubungan Kinerja
                                                      Berdasarkan AI (Skor
                                                      Keyakinan AI)</small
                                                    >
                                                    <h6
                                                      class="m-0 mb-1 sasaran-title font-wight-bold"
                                                      style="
                                                        word-wrap: anywhere;
                                                      "
                                                    >
                                                      {{
                                                        sasaran_lv8.label_ai_lv8
                                                      }}
                                                      {{
                                                        (
                                                          sasaran_lv8.score_ai_lv8 *
                                                          100
                                                        ).toFixed(0)
                                                      }}%
                                                    </h6>
                                                  </div>
                                                </div>
                                              </span>
                                            </li>
                                          </ul>
                                        </li>
                                      </ul>
                                    </li>
                                  </ul>
                                </li>
                              </ul>
                            </li>
                          </ul>
                        </li>
                      </ul>
                    </li>
                  </ul>
                </li>
              </ul>
            </div>
          </div>
        </HandyScroll>
      </div>
      <ModalWhyPohonKinerja />
    </div>
  </b-card>
</template>
<style scoped>
.text-blue {
  color: #2f72ac;
}
.pos-r {
  position: relative;
}
.main-box {
  padding: 15px;
  border-radius: 15px;
  font-size: 15px;
  display: inline-block;
}
.level1-box {
  background-color: #dce775;
  color: black;
}
.level2-box {
  background-color: #aed581;
  color: black;
}
.level3-box {
  background-color: #8bc34a;
  color: white;
}
.level4-box {
  background-color: #558b2f;
  color: white;
}
.level5-box {
  background-color: #827717;

  color: white;
}
.level6-box {
  background-color: #a1887f;
  color: black;
}
.level7-box {
  background-color: #795548;
  color: white;
}

.level8-box {
  background-color: #5d4037;
  color: white;
}

.kinerja-bayangan-container {
  background-color: #ff7979;
  color: black;
}
.w-100 {
  width: 100%;
}
.tree,
.tree ul,
.tree li {
  list-style: none;
  margin: 0;
  padding: 0;
  position: relative;
}

.tree {
  margin: 0 0 1em;
  text-align: center;
}
/* .tree, .tree ul {
    display: table;
  } */
.tree ul {
  width: 100%;
}
.tree li {
  display: table-cell;
  padding: 20px 0 0;
  vertical-align: top;
}
/* _________ */
.tree li:before {
  outline: solid 1px #aaa;
  content: "";
  left: 0;
  position: absolute;
  right: 0;
  top: 0;
}
.tree li:first-child:before {
  left: 50%;
}
.tree li:last-child:before {
  right: 50%;
}

.tree > li > code,
.tree > li > span {
  border: none !important;
  border-radius: 0.5em;
  display: inline-block;
  margin: 0 0.5em 20px;
  padding: 0.5em 20px;
  position: relative;
  max-width: 350px;
}
.tree code,
.tree span:not(.d-none) {
  /* border: solid .1em #aaa; */
  border-radius: 0.5em;
  display: inline-block;
  margin: 0 0.5em 20px;
  padding: 0.5em 10px;
  position: relative;
  max-width: 350px;
  min-height: 300px;
  min-width: 300px;
  display: inline-flex !important;
  flex-direction: column;
  align-items: center;
  /* justify-content: center; */
}

/* | */
.tree ul:before,
.tree code:before,
.tree span:before {
  outline: solid 1px #aaa;
  content: "";
  height: 20px;
  left: 50%;
  position: absolute;
}

.tree .cross span:before {
  outline: black dashed medium;
  content: "";
  height: 20px;
  left: 50%;
  position: absolute;
}
.tree ul:before {
  top: -20px;
}
.tree code:before,
.tree span:before {
  top: -21px;
}

/* The root node doesn't connect upwards */
.tree > li {
  margin-top: 0;
  padding-top: 0;
}
.tree > li:before,
.tree > li:after,
.tree > li > code:before,
.tree > li > span:before {
  outline: none;
}
.lightblue-box {
  background-color: #21a2dc;
  color: white;
  min-width: 200px;
}

.orange-box {
  background-color: #b17117;
  color: white;
  min-width: 200px;
}

.col {
  flex-basis: 0;
  flex-grow: 1;
  max-width: 100%;
}
.text-italic {
  font-style: italic;
}
.green-box .indikator {
  color: #cdff00;
}
.yellow-box .indikator {
  color: #0021c1;
  text-shadow: 0 0 15px white;
}
.blue-box .indikator {
  color: #d6e228;
}
/* .lightblue-box .indikator{
    color: #000000;
    text-shadow: 0 0 15px white;
  } */
.sasaran-title {
  font-size: 15px;
}
.indikator {
  font-size: 15px;
}
.maximize-btn {
  position: absolute;
  right: 0;
  top: 0;
  z-index: 4;
}
.minimize-btn {
  position: fixed;
  right: 0;
  top: 0;
  margin: 15px;
}
.nama-kegiatan {
  /* color: #000; */
  font-size: 11px;
}
.pengampu {
  /* color: #000; */
  font-size: 11px;
}

/*CUSTOM VARIABLES HERE*/

.level-5-wrapper {
  position: relative;
  width: 80%;
  margin-left: auto;
  padding: 0;
  padding-right: 8px;
  margin-right: -4px;
}

.level-5-wrapper::before {
  content: "";
  position: absolute;
  top: -20px;
  left: -20px;
  width: 2px;
  height: calc(100% + 20px);
  background: #aaa;
}

.tree .level-5-wrapper li {
  display: block;
  padding: 0;
}
.tree .level-5-wrapper li::before {
  outline: none;
}
.level-5-wrapper li + li {
  margin-top: 20px;
}

.level-5 {
  font-weight: normal;
  background: rgb(162, 162, 162);
  padding: 5px;
  border-radius: 5px;
}

.level-5::before {
  content: "";
  position: absolute;
  top: 50%;
  left: 0%;
  transform: translate(-100%, -50%);
  width: 20px;
  height: 2px;
  background: #aaa;
}
.cross .level-5::before {
  border-bottom: thick dashed black;
  background: white;
}
.level-5 .sasaran-title {
  font-size: 12px;
  /* color:black; */
  word-wrap: anywhere;
}
.level-5 .indikator {
  font-size: 11px;
  /* color:rgb(0, 38, 255); */
  /* text-shadow: none; */
}

.tree span.kinerjaKegiatan,
.tree span.kinerjaProgram,
.tree span.satkerSasaran {
  justify-content: unset;
}

/* border untuk kotak yang menjadi cross cutting di parentnya */
.cross > .kinerjaProgram,
.cross > .kinerjaKegiatan,
.cross > .level-5 {
  border: 10px dashed yellow;
}
.cross > .satkerSasaran {
  border: 10px dashed black;
}

/**
    * border untuk kotak yang tidak menjadi cross cutting di parentnya
    * tapi menjadi cross cutting di parent lain dalam satu opd
   */
.cross-exists > .satkerSasaran,
.cross-exists > .kinerjaProgram,
.cross-exists > .kinerjaKegiatan,
.cross-exists > .level-5 {
  border: thick solid black;
}

.highlight {
  animation: blink 4s infinite;
}

@keyframes blink {
  0% {
    border: 8px solid #ff0000 !important;
    box-shadow: 0 0 80px #ff0000, inset 0 0 40px rgba(255, 0, 0, 0.5);
    background-color: #ff0000 !important;
    transform: scale(1.05);
    z-index: 9999 !important;
  }
  25% {
    border: 8px solid #ff0000 !important;
    box-shadow: 0 0 100px#ff0000, inset 0 0 40px rgba(255, 0, 0, 0.5);
    background-color: #ff0000 !important;
    transform: scale(1.1);
  }
  50% {
    border: 8px solid #ff0000 !important;
    box-shadow: 0 0 120px#ff0000, inset 0 0 40px rgba(255, 0, 0, 0.5);
    background-color: #ff0000 !important;
    transform: scale(1.05);
  }
  75% {
    border: 8px solid #ff0000 !important;
    box-shadow: 0 0 100px #ff0000, inset 0 0 40px rgba(255, 0, 0, 0.5);
    background-color: #ff0000 !important;
    transform: scale(1.1);
  }
  100% {
    border: 8px solid #ff0000 !important;
    box-shadow: 0 0 80px #ff0000, inset 0 0 40px rgba(255, 0, 0, 0.5);
    background-color: #ff0000 !important;
    transform: scale(1.05);
    z-index: 9999 !important;
  }
}
</style>
