<script>
export default {
  props: {
    value: {
      default: null,
    },
  },

  data() {
    return {
      options: [
        { value: 1, text: 'I' },
        { value: 2, text: 'II' },
        { value: 3, text: 'III' },
        { value: 4, text: 'IV' },
      ]
    }
  }
}
</script>

<template>
  <b-form-group label="Triwulan" label-class="font-weight-bold">
    <b-form-select @input="(value) => $emit('input', value)" :value="value" :options="options"></b-form-select>
  </b-form-group>
</template>