<template>
<div>
  <!-- Head pictures -->
  <div style="width:800px; background-color: #575757">
    <div style="width:800px;height:80px"><img src="./assets/top/top-bg7.jpg"></div>
    <div style="width:800px;height:30px"><img src="./assets/Head.gif"></div>
  </div>

  <!-- Menu -->
  <table id="menu" width="800" height="30" cellspacing="0" cellpadding="0" border="0" style="width: 800px">
    <tbody><tr>
      <td>
        <img src="./assets/menuimages/menu_01.jpg" alt="" width="32" height="30"></td>
      <td>
        <a href="/#/home" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('menu_a_02','','menuimages/menu_h_02.jpg',1);">
          <img name="menu_a_02" src="./assets/menuimages/menu_a_02.jpg" alt="Home" width="63" height="30" border="0">
        </a>
      </td>
      <td>
        <img src="./assets/menuimages/menu_03.jpg" alt="" width="21" height="30"></td>
      <td>
        <a href="/#/members" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('menu_a_04','','menuimages/menu_h_04.jpg',1);">
          <img name="menu_a_04" src="./assets/menuimages/menu_a_04.jpg" alt="Members" width="100" height="30" border="0">
      </a></td>

      <td>
        <img src="./assets/menuimages/menu_05.jpg" alt="" width="17" height="30"></td>
      <td>
        <a href="/#/research" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('menu_a_06','','menuimages/menu_h_06.jpg',1);">
          <img name="menu_a_06" src="./assets/menuimages/menu_a_06.jpg" alt="Research" width="94" height="30" border="0">
      </a></td>

      <td>
        <img src="./assets/menuimages/menu_07.jpg" alt="" width="25" height="30"></td>
      <td>
        <a href="/#/publications" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('menu_a_08','','menuimages/menu_h_08.jpg',1);">
          <img name="menu_a_08" src="./assets/menuimages/menu_a_08.jpg" alt="Publications" width="129" height="30" border="0">
      </a></td>

      <td>
        <img src="./assets/menuimages/menu_09.jpg" alt="" width="25" height="30"></td>
      <td>
        <a href="/#/tools" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('menu_a_10','','menuimages/menu_h_10.jpg',1);">
          <img name="menu_a_10" src="./assets/menuimages/menu_a_10.jpg" alt="Tools" width="62" height="30" border="0">
      </a></td>

      <td>
        <img src="./assets/menuimages/menu_11.jpg" alt="" width="24" height="30">
      </td>
      <td>
        <a href="/#/events" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('menu_a_12','','menuimages/menu_h_12.jpg',1);">
          <img name="menu_a_12" src="./assets/menuimages/menu_a_12.jpg" alt="Events" width="83" height="30" border="0">
        </a>
      </td>

      <td>
        <img src="./assets/menuimages/menu_13.jpg" alt="" width="19" height="30"></td>
      <td>
        <a href="/#/search" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('menu_a_14','','menuimages/menu_h_14.jpg',1);">
          <img name="menu_a_14" src="./assets/menuimages/menu_a_14.jpg" alt="Search" width="74" height="30" border="0">
      </a></td>

      <td>
        <img src="./assets/menuimages/menu_15.jpg" alt="" width="32" height="30"></td>
    </tr>
    </tbody>
  </table>

  <div style="width:800px">
    <keep-alive exclude="Task">
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
      formLabelWidth: 150
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
      url: v.$config.HOST + '/allos/actions/user.php'
    }).then(response => {
      var r = response.data
      if (r.status === 1) {
        this.$store.commit('login', r.user)
      }
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

* a.allos-button {
  color: #67c23a;
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
  color: #67c23a;
}

a:hover {
  /*
  color: #ad8134;
   */
color: #b5e7a0;
}

a:visited {
  color: #ad8134;
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

</style>

