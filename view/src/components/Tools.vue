<template>
<div style="position:absolute;top:160px;bottom:50px">
    <!--
  <iframe ref="frame" style="width:800px;height:100%" :src="'http://jianopt.com/dokhlab/actions/proxy.php?src='+frontConfig.tools.src" frameborder="0" @load="resizeIframe()" ></iframe>
  -->
  <div v-html="$store.state.frontConfig.tools.src"></div>
</div>
</template>

<script>
import { bus } from '../bus.js'
import axios from 'axios'

export default {
  name: 'Tools',

  data () {
    return {
      content: ''
    }
  },

  computed: {
    frontConfig () {
      return this.$store.state.frontConfig
    }
  },

  methods: {
    resizeIframe (obj) {
      console.log(this.$refs.frame)
      this.$refs.frame.style.height = this.$refs.frame.contentWindow.document.body.scrollHeight + 'px'
    },

    getContent () {
      var v = this

      axios({
        method: 'get',
        url: v.$config.HOST + '/dokhlab/actions/content.php?name=tools'
      }).then(response => {
        v.content = response.data
      }).catch(() => {
        alert('Fetch content failed!')
      })
    }
  },

  mounted () {
    var v = this

    this.$nextTick(function () {
      bus.$emit('switch-router', 'Tools')

      v.getContent()
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
