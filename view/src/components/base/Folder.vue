<template>
  <div>
    <span @click="isFolded=(!isFolded)" class="folder">
      <i v-if="isFolded" class="el-icon-plus"></i>
      <i class="el-icon-minus" v-else></i>
      <slot></slot>
    </span>

    <template v-if="!isFolded">
      <slot name='items'></slot>
    </template>
  </div>
</template>

<script>
export default {
  name: 'Folder',

  data () {
    return {
      isFolded: this.folded
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
