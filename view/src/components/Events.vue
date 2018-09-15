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
      <div style="padding-top:24px;padding-bottom:16px;overflow-x:hidden;overflow-y:auto">
        <div style="display: flex; width: 100%">
          <div style="font-size: 20px; font-weight: 400; line-height: 16px; color: #212121; width: 48px; padding-left: 16px; align-items: center; display: flex">
            <i class="el-icon-time"></i>
          </div>
          <div style="font-size: 14px; font-weight: 400; line-height: 16px; color: #212121">
            <div>
              {{`${formatTime(eventToShow.start, 'dddd, MMMM D')}`}}
            </div>
            <div>
              {{`${formatTime(eventToShow.start, 'HH:mm')} - ${formatTime(eventToShow.end, 'HH:mm')}`}}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- New Event Dialog -->
  <el-dialog title="New Event" :visible.sync="newEventDialogVisible">
    <el-form :model="newEvent" label-width="80px">

      <!-- Event Title -->
      <el-form-item label="Event Title">
        <el-input v-model="newEvent.title" auto-complete="off"></el-input>
      </el-form-item>

      <!-- Start Time -->
      <el-form-item label="Start Time">
        <el-time-picker v-model="newEvent.start" :picker-options="{ selectableRange: '8:00:00 - 20:30:00' }" placeholder="Start Time"></el-time-picker>
      </el-form-item>

      <!-- End Time -->
      <el-form-item label="End Time">
        <el-time-picker v-model="newEvent.end" :picker-options="{ selectableRange: '8:00:00 - 20:30:00' }" placeholder="End Time"></el-time-picker>
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

  <full-calendar :config="config" :events="events"></full-calendar>
</div>
</template>

<script>
import { bus } from '../bus.js'
import { FullCalendar } from 'vue-full-calendar'
import 'fullcalendar/dist/fullcalendar.min.css'
import moment from 'moment'
import axios from 'axios'

export default {
  name: 'Events',

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
        display: 'none',
        boxShadow: '0 24px 38px 3px rgba(0,0,0,0.14),0 9px 46px 8px rgba(0,0,0,0.12),0 11px 15px -7px rgba(0,0,0,0.2)',
        borderRadius: '2px'
      },

      newEvent: {
        start: '',
        date: '',
        end: '',
        title: '',
        allDay: false
      },

      newEventDialogVisible: false,

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
//          console.log(calEvent)
//          console.log(jsEvent.target)
//          alert('Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY)
//          alert('View: ' + view.name)
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
    FullCalendar
  },

  methods: {
    formatTime (t, format) {
      return moment(t).format(format)
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

      // Post the new event to the database
//      axios({
//        method: 'post',
//        url: v.$config.HOST + '/events/actions/events.php'
//      }).then(response => {
//        for (var i in response.data) {
//          v.events.push(response.data[i])
//        }
//      })
    }
  },

  mounted () {
    var v = this

    this.$nextTick(function () {
      bus.$emit('switch-router', 'Events')

      v.$on('day-click', (date) => {
        v.newEvent.date = moment(date).format('YYYY-MM-DD')
        v.start = '08:00:00'
        v.end = '17:00:00'
        v.allDay = false
        console.log(`${date}`)
        v.newEventDialogVisible = true
      })

      // Fetch events
      axios({
        method: 'get',
        url: v.$config.HOST + '/events/actions/events.php'
      }).then(response => {
        for (var i in response.data) {
          v.events.push(response.data[i])
        }
      })
    })
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="stylus" scoped>

test-color = #22A7E8

.event-popup-menu-icon {
  float: right;
  cursor: pointer;
}

.event-popup-menu-icon:hover {
  background-color: test-color;
}

.circle {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  font-size: 20px;
  color: #fff;
  line-height: 40px;
  text-align: center;
  /*
  background: #000
  */
}


</style>
