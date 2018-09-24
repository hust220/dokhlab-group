<template>
<div>
  <div style="padding: 5px; width: 800px; height: 41px">
    <div style="margin-right: 5px; height: 30px" align="right">
      <img src="../assets/home.gif" style="width:300px; height:30px" />
    </div>
  </div>

  <!--
  <iframe style="overflow:visible;width:800px;min-height:1000px;border:none" src="https://docs.google.com/document/d/e/2PACX-1vQhNi9UyjZrzWSnWXEpzKwFIANESYx7gm9Cm1_YZafa_WB8XgjMqvuIC4IO69UbcCQLNttCeXsnqsU1/pub?embedded=true"></iframe>
  -->
  <iframe style="overflow:visible;width:800px;min-height:1000px;border:none" :src="'http://jianopt.com/dokhlab/actions/proxy.php?src='+frontConfig.home.src"></iframe>
  <!--
  <div v-html="content"></div>
  -->
</div>
</template>

<script>
import { bus } from '../bus.js'
import axios from 'axios'

export default {
  name: 'Home',

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
    getContent () {
      var v = this

      axios({
        method: 'get',
        url: v.$config.HOST + '/dokhlab/actions/content.php?name=home'
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
      bus.$emit('switch-router', 'Home')

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
