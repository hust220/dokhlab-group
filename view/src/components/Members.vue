<template>
<div style="font-size:13px;font-family: Geneva, arial, helvetica, sans-serif;">
  <div style="padding: 5px; width: 800px; height: 41px">
    <div style="margin-right: 5px; height: 30px" align="right">
      <img src="../assets/members.gif" style="width:300px; height:30px" />
    </div>
  </div>

  <div style="display:flex">
    <!-- Navigation -->
    <div style="flex-direction:column;background-color:#EEEEEE;border:1px solid #BBBBBB;padding:5px 10px; width: 250px">
      <h4 @click="member={}" style="cursor:pointer">Dokhlab</h4>

      <h5>Current Members</h5>
      <ul>
        <li @click='member=boss' class="mem">Principal Investigator</li>
        <folder class="mems" v-for="type in ['Undergraduate Student', 'Postdoctoral Fellow', 'Research Assistant Professor', 'Technician', 'Visiting Scientist', 'Foreign Student', 'Rotation Student']" :key="type">
          {{type}}s
          <template slot="items">
            <ul style="padding-left:28px">
              <li class="mem" @click="member=mem" v-for="mem in filterMembers(type, 'yes')" v-text="`${mem.f_name} ${mem.l_name}`"></li>
            </ul>
          </template>
        </folder>
      </ul>

      <h5>Alumni</h5>
      <ul>
        <folder class="mems" v-for="type in ['Research Assistant Professor', 'Postdoctoral Fellow', 'Graduate Student', 'Undergraduate Student', 'Technician', 'Visiting Scientist', 'Graduate (MS) Student', 'Summer Student', 'Fullbright Foreign Student', 'Rotation Student']" :key="type">
          {{type}}s
          <template slot="items">
            <ul style="padding-left:28px">
              <li class="mem" @click="member=mem" v-for="mem in filterMembers(type, 'no')" v-text="`${mem.f_name} ${mem.l_name}`"></li>
            </ul>
          </template>
        </folder>
      </ul>
    </div>

    <!-- Member -->
    <div style="flex-direction:column;width:100%;border:1px solid #BBBBBB;padding:10px;margin-left:10px">
      <div v-if="JSON.stringify(member)===JSON.stringify({})">
        <div style="margin:auto auto;width:468px;height:348px">
          <img style="width:100%;height:100%" :src="$config.HOST+`/dokhlab/actions/image.php?name=members/DG2017-bw.jpg`">
        </div>
      </div>

      <div v-else>
        <div style="display:flex;align-items:center;height:300px">
          <!-- Member info -->
          <div style="flex-direction:column;padding:0px 80px;min-width:20px">
            <ul>
              <li>Full Name: <b>{{member.f_name}} {{member.m_init}} {{member.l_name}}</b></li>
              <li>Status :   <b>{{member.designation}}</b></li>
              <li>Tenure :   <b>{{member.memfrom}} - {{member.memtill}}</b></li>
              <li>E-mail :   <b>{{member.email}}</b></li>
              <li>Phone :    <b>{{member.phone}}</b></li>
            </ul>
          </div>

          <!-- Member Image -->
          <div style="flex-direction:column;width:135px;height:197px">
            <img style="width:100%;height:100%" :src="$config.HOST+`/dokhlab/actions/image.php?name=members/${member.f_name}-${member.l_name}.jpg`">
          </div>
        </div>

        <h3>About {{member.f_name}}</h3>
        <div class="mem-panel">
          <p v-html="member.info"></p>
        </div>

        <h3>Education</h3>
        <div class="mem-panel">
          <p v-html="member.education"></p>
        </div>

        <h3>Honors and Awards</h3>
        <div class="mem-panel">
          <p v-html="member.awards"></p>
        </div>
      </div>

    </div>
  </div>
</div>
</template>

<script>
import { bus } from '../bus.js'
import axios from 'axios'

export default {
  name: 'Members',

  data () {
    return {
      members: [],
      member: {}
    }
  },

  computed: {
    currentPostdocs () {
      return this.members.filter(mem => mem.current === 'yes' && mem.designation === 'Postdoctoral Fellow')
    },

    boss () {
      return this.members.filter(mem => mem.designation === 'Principal Investigator')[0]
    }
  },

  methods: {
    filterMembers (designation, current) {
      return this.members.filter(mem => mem.current === current && mem.designation === designation)
    },

    getMembers () {
      var v = this

      axios({
        method: 'get',
        url: v.$config.HOST + '/dokhlab/actions/members.php'
      }).then(response => {
        v.members = response.data
//        v.member = v.boss
      }).catch(() => {
        alert('Fetch members failed!')
      })
    }
  },

  mounted () {
    var v = this

    this.$nextTick(function () {
      bus.$emit('switch-router', 'Members')
    })

    v.getMembers()
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

.mem {
  cursor: pointer;
  font-size: 12px;
}

.mems {
  font-size: 12px;
}

.mem-panel {
  background-color:#EEEEEE;
  border:1px solid #BBBBBB;
  padding:5px 10px;
  text-align: justify;
  text-justify: inter-word;
}

</style>
