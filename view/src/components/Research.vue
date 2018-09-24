<template>
<div style="position:absolute;top:160px;bottom:50px">
  <div style="padding: 5px; width: 800px; height: 41px">
    <div style="margin-right: 5px; height: 30px" align="right">
      <img src="../assets/research.gif" style="width:300px; height:30px" />
    </div>
  </div>
    <!--
    <iframe ref="frame" style="width:800px" src="https://docs.google.com/document/d/e/2PACX-1vSkcgDSUJqQTPuskj-GhPdeuD4xr-Y-MZuuIDKa32MsJ-1m0Hdhr53ybL9-Co7RP-n_JsK8R7R0EcIr/pub?embedded=true" frameborder="0" scrolling="no" @load="resizeIframe()" ></iframe>
    -->
  <iframe ref="frame" style="width:800px;height:100%" :src="'http://jianopt.com/dokhlab/actions/proxy.php?src='+frontConfig.research.src" frameborder="0" @load="resizeIframe()" ></iframe>
  <!--
  <div v-html="content"></div>
  -->
</div>
</template>

<script>
import { bus } from '../bus.js'
import axios from 'axios'

export default {
  name: 'Research',

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
        url: v.$config.HOST + '/dokhlab/actions/content.php?name=research'
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
      bus.$emit('switch-router', 'Research')

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
