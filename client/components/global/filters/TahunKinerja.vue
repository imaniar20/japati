<script>
export default {
  name: 'FilterTahunKinerja',
  props: {
    id: {
      type: String,
      default: 'filter-tahun-kinerja',
    },
    labelTitle: {
      type: String,
      default: 'Filter Tahun Kinerja',
    },
    value: {
      default: null,
    },
    /**
     * tampilkan semua opsi tahun kinerja dari semua tahun mulai $const.tahun_mulai_list
     */
    allTahun: {
      type: Boolean,
      default: true,
    },
    /**
     * jika allTahun = true maka props tahunMulai ini diabaikan
     * jika props allTahun = false maka opsi tahun kinerja berdasarkan props tahunMulai
     */
    tahunMulai: {
      type: Number,
      default: function () {
        return this.$helper.getTahunMulai()
      },
    },
    groupProps: {
      type: Object,
      default: () => {
        return {}
      }
    },
    selectProps: {
      type: Object,
      default: () => {
        return {}
      }
    },
  },

  data() {
    return {
      defaultGroupProps: {
        'label-class': 'font-weight-bold',
      },
    }
  },

  computed: {
    options() {
      const tahunMulai = this.allTahun
        ? this.$const.tahun_mulai_list
        : [this.tahunMulai]

      const list = []

      tahunMulai.forEach(tahun => {
        for (let i = 0; i < 5; i++) {
          list.push({
            value: tahun + i,
            text: `Tahun ${tahun + i}`
          })
        }
      });

      return list
    }
  },
}
</script>

<template>
  <b-form-group v-bind="{...defaultGroupProps, ...groupProps}" :label="labelTitle" :label-for="id">
    <b-form-select 
      :id="id" 
      :value="value"
      @input="(value) => $emit('input', value)"
      v-bind="selectProps"
    >
      <b-form-select-option v-for="item in options" :key="item.value" :value="item.value">
        {{ item.text }}
      </b-form-select-option>
    </b-form-select>
  </b-form-group>
</template>