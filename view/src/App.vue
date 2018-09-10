<template>
<div>
  <!-- Event exhibition dialog -->
  <div :style="eventPopupStyle">
    <div style="background-color: #039BE5; height: 128px; width: 448px">
      <div style="width: 436px; height: 46px; padding: 6px 6px 0px 6px;">
        <div class="event-popup-menu-icon circle" @click="eventPopupStyle.display='none'"><i class="el-icon-close"></i></div>
        <div class="event-popup-menu-icon circle"><i class="el-icon-edit"></i></div>
        <div class="event-popup-menu-icon circle"><i class="el-icon-delete"></i></div>
      </div>

      <div style="margin: auto 0px 0px 0px; width: 352px; height 26px; padding: 0px 32px 0px 64px; color: white; font-size: 50px">
        {{ eventToShow.title }}
      </div>
    </div>

    <div style="background-color: white; height: 144px; width: 100%">
    </div>
  </div>

  <el-dialog title="New Event" :visible.sync="newEventDialogVisible">
    <el-form :model="newEvent" label-width="80px">

      <!-- Event Title -->
      <el-form-item label="Event Title">
        <el-input v-model="newEvent.title" auto-complete="off"></el-input>
      </el-form-item>

      <!-- Start Time -->
      <el-form-item label="Start Time">
        <el-time-picker arrow-control v-model="newEvent.start" :picker-options="{ selectableRange: '8:00:00 - 20:30:00' }" placeholder="Start Time"></el-time-picker>
      </el-form-item>

      <!-- End Time -->
      <el-form-item label="End Time">
        <el-time-picker arrow-control v-model="newEvent.end" :picker-options="{ selectableRange: '8:00:00 - 20:30:00' }" placeholder="End Time"></el-time-picker>
      </el-form-item>

      <!-- All Day -->
      <el-form-item label="All Day">
        <el-switch v-model="newEvent.allDay" active-color="#13ce66" inactive-color="rgb(220, 223, 230)"> </el-switch>
      </el-form-item>
    </el-form>

    <div slot="footer" class="dialog-footer">
      <el-button @click="newEventDialogVisible = false">Cancel</el-button>
      <el-button type="primary" @click="newEventDialogVisible = false; addEvent()">Add</el-button>
    </div>
  </el-dialog>
  <!--
  <div style="width:800px; background-image: url('./assets/top/scanlines.png'); background-color: #575757">
  -->
  <div style="width:800px; background-color: #575757">
    <div style="width:800px;height:80px"><img src="./assets/top/top-bg7.jpg"></div>
    <div style="width:800px;height:30px"><img src="./assets/Head.gif"></div>
  </div>

  <table id="menu" width="800" height="30" cellspacing="0" cellpadding="0" border="0" style="width: 800px">
    <tbody><tr>
      <td>
        <img src="./assets/menuimages/menu_01.jpg" alt="" width="32" height="30"></td>
      <td>
        <a href="index.php" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('menu_a_02','','menuimages/menu_h_02.jpg',1);">
          <img name="menu_a_02" src="http://www.jianopt.com/dokhlab/menuimages/menu_a_02.jpg" alt="Home" width="63" height="30" border="0">
        </a>
      </td>
      <td>
        <img src="./assets/menuimages/menu_03.jpg" alt="" width="21" height="30"></td>
      <td>
        <a href="members.php" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('menu_a_04','','menuimages/menu_h_04.jpg',1);">
          <img name="menu_a_04" src="http://www.jianopt.com/dokhlab/menuimages/menu_a_04.jpg" alt="Members" width="100" height="30" border="0">
      </a></td>

      <td>
        <img src="./assets/menuimages/menu_05.jpg" alt="" width="17" height="30"></td>
      <td>
        <a href="research.php" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('menu_a_06','','menuimages/menu_h_06.jpg',1);">
          <img name="menu_a_06" src="http://www.jianopt.com/dokhlab/menuimages/menu_a_06.jpg" alt="Research" width="94" height="30" border="0">
      </a></td>

      <td>
        <img src="./assets/menuimages/menu_07.jpg" alt="" width="25" height="30"></td>
      <td>
        <a href="publications.php" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('menu_a_08','','menuimages/menu_h_08.jpg',1);">
          <img name="menu_a_08" src="http://www.jianopt.com/dokhlab/menuimages/menu_a_08.jpg" alt="Publications" width="129" height="30" border="0">
      </a></td>

      <td>
        <img src="./assets/menuimages/menu_09.jpg" alt="" width="25" height="30"></td>
      <td>
        <a href="tools.php" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('menu_a_10','','menuimages/menu_h_10.jpg',1);">
          <img name="menu_a_10" src="http://www.jianopt.com/dokhlab/menuimages/menu_a_10.jpg" alt="Tools" width="62" height="30" border="0">
      </a></td>

      <td>
        <img src="./assets/menuimages/menu_11.jpg" alt="" width="24" height="30"></td>
      <td>
        <a href="events.php" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('menu_a_12','','menuimages/menu_h_12.jpg',1);">
          <img name="menu_a_12" src="http://www.jianopt.com/dokhlab/menuimages/menu_a_12.jpg" alt="Events" width="83" height="30" border="0">
      </a></td>

      <td>
        <img src="./assets/menuimages/menu_13.jpg" alt="" width="19" height="30"></td>
      <td>
        <a href="search.php" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('menu_a_14','','menuimages/menu_h_14.jpg',1);">
          <img name="menu_a_14" src="http://www.jianopt.com/dokhlab/menuimages/menu_a_14.jpg" alt="Search" width="74" height="30" border="0">
      </a></td>

      <td>
        <img src="./assets/menuimages/menu_15.jpg" alt="" width="32" height="30"></td>
    </tr>
    </tbody>
  </table>

  <div style="width:800px">
    <full-calendar :config="config" :events="events"></full-calendar>
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
import { FullCalendar } from 'vue-full-calendar'
import 'fullcalendar/dist/fullcalendar.min.css'
import moment from 'moment'

export default {
  name: 'app',

  data () {
    var v = this

    return {
      eventPopupStyle: {
        width: '448px',
        height: '288px',
        position: 'absolute',
        left: '0px',
        top: '0px',
        zIndex: '99',
        display: 'none'
      },

      newEvent: {
        start: '',
        date: '',
        end: '',
        title: '',
        allDay: false
      },

      newEventDialogVisible: false,

      formLabelWidth: 150,

      events: [
        {
          title: 'event1',
          start: '2010-01-01'
        },
        {
          title: 'event2',
          start: '2010-01-05',
          end: '2010-01-07'
        },
        {
          title: 'event3',
          start: '2010-01-09T12:30:00',
          allDay: false
        }
      ],

      eventToShow: {},

      config: {
        defaultView: 'month',

        eventRender: function (event, element) {
          console.log(event)
        },

        dayClick (...args) {
          v.$emit('day-click', ...args)
        },

        eventClick (calEvent, jsEvent, view) {
          console.log(calEvent)
          console.log(jsEvent.target)
          alert('Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY)
          alert('View: ' + view.name)
          v.eventToShow = calEvent
          var e = jsEvent.target.closest('td')
          var p = e.getBoundingClientRect()
          v.eventPopupStyle.top = `${p.top + 2}px`
          v.eventPopupStyle.left = `${p.left + e.offsetWidth + 2}px`
          v.eventPopupStyle.display = 'block'
        }
      }
    }
  },

  components: {
    Login,
    SignUp,
    User,
    Window,
    FullCalendar
  },

  methods: {
    redirect (url) {
      window.location.href = url
    },

    addEvent () {
      var v = this

//      console.log(v.newEvent.start)
//      console.log(v.newEvent.end)
//      console.log(`${moment(v.newEvent.start).format()}`)
//      console.log(`${moment(v.newEvent.end).format()}`)

      var newEvent = {
        title: v.newEvent.title,
        start: `${v.newEvent.date}T${moment(v.newEvent.start).format('HH:mm:ss')}`,
        end: `${v.newEvent.date}T${moment(v.newEvent.end).format('HH:mm:ss')}`,
        allDay: v.newEvent.allDay
      }

      v.events.push(newEvent)
    }
  },

  mounted () {
    var v = this

    v.$on('day-click', (date) => {
      v.newEvent.date = moment(date).format('YYYY-MM-DD')
      v.start = '08:00:00'
      v.end = '17:00:00'
      v.allDay = false
      console.log(`${date}`)
      v.newEventDialogVisible = true
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

.event-popup-menu-icon {
  float: right;
  cursor: pointer;
}

.event-popup-menu-icon:hover {
  background-color: #22A7E8;
}

.circle {
  width: 30px;
  height: 30px;
  border-radius: 50%;
  font-size: 15px;
  color: #fff;
  line-height: 30px;
  text-align: center;
  /*
  background: #000
  */
}

</style>

