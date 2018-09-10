<template>
  <div :class="{'more-dropdown': true, left: placement.includes('left'), right: placement.includes('right'), top: placement.includes('top'), bottom: placement.includes('bottom')}">
    <slot></slot>
  </div>
</template>

<script>
export default {
  name: 'MoreDropdown',

  data () {
    return {
    }
  },

  props: ['placement'],

  methods: {
  }
}
</script>

<style>

</style>
