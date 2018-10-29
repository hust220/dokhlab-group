<template>
<div>
  <!-- Event exhibition dialog -->
  <div :style="eventPopupStyle">
    <div style="background-color: #039BE5; height: 128px; width: 448px">
      <div style="width: 436px; height: 46px; padding: 6px 6px 0px 6px;">
        <div class="event-popup-menu-icon circle" @click="eventPopupStyle.display='none'"><i class="el-icon-close"></i></div>
        <div v-if="Number($store.state.user.level)===10" class="event-popup-menu-icon circle" @click="eventPopupStyle.display='none';eventToUpdate=eventToShow;updateEventDialogVisible=true"><i class="el-icon-edit"></i></div>
        <div v-if="Number($store.state.user.level)===10" class="event-popup-menu-icon circle" @click="deleteEvent()"><i class="el-icon-delete"></i></div>
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

  <!-- Update Event Dialog -->
  <el-dialog title="Update Event" :visible.sync="updateEventDialogVisible">
    <el-form :model="eventToUpdate" label-width="80px">

      <!-- Event Title -->
      <el-form-item label="Event Title">
        <el-input v-model="eventToUpdate.title" auto-complete="off"></el-input>
      </el-form-item>

      <!-- Start Time -->
      <el-form-item label="Start Time">
        <el-time-picker v-model="eventToUpdate.start" :picker-options="{ selectableRange: '8:00:00 - 20:30:00' }" placeholder="Start Time"></el-time-picker>
      </el-form-item>

      <!-- End Time -->
      <el-form-item label="End Time">
        <el-time-picker v-model="eventToUpdate.end" :picker-options="{ selectableRange: '8:00:00 - 20:30:00' }" placeholder="End Time"></el-time-picker>
      </el-form-item>

      <!-- All Day -->
      <el-form-item label="All Day">
        <el-switch v-model="eventToUpdate.allDay" active-color="#13ce66" inactive-color="rgb(220, 223, 230)"> </el-switch>
      </el-form-item>
    </el-form>

    <div slot="footer" class="dialog-footer">
      <el-button @click="updateEventDialogVisible = false">Cancel</el-button>
      <el-button type="info" @click="updateEventDialogVisible = false; updateEvent()">Update</el-button>
    </div>
  </el-dialog>

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
      <el-button type="info" @click="newEventDialogVisible = false; addEvent()">Add</el-button>
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

      eventToUpdate: {
        start: '',
        date: '',
        end: '',
        title: '',
        allDay: false
      },
      updateEventDialogVisible: false,

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

        header: {
          left: 'prev,next today',
//          right: 'month,listWeek',
          right: 'month',
          center: 'title'
        },

        dayClick (date) {
          if (Number(v.$store.state.user.level) === 10) {
            v.newEvent.date = moment(date).format('YYYY-MM-DD')
            v.start = '08:00:00'
            v.end = '17:00:00'
            v.allDay = false
            console.log(`${date}`)
            v.newEventDialogVisible = true
          }
        },

        eventClick (calEvent, jsEvent, view) {
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

      console.log(v.newEvent.date)
      console.log(v.newEvent.start)
      console.log(v.newEvent.end)
      //      console.log(`${moment(v.newEvent.start).format()}`)
      //      console.log(`${moment(v.newEvent.end).format()}`)

      //      var newEvent = {
        //        title: v.newEvent.title,
        //        start: `${v.newEvent.date}T${moment(v.newEvent.start).format('HH:mm:ss')}`,
        //        end: `${v.newEvent.date}T${moment(v.newEvent.end).format('HH:mm:ss')}`,
        //        allDay: v.newEvent.allDay
      //      }

      var newEvent = {
        title: v.newEvent.title,
        start: moment(`${v.newEvent.date}T${moment(v.newEvent.start).format('HH:mm:ss')}`).unix(),
        end: moment(`${v.newEvent.date}T${moment(v.newEvent.end).format('HH:mm:ss')}`).unix(),
        all_day: (v.newEvent.allDay ? 1 : 0)
      }

      console.log(newEvent)

      //      v.events.push(newEvent)

      let formData = new FormData()
      formData.append('__table', 'events')

      for (let i in newEvent) {
        if (i !== 'id') {
          formData.append(i, newEvent[i])
        }
      }

      axios({
        method: 'post',
        url: v.$config.HOST + `/dokhlab/actions/insert.php`,
        data: formData,
        config: {headers: {'Content-Type': 'multipart/form-data'}},
        withCredentials: true
      }).then(response => {
        v.fetchEvents()
//        alert(`Insert successfully!`)
        v.newEventDialogVisible = false
      }).catch(error => {
        console.log(error.response)
        alert(`Insert failed!`)
        v.newEventDialogVisible = false
      })
    },

    arrayEraseIf (arr, cb) {
      var i = 0
      while (i < arr.length) {
        if (cb(arr[i], i)) {
          arr.splice(i, 1)
        } else {
          ++i
        }
      }
    },

    arrayAssignIf (arr, cb, obj) {
      var i = 0
      while (i < arr.length) {
        if (cb(arr[i], i)) {
          for (var j in arr[i]) {
            arr[i][j] = obj[j]
          }
        }
        ++i
      }
    },

    updateEvent () {
      let v = this

      var eventToUpdate = {
        title: v.eventToUpdate.title,
        start: moment(v.eventToUpdate.start).unix(),
        end: moment(v.eventToUpdate.end).unix(),
        all_day: (v.eventToUpdate.allDay ? 1 : 0),
        id: v.eventToUpdate.id
      }

      let formData = new FormData()

      // Add Table Name
      formData.append('__table', 'events')
      formData.append('__key', 'id')

      for (let i in eventToUpdate) {
        formData.append(i, eventToUpdate[i])
      }

      axios({
        method: 'post',
        url: v.$config.HOST + `/dokhlab/actions/update.php`,
        data: formData,
        config: {headers: {'Content-Type': 'multipart/form-data'}},
        withCredentials: true
      }).then(response => {
        v.fetchEvents()
//        alert(`Update successfully!`)
        v.updateEventDialogVisible = false
      }).catch(() => {
        alert(`Update failed!`)
        v.updateEventDialogVisible = false
      })
    },

    deleteEvent () {
      let v = this

      if (confirm('Are you sure?')) {
        let formData = new FormData()

        // Add Table Name
        formData.append('__table', 'events')

        // Add Key
        formData.append('id', v.eventToShow.id)

        axios({
          method: 'post',
          url: v.$config.HOST + `/dokhlab/actions/erase.php`,
          data: formData,
          config: {headers: {'Content-Type': 'multipart/form-data'}},
          withCredentials: true
        }).then(response => {
          v.fetchEvents()
//          alert('Delete successfully!')
          v.eventPopupStyle.display = 'none'
        }).catch(() => {
          alert(`Delete failed!`)
          v.eventPopupStyle.display = 'none'
        })
      }
    },

    fetchEvents () {
      var v = this

      axios({
        method: 'get',
        url: v.$config.HOST + `/dokhlab/actions/table.php?name=events`,
        withCredentials: true
      }).then(response => {
        v.events = []
        for (var i = 0; i < response.data.length; i++) {
          //        v.events.push(response.data[i])
          var e = response.data[i]

          v.events.push({
            title: e.title,
            start: moment.unix(e.start).format(),
            end: moment.unix(e.end).format(),
            allDay: (Number(e.all_day) === 1),
            id: e.id
          })
        }
        console.log('response:')
        console.log(JSON.stringify(response.data))
        console.log('events:')
        console.log(JSON.stringify(v.events))
      }).catch(error => {
        console.log(error.response)
        alert(`Fetch Events failed!`)
      })
    }
  },

  mounted () {
    var v = this

    this.$nextTick(function () {
      bus.$emit('switch-router', 'Events')

//      v.$on('day-click', (date) => {
//        if (Number(v.$store.state.user.level) === 10) {
//          v.newEvent.date = moment(date).format('YYYY-MM-DD')
//          v.start = '08:00:00'
//          v.end = '17:00:00'
//          v.allDay = false
//          console.log(`${date}`)
//          v.newEventDialogVisible = true
//        }
//      })

      v.fetchEvents()
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
