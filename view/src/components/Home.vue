<template>
<div>
  <!-- Timeline -->
  <el-dialog title="Dokhlab" width="1250px" :visible.sync="timelineVisible">
    <div style="padding:0px;margin:0px;width:1220px;height:620px;overflow-x: auto">
      <!--
      <iframe src='https://cdn.knightlab.com/libs/timeline3/latest/embed/index.html?source=1VnrNiMk_QQC6ZDO3j8zjFDsWBwKM7bZRo_oOt4JbBc8&font=Default&lang=en&initial_zoom=2&width=1200&height=600' width='1200' height='600' webkitallowfullscreen mozallowfullscreen allowfullscreen frameborder='0'></iframe>
      -->
      <iframe :src="timeline.src" :width="timeline.width" :height="timeline.height" webkitallowfullscreen mozallowfullscreen allowfullscreen frameborder='0'></iframe>
    </div>
  </el-dialog>

  <div style="padding: 5px; width: 800px; height: 41px">
    <div style="margin-right: 5px; height: 30px" align="right">
      <img src="../assets/home.gif" style="width:300px; height:30px" />
    </div>
  </div>

  <!--
  <iframe style="overflow:visible;width:800px;min-height:1000px;border:none" :src="'http://jianopt.com/dokhlab/actions/proxy.php?src='+frontConfig.home.src"></iframe>
  -->
  <div style="width: 750px; padding: 5px 24px 5px 24px; text-align: justify; text-justify: inter-word;background: #EEEEEE; border: 1px solid #BBBBBB; font-size: 13px; font-family: Geneva, arial, helvetica, sans-serif;">

    <div style="margin:10px auto">
      <el-carousel>
        <el-carousel-item v-for="(img,ind) in $store.state.frontConfig.carousel" :key="ind">
          <div style="margin:0px auto; text-align:center;width:700px">
            <img style="height:300px;width:auto" :src="$config.HOST+'/dokhlab/actions/image.php?name='+img">
          </div>
        </el-carousel-item>
      </el-carousel>
    </div>

    <div>
      <a style="cursor:pointer" @click="timelineVisible=true">Read our history.</a>
    </div>

    <div style="margin-top:30px;min-height:700px">
      <div v-html="$store.state.frontConfig.home.src"></div>
    </div>
  </div>
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
      timelineVisible: false,
      content: ''
    }
  },

  computed: {
    frontConfig () {
      return this.$store.state.frontConfig
    },

    timeline () {
      var width = this.$store.state.frontConfig.timeline.width
      var height = this.$store.state.frontConfig.timeline.height

      var id = ''
      var found = this.$store.state.frontConfig.timeline.src.match(/https:\/\/docs.google.com\/spreadsheets\/d\/([^/]+)\/edit#/)
      if (found) {
        id = found[1]
      }

      return {
        src: `https://cdn.knightlab.com/libs/timeline3/latest/embed/index.html?source=${id}&font=Default&lang=en&initial_zoom=2&width=${width}&height=${height}`,
        width,
        height
      }
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
