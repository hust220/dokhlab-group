<template>
  <div>
    <div v-if="node===''"></div>

    <div v-if="Array.isArray(node)">
      <tree v-for="son in node" :content="son" :key="son"></tree>
    </div>

    <div v-else-if="node.sons===''">
      <div style="padding-left:18px">
        <slot :node="node" :path="root+node.name">
          {{node.name}}
        </slot>
      </div>
    </div>

    <div v-else>
      <folder>
        <slot :node="node" :path="root+node.name">
          {{node.name}}
        </slot>
        <div slot="items">
          <div style="padding-left:20px">
            <tree v-for="(son, ind) in node.sons" :root="root+node.name+'/'" :content="son" :key="ind">
              <template slot-scope="slotProps">
                <slot :node="slotProps.node" :path="slotProps.path"></slot>
              </template>
            </tree>
          </div>
        </div>
      </folder>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Tree',

  data () {
    return {
    }
  },

  computed: {
    node () {
      return this.content
    }
  },

  props: {
    root: {
      default: ''
    },
    content: {
    }
  }
}
</script>

<style scoped>

.folder {
  cursor: pointer;
}

</style>
