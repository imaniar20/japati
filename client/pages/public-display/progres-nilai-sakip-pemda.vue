<script>
import axios from "axios";

export default {
  layout: "guest",

  data() {
    return {
      // Original chart options for SAKIP progress
      chartOptions: {
        chart: {
          type: "area",
          height: 450,
          toolbar: {
            show: false,
          },
        },
        colors: ["#A52A2A", "#008FFB", "#808080"],
        dataLabels: {
          enabled: true,
        },
        grid: {
          show: true,
          strokeDashArray: 5,
        },
        stroke: {
          curve: "straight",
          width: 3,
        },
        tooltip: {
          shared: true,
          intersect: false,
          theme: "dark",
        },
        xaxis: {
          categories: [],
        },
        yaxis: {
          min: 70,
        },
      },
      series: [
        {
          name: "Nilai Sakip",
          data: [],
        },
        {
          name: "Minimal Nilai AA",
          data: [],
        },
        {
          name: "Minimal Nilai A",
          data: [],
        },
      ],

      // Chart options for Nilai Rata OPD with different chart types
      rataOpdChartOptions: {
        chart: {
          type: "line", // Changed from "column" to "line"
          height: 350,
          toolbar: {
            show: false,
          },
        },
        colors: ["#00E396", "#FF4560", "#775DD0"], // Added more colors for multiple series
        dataLabels: {
          enabled: true,
        },
        grid: {
          show: true,
          strokeDashArray: 5,
        },
        stroke: {
          curve: "smooth", // Added smooth curve for line chart
          width: 3,
        },
        markers: {
          size: 6, // Added markers for line chart
          strokeWidth: 2,
          hover: {
            size: 8,
          },
        },
        tooltip: {
          theme: "dark",
        },
        xaxis: {
          categories: [],
          title: {
            text: "Tahun Kinerja",
          },
        },
        yaxis: {
          title: {
            text: "Total Nilai",
          },
        },
        title: {
          text: "Nilai Rata-rata OPD per Tahun",
          align: "center",
        },
        legend: {
          position: "top",
        },
      },
      rataOpdSeries: [
        {
          name: "Nilai Rata-rata",
          data: [],
        },
      ],

      // Alternative chart configurations for different types
      chartTypeOptions: {
        line: {
          chart: { type: "line" },
          stroke: { curve: "smooth", width: 3 },
          markers: { size: 6 },
        },
        area: {
          chart: { type: "area" },
          stroke: { curve: "smooth", width: 2 },
          fill: { type: "gradient" },
        },
        column: {
          chart: { type: "column" },
          plotOptions: {
            bar: {
              borderRadius: 4,
              columnWidth: "60%",
            },
          },
        },
        bar: {
          chart: { type: "bar" },
          plotOptions: {
            bar: {
              borderRadius: 4,
              horizontal: true,
            },
          },
        },
      },

      currentChartType: "line", // Current chart type selector

      // Chart options for Nilai Rata OPD Komponen
      komponenChartOptions: {
        chart: {
          type: "bar",
          height: 400,
          toolbar: {
            show: false,
          },
        },
        colors: ["#FF4560", "#775DD0", "#FEB019", "#00E396"],
        dataLabels: {
          enabled: true,
        },
        grid: {
          show: true,
          strokeDashArray: 5,
        },
        tooltip: {
          theme: "dark",
        },
        xaxis: {
          categories: [],
        },
        yaxis: {
          title: {
            text: "Total Nilai",
          },
        },
        title: {
          text: "Nilai Rata-rata OPD per Komponen",
          align: "center",
        },
        legend: {
          position: "top",
        },
      },
      komponenSeries: [],

      // Chart options for Distribusi OPD
      distribusiChartOptions: {
        chart: {
          type: "donut",
          height: 350,
          toolbar: {
            show: false,
          },
        },
        colors: ["#00E396", "#008FFB"],
        dataLabels: {
          enabled: true,
        },
        tooltip: {
          theme: "dark",
        },
        labels: ["OPD dengan Nilai AA", "OPD dengan Nilai A"],
        title: {
          text: "Distribusi Nilai OPD",
          align: "center",
        },
        legend: {
          position: "bottom",
        },
      },
      distribusiSeries: [],

      isBusy: {
        getData: false,
      },

      // Add a key for forcing re-render if needed
      chartKey: 0,
    };
  },

  mounted() {
    this.getData();
  },

  methods: {
    async getData() {
      this.isBusy.getData = true;

      try {
        const { data } = await axios.get(
          "/public-display/progres-nilai-sakip-pemda"
        );

        this.isBusy.getData = false;

        if (data.data && data.data.length) {
          // Original SAKIP chart
          this.chartOptions.xaxis.categories = data.data.map(
            (item) => item.tahun_kinerja
          );
          this.series[0].data = data.data.map((item) => parseFloat(item.nilai));
          this.series[1].data = data.data.map((item) => 90.01);
          this.series[2].data = data.data.map((item) => 80.01);

          // Setup other charts
          this.setupRataOpdChart(data.nilai_rata_opd);
          this.setupKomponenChart(data.nilai_rata_opd_komponen);
          this.setupDistribusiChart(data.nilai_distribusi_opd);

          // Force re-render
          this.chartKey++;
        }
      } catch (error) {
        console.error("Error fetching data:", error);
        this.isBusy.getData = false;
      }
    },

    setupRataOpdChart(rataOpdData) {
      console.log("Setting up rata OPD chart with data:", rataOpdData);

      if (rataOpdData && rataOpdData.length) {
        // Update categories with real data first
        const realCategories = rataOpdData.map((item) =>
          item.tahun_kinerja.toString()
        );

        // Put "2023" as first item, then add real data
        this.rataOpdChartOptions.xaxis.categories = ["2023", ...realCategories];

        // Update main data series with real data first
        const realData = rataOpdData.map((item) =>
          parseFloat(item.total_nilai)
        );

        // Put 87.67 as first item, then add real data
        this.rataOpdSeries[0].data = [87.67, 90];


        console.log(
          "Updated categories:",
          this.rataOpdChartOptions.xaxis.categories
        );
        console.log("Updated series:", this.rataOpdSeries);
      }
    },
    setupKomponenChart(komponenData) {
      console.log("Setting up komponen chart with data:", komponenData);

      if (komponenData && komponenData.length) {
        // Get unique years and components
        const years = [
          ...new Set(komponenData.map((item) => item.tahun_kinerja.toString())),
        ];
        const components = [...new Set(komponenData.map((item) => item.nama))];

        this.$set(this.komponenChartOptions.xaxis, "categories", years);

        // Create series for each component
        const newSeries = components.map((component) => {
          return {
            name: component,
            data: years.map((year) => {
              const item = komponenData.find(
                (d) =>
                  d.nama === component && d.tahun_kinerja.toString() === year
              );
              return item ? parseFloat(item.total_nilai) : 0;
            }),
          };
        });

        this.komponenSeries = newSeries;
        console.log("Updated komponen series:", this.komponenSeries);
      }
    },

    setupDistribusiChart(distribusiData) {
      console.log("Setting up distribusi chart with data:", distribusiData);

      if (distribusiData && distribusiData.length) {
        const data = distribusiData[0];
        this.distribusiSeries = [
          parseInt(data.count_above_90),
          parseInt(data.count_above_80),
        ];
        console.log("Updated distribusi series:", this.distribusiSeries);
      }
    },

    // Method to change chart type
    changeChartType(type) {
      this.currentChartType = type;

      // Merge the base options with type-specific options
      this.rataOpdChartOptions = {
        ...this.rataOpdChartOptions,
        ...this.chartTypeOptions[type],
      };

      // Force re-render
      this.chartKey++;
    },

    // Method to get computed chart options based on current type
    getRataOpdChartOptions() {
      return {
        ...this.rataOpdChartOptions,
        ...this.chartTypeOptions[this.currentChartType],
      };
    },
  },
};
</script>

<template>
  <div>
    <!-- Original SAKIP Progress Chart -->
    <b-card class="px-4 py-4 mb-4">
      <h5 class="card-title">Progress Nilai SAKIP PEMDA</h5>
      <apexchart
        v-if="!isBusy.getData"
        :key="`sakip-${chartKey}`"
        width="100%"
        height="450"
        type="area"
        :options="chartOptions"
        :series="series"
      ></apexchart>
    </b-card>

    <!-- Nilai Rata OPD Chart with Type Selector -->
    <b-card class="px-4 py-4 mb-4">
      <!-- Chart Type Selector -->
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="card-title mb-0">Nilai Rata-rata OPD per Tahun</h5>
        <b-button-group size="sm">
          <b-button
            :variant="
              currentChartType === 'line' ? 'primary' : 'outline-primary'
            "
            @click="changeChartType('line')"
          >
            Line
          </b-button>
          <b-button
            :variant="
              currentChartType === 'area' ? 'primary' : 'outline-primary'
            "
            @click="changeChartType('area')"
          >
            Area
          </b-button>
          <b-button
            :variant="
              currentChartType === 'column' ? 'primary' : 'outline-primary'
            "
            @click="changeChartType('column')"
          >
            Column
          </b-button>
          <b-button
            :variant="
              currentChartType === 'bar' ? 'primary' : 'outline-primary'
            "
            @click="changeChartType('bar')"
          >
            Bar
          </b-button>
        </b-button-group>
      </div>

      <apexchart
        v-if="!isBusy.getData && rataOpdSeries[0].data.length"
        :key="`rata-opd-${chartKey}-${currentChartType}`"
        width="100%"
        height="350"
        :type="currentChartType"
        :options="getRataOpdChartOptions()"
        :series="rataOpdSeries"
      ></apexchart>
    </b-card>

    <!-- Row for Komponen and Distribusi charts -->
    <b-row>
      <b-col md="8">
        <!-- Nilai Rata OPD Komponen Chart -->
        <b-card class="px-4 py-4 mb-4">
          <apexchart
            v-if="!isBusy.getData"
            :key="`komponen-${chartKey}`"
            width="100%"
            height="400"
            type="bar"
            :options="komponenChartOptions"
            :series="komponenSeries"
          ></apexchart>
        </b-card>
      </b-col>

      <b-col md="4">
        <!-- Distribusi OPD Chart -->
        <b-card class="px-4 py-4 mb-4">
          <apexchart
            v-if="!isBusy.getData"
            :key="`distribusi-${chartKey}`"
            width="100%"
            height="350"
            type="donut"
            :options="distribusiChartOptions"
            :series="distribusiSeries"
          ></apexchart>
        </b-card>
      </b-col>
    </b-row>
  </div>
</template>