<template>
<div>
  <div style="padding: 5px; width: 800px; height: 41px">
    <div style="margin-right: 5px; height: 30px" align="right">
      <img src="../assets/research.gif" style="width:300px; height:30px" />
    </div>
  </div>
  <div></div>
</div>
</template>

<script>
import { bus } from '../bus.js'

export default {
  name: 'Home',
  data () {
    return {
    }
  },
  methods: {
  },
  mounted () {
    this.$nextTick(function () {
      bus.$emit('switch-router', 'Home')
    })
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

.introduction {
  padding: 10px 30px;
  color: #5a5e66;
  font-size: 14px;
  text-align: justify;
}

img {
  width: 50%;
}

</style>
