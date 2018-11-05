<template>
  <a class="more-menu-item" :href="href" v-if="href">
    <slot></slot>
  </a>
  <div class="more-menu-item" v-else>
    <slot></slot>
  </div>
</template>

<script>
export default {
  name: 'MoreMenuitem',

  props: ['href'],

  data () {
    return {
    }
  },

  methods: {
  }
}
</script>

<style>

</style>
