<script>
export default {
  name: 'FilterStatusValidasi',
  props: {
    id: {
      type: String,
      default: 'filter-status-validasi',
    },
    labelTitle: {
      type: String,
      default: 'Filter Status validasi',
    },
    value: {
      default: null,
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
      <b-form-select-option :value="null">
        Semua
      </b-form-select-option>
      <b-form-select-option :value="1">
        Tervalidasi
      </b-form-select-option>
      <b-form-select-option :value="0">
        Belum Tervalidasi
      </b-form-select-option>
    </b-form-select>
  </b-form-group>
</template>