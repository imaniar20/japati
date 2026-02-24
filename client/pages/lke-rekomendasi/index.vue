<script>
import axios from "axios";
import { destroy as doDestroy } from "~/plugins/swal";
import ValidasiSkp from "~/components/ValidasiSkp.vue";
import { mapGetters } from "vuex";
import { arrayChunk2 } from "~/plugins/utils";
export default {
  middleware: ["auth"],

  components: {
    ValidasiSkp,
  },

  async asyncData({ $role }) {
    let data = {};
    try {
      if (!$role.isValidatorLKE() && !$role.isValidatorPleno() && !$role.isPerangkatDaerah()) {
        const response = await $axios.get("/lke-rekomendasi");
        data = response.data;
      }
    } catch (error) {
      console.error("Error fetching data:", error);
    }

    return {
      data,
    };
  },

  data() {
    return {
      table: {
        fields: [
          { key: "no", label: "No" },
          { key: "rekomendasi", label: "Rekomendasi" },
          { key: "tindak_lanjut", label: "Tindak Lanjut" },
          { key: "waktu", label: "Waktu" },
          { key: "penanggung_jawab", label: "Penanggung Jawab" },
          { key: "link_eviden", label: "Link Eviden" },
          { key: "status", label: "Status Tindak Lanjut" },
          { key: "status_monev", label: "Status Monev" },
          { key: 'action', label: 'Aksi' },
        ],
      },
      filter: {
        satuan_kerja_id: null,
        sasaran_strategis_id: null,
        indikator_sasaran_strategis_id: null,
        status_validasi: null,
      },
      isBusy: {
        doFilter: false,
        exportExcel: false,
      },
      filter_satuan_kerja_id: [],
    };
  },

  computed: {
    ...mapGetters({
      user: "auth/user",
    }),
    cardTitle() {
      const tahunKinerja = this.$helper.getTahunKinerja();
      return `Tindak Lanjut LHE ${tahunKinerja}`;
    },
  },

  watch: {
    filter: {
      handler: function () {
        this.doFilter();
      },
      deep: true,
    },
  },

  created() {
    /** prepend field Satuan Kerja jika akun super atau setda */
    if (this.$role.isSuper() || this.$role.isSetda()) {
      this.table.fields.splice(1, 0, {
        key: "satuan_kerja",
        label: "Satuan Kerja",
      });
    } else if (this.$role.isValidatorLKE() || this.$role.isValidatorPleno() ) {
      this.filter.satuan_kerja_id = null;
    } else {
      this.filter.satuan_kerja_id = this.user.satuan_kerja_id;
    }
  },

  methods: {
    arrayChunk2,
    destroy(index, id) {
      doDestroy({
        preConfirm: async () => {
          return axios.delete(`/lke-rekomendasi/${id}`).then(() => {
            this.data.data.splice(index, 1);

            return true;
          });
        },
      });
    },
    async doFilter(page = 1) {
      this.isBusy.doFilter = true;
      if (this.filter.satuan_kerja_id) {
        this.filter_satuan_kerja_id = [];
        this.filter_satuan_kerja_id.push(this.filter.satuan_kerja_id);
      }
      const { data } = await axios.get("lke-rekomendasi", {
        params: {
          filter: this.filter,
          page,
        },
      });

      this.data = data;
      this.isBusy.doFilter = false;
    },
  },
};
</script>

<template>
  <b-card :title="cardTitle">
    <div>
      <div
        v-if="$role.isValidatorLKE() || $role.isValidatorPleno()"
        class="text-right"
      >
        <template>
          <nuxt-link to="/lke/rekomendasi/create" class="btn btn-primary">
            <i class="ti-plus" aria-hidden="true"></i> Tambah
          </nuxt-link>
        </template>
      </div> 

      <div
        v-if="$role.isPerangkatDaerah() && this.$helper.getTahunKinerja() == '2024'"
        class="text-right"
      >
        <template>
          <nuxt-link to="/lke/rekomendasi/create" class="btn btn-primary">
            <i class="ti-plus" aria-hidden="true"></i> Tambah
          </nuxt-link>
        </template>
      </div> 
      <b-row>
        <b-col v-if="$role.isSuper()" sm="6" md="4">
          <FilterSatuanKerja v-model="filter.satuan_kerja_id" />
        </b-col>
        <b-col
          v-if="$role.isValidatorLKE() || $role.isValidatorPleno()"
          sm="6"
          md="6"
        >
          <FilterSatuanKerja
            v-model="filter.satuan_kerja_id"
            :selectProps="{ clearable: false }"
            :satuanKerjaIds="user.lke_penilaian_satuan_kerja_ids"
          />
        </b-col>
        <!-- <b-col sm="6" md="4">
          <FilterKegiatan v-model="filter.kegiatan_id"  :satuan-kerja-id="filter_satuan_kerja_id"/>
        </b-col> -->
      </b-row>
    </div>

    <b-table
      responsive
      hover
      striped
      sticky-header="calc(100vh - 300px)"
      :fields="table.fields"
      :items="data.data"
      :busy="isBusy.doFilter"
      show-empty
      class="table-bordered mt-2"
      head-variant="info"
    >
      <template #cell(no)="row">
        <div class="text-center">
          {{ data.from + row.index }} <br />
        </div>
      </template>

      <template #cell(link_eviden)="row">
        <a :href="row.value" target="_blank">{{ row.value }}</a>
      </template>
      <template   v-if="$role.isValidatorLKE() || $role.isValidatorPleno()" #cell(satuan_kerja)="row">
        {{ row.value.satuan_kerja_nama }}
      </template>

      <template
        v-if="$role.isValidatorLKE() || $role.isValidatorPleno() || $role.isPerangkatDaerah()"
        #cell(action)="row"
      >
        <div class="text-nowrap">
          <nuxt-link
            :to="`/lke/rekomendasi/${row.item.id}/edit`"
            class="btn btn-outline-warning btn-sm m-1 rounded-circle"
            title="Edit Tindak Lanjut"
          >
            <i class="fa fa-pencil" aria-hidden="true"></i>
          </nuxt-link>
          <b-button   v-if="$role.isValidatorLKE() || $role.isValidatorPleno()"
            @click="destroy(row.index, row.item.id)"
            variant="outline-danger"
            size="sm"
            class="m-1 rounded-circle"
            title="Hapus Tindak Lanjut"
          >
            <i class="fa fa-trash" aria-hidden="true"></i>
          </b-button>
        </div>
      </template>
    </b-table>

    <div>
      <b-pagination
        v-model="data.current_page"
        :total-rows="data.total"
        :per-page="data.per_page"
        @change="doFilter($event)"
      >
        <template #page="{ page, active }">
          <i
            class="fa fa-spinner fa-pulse fa-fw"
            v-if="isBusy.doFilter && active"
          ></i>
          <template v-else>{{ page }}</template>
        </template>
      </b-pagination>
    </div>

    <ValidasiSkp ref="validasiSkp" @success="doFilter(data.current_page)" />
  </b-card>
</template>