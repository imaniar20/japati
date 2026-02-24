import Vue from 'vue'

const context = [
  require.context('./', false, /.*\.(vue)$/),
  require.context('./filters', false, /.*\.(vue)$/),
  require.context('./options', false, /.*\.(vue)$/),
]

context.forEach(requireContext => {
  requireContext.keys().forEach((file) => {
    const Component = requireContext(file).default
  
    if (Component.name) {
      Vue.component(Component.name, Component)
    }
  })
});
