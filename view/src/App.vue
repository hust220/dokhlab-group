<template>
<div :style="appStyle">
  <!-- User Dialog -->
  <user :visible.sync="userVisible"></user>

  <!-- Head pictures -->
  <div style="width:800px; background-color: #575757">
    <div style="width:800px;height:80px"><img src="./assets/top/top-bg7.jpg"></div>
    <div style="position:relative;width:800px;height:30px">
      <div style="position:absolute;left:28px;bottom:5px;color:white;font-size:11px">
        <span v-if="!$store.state.user" @click="userVisible=true" class="user-button">Login</span>
        <span v-else @click="userVisible=true" v-text="$store.state.user.username" class="user-button"></span>

        <span style="padding-left:20px" v-if="$store.state.user" class="user-button"><a style="color:white" href="#/management">Management</a></span>
      </div>
      <img src="./assets/Head.gif">
    </div>
  </div>

  <!-- Menu -->
  <table id="menu" width="800" height="30" cellspacing="0" cellpadding="0" border="0" style="width: 800px">
    <tbody><tr>
      <td>
        <img src="./assets/menuimages/menu_01.jpg" alt="" width="32" height="30"></td>
      <td>
        <a href="#/home" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('menu_a_02','','menuimages/menu_h_02.jpg',1);">
          <img name="menu_a_02" src="./assets/menuimages/menu_a_02.jpg" alt="Home" width="63" height="30" border="0">
        </a>
      </td>
      <td>
        <img src="./assets/menuimages/menu_03.jpg" alt="" width="21" height="30"></td>
      <td>
        <a href="#/members" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('menu_a_04','','menuimages/menu_h_04.jpg',1);">
          <img name="menu_a_04" src="./assets/menuimages/menu_a_04.jpg" alt="Members" width="100" height="30" border="0">
      </a></td>

      <td>
        <img src="./assets/menuimages/menu_05.jpg" alt="" width="17" height="30"></td>
      <td>
        <a href="#/research" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('menu_a_06','','menuimages/menu_h_06.jpg',1);">
          <img name="menu_a_06" src="./assets/menuimages/menu_a_06.jpg" alt="Research" width="94" height="30" border="0">
      </a></td>

      <td>
        <img src="./assets/menuimages/menu_07.jpg" alt="" width="25" height="30"></td>
      <td>
        <a href="#/publications" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('menu_a_08','','menuimages/menu_h_08.jpg',1);">
          <img name="menu_a_08" src="./assets/menuimages/menu_a_08.jpg" alt="Publications" width="129" height="30" border="0">
      </a></td>

      <td>
        <img src="./assets/menuimages/menu_09.jpg" alt="" width="25" height="30"></td>
      <td>
        <a href="#/tools" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('menu_a_10','','menuimages/menu_h_10.jpg',1);">
          <img name="menu_a_10" src="./assets/menuimages/menu_a_10.jpg" alt="Tools" width="62" height="30" border="0">
      </a></td>

      <td>
        <img src="./assets/menuimages/menu_11.jpg" alt="" width="24" height="30">
      </td>
      <td>
        <a href="#/events" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('menu_a_12','','menuimages/menu_h_12.jpg',1);">
          <img name="menu_a_12" src="./assets/menuimages/menu_a_12.jpg" alt="Events" width="83" height="30" border="0">
        </a>
      </td>

      <td>
        <img src="./assets/menuimages/menu_13.jpg" alt="" width="19" height="30"></td>
      <td>
        <a href="#/search" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('menu_a_14','','menuimages/menu_h_14.jpg',1);">
          <img name="menu_a_14" src="./assets/menuimages/menu_a_14.jpg" alt="Search" width="74" height="30" border="0">
      </a></td>

      <td>
        <img src="./assets/menuimages/menu_15.jpg" alt="" width="32" height="30"></td>
    </tr>
    </tbody>
  </table>

  <div style="width:800px">
    <keep-alive exclude="Home">
      <router-view/>
    </keep-alive>
  </div>

</div>
</template>

<script>
import { bus } from '@/bus.js'
import Login from '@/components/Login'
import SignUp from '@/components/SignUp'
import User from '@/components/User'
import Window from '@/components/Window'
import axios from 'axios'

export default {
  name: 'app',

  data () {
    return {
      formLabelWidth: 150,
      userVisible: false
    }
  },

  computed: {
    appStyle () {
      var v = this
      var style = {
        width: '800px'
      }
      var align
      try {
        align = v.frontConfig.layout.align
      } catch (error) {
        align = 'left'
      }
      if (align === 'center') {
        style.margin = '0px auto'
      } else if (align === 'right') {
        style.marginLeft = 'auto'
        style.marginRight = '0px'
      } else {
        style.marginLeft = '0px'
      }
      return style
    },

    frontConfig () {
      return this.$store.state.frontConfig
    }
  },

  components: {
    Login,
    SignUp,
    User,
    Window
  },

  methods: {
    redirect (url) {
      window.location.href = url
    }
  },

  mounted () {
    var v = this

    // Initialize front-end configuration
    console.log('Init website front-end configuration...')
    axios({
      method: 'get',
      url: v.$config.HOST + `/dokhlab/actions/front-config.php`,
      withCredentials: true
    }).then(response => {
      v.$store.commit('initFrontConfig', response.data)

      console.log(JSON.stringify(response.data))
    }).catch(() => {
      alert(`Could not fetch website front-end configuration`)
    })

    bus.$on('switch-router', (name) => {
      v.active = name
    })
    bus.$on('switch-task', (id) => {
      v.task_id = id
    })
    var top = (document.body.clientHeight - 600) / 2
    var left = (document.body.clientWidth - 1000) / 2
    v.top = (top < 0 ? 0 : top)
    v.left = (left < 0 ? 0 : left)

    // Auto-Login
    axios({
      method: 'get',
      url: v.$config.HOST + '/dokhlab/actions/user.php',
      withCredentials: true
    }).then(response => {
      var r = response.data
      if (r.status === 1) {
        this.$store.commit('login', r.user)
      }
    }).catch(error => {
      alert(error.response.headers)
    })
  }
}
</script>

<style>
html {
  overflow-y: auto;
  width: 100%;
  height: 100%;
  /*
  background: url('./assets/app-bg.jpg') no-repeat 50%;
  */
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}

img {
  margin: 0px;
}

body {
  margin: 8px;
  padding: 0px;
  width: 100%;
  height: 100%;
}

#app {
  margin: 0px;
  padding: 0px;
  width: 100%;
  height: 100%;
  position: relative;
}

table {
  height: 100%;
  width: 100%;
  border-collapse:collapse;
  overflow: hidden;
}

tr {
  width: 100%;
  height: 100%;
  margin: 0px;
}

.nav {
  height: 100%;
  width: 250px;
  min-width: 250px;
  background: #2e3238;
  color: white;
  padding: 0px;
  vertical-align: text-top;
}

.nav-header {
  margin: 20px;
  line-height: 40px;
  vertical-align: middle;
}

.content {
  background-color: white;
  align: center;
  padding: 0px;
  overflow-y: auto;
  position: relative;
  /*
  background-image: url('./assets/intro-bg.png');
   */
background-repeat: no-repeat;
background-position: 0px 0px;
background-size: auto 220px;
}

.content-content {
  width: 720px;
  height: 600px;
  margin: 0px auto;
}

/*
.zoom {
  position: absolute;
  right: 5px;
  top: 5px;
  font-size: 18px;
  color: #878d99;
  cursor: pointer;
  z-index: 99;
}
 */

.left-nav {
  margin-bottom: 0px;
}

.box-card {
  padding: 0px 0px;
}

a {
  text-decoration: None;
}

ul {
  list-style: None;
  padding-left: 0px;
  margin: 0px;
}

li.menu-item {
  padding: 0 20px;
  font-size: 14px;
  /*color: #2d2f33;*/
  height: 56px;
  line-height: 56px;
  cursor: pointer;
}

.submenu-list li.menu-item {
  padding: 0 40px;
}

li.menu-item:hover {
  /*background-color: #ecf5ff*/
}

li.menu-item.is-active {
  /*
  color: #ffd04b;
   */
font-weight: bold;
color: #67c23a;
}

li.menu-item * {
  vertical-align: middle;
}

li.menu-item i {
  margin-right: 5px;
  width: 24px;
  text-align: center;
  font-size: 18px;
  color: #878d99;
}

li.menu-item.is-active i {
  color: inherit;
}

.submenu-title {
  position: relative;
}

li.menu-item i.submenu-arrow {
  position: absolute;
  right: 20px;
  top: 50%;
  margin-top: -7px;
  font-size: 12px;
}

#bg {
  margin: 0px 0px 10px 0px;
}

div.el-card__header {
  padding: 0px 20px;
  /*
  background-color: #223e4a;
  color: white;
   */
}

.grey-button {
  color: #5a5e66;
}

.unselectable {
  -moz-user-select: -moz-none;
  -khtml-user-select: none;
  -webkit-user-select: none;
  -ms-user-select: none;
  user-select: none;
}

a {
  color: rgb(144, 147, 153);
}

a:hover {
  color: rgb(144,147,153);
}

a:visited {
  color: rgb(144, 147, 153);
}

.el-button--text {
  color: #67c23a;
}

a.jn-button {
  color: #67c23a;
  /*
  color: #67c23a;
   */
cursor: pointer;
font-size: 14px;
font-weight: 600;
display: inline-block;
line-height: 1;
border: 1px solid transparent;
background: transparent;
padding: 12px 0px;
}

a:hover.jn-button {
  /*
  color: #ad8134;
   */
color: #b5e7a0;
}

a:visited.jn-button {
  color: #67c23a;
}

.user-button {
  cursor: pointer;
}

.user-button:hover {
  text-decoration: underline;
}

</style>

