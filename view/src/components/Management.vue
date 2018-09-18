<template>
<div style="font-size:13px;font-family: Geneva, arial, helvetica, sans-serif;">
  <div style="margin-top:20px;width:100%;border:1px solid #BBBBBB;padding:10px;margin-left:10px">
    <ul v-for="tableName in tableNames">
      <h3>{{tableName}}</h3>
      <div v-if="tables[tableName]">
      {{tables[tableName].length}}
      <!--
      <li v-for="(row, rowIndex) in tables[tableName]">
      hi
      <div v-if="tables[tableName][rowIndex-1]">
        <input v-for="(col,name) in tables[tableName][rowIndex-1]" type='text' v-model='tables[tableName][rowIndex-1][name]' />
      </div>
      </li>
      -->
      </div>
    </ul>
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
      tableNames: ['mems', 'pubs', 'users'],

      tables: {
        mems: [],
        pubs: [],
        users: []
      }
    }
  },

  methods: {
    getTable (name) {
      var v = this

      axios({
        method: 'get',
        url: v.$config.HOST + `/dokhlab/actions/table.php?name=${name}`
      }).then(response => {
        v.tables[name] = response.data
      }).catch(() => {
        alert(`Fetch Table ${name} failed!`)
      })
    }
  },

  mounted () {
    var v = this

    this.$nextTick(function () {
      bus.$emit('switch-router', 'Members')
    })

    v.tables = {}
    for (var i in v.tableNames) {
      v.getTable(v.tableNames[i])
    }
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
