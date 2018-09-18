<template>
  <li>
    <span @click="folded=(!folded)" class="folder">
      <i v-if="folded" class="el-icon-plus"></i>
      <i class="el-icon-minus" v-else></i>
      <slot></slot>
    </span>

    <template v-if="!folded">
      <slot name='items'></slot>
    </template>
  </li>
</template>

<script>
export default {
  name: 'Folder',

  data () {
    return {
    }
  },

  props: {
    folded: {
      default: true
    }
  }
}
</script>

<style scoped>

.folder {
  cursor: pointer;
}

</style>
