<template>
<div>

  <!-- Row Update Dialog -->
  <el-dialog title="Update" :visible.sync="updateDialogVisible">
    <div style="height: 500px; overflow-y: auto">
      <el-form :model="rowToUpdate" label-width="80px">

        <!-- Event Title -->
        <el-form-item v-for="(col, name) in rowToUpdate" :key="name" :label="name">
          <el-input v-model="rowToUpdate[name]" auto-complete="off"></el-input>
        </el-form-item>
      </el-form>
    </div>

    <div slot="footer" class="dialog-footer">
      <el-button @click="updateDialogVisible = false">Cancel</el-button>
      <el-button type="primary" @click="updateDialogVisible = false; update()">Update</el-button>
    </div>
  </el-dialog>


<div style="font-size:13px;font-family: Geneva, arial, helvetica, sans-serif;border:1px solid #BBBBBB;width:800px;overflow:scroll;position:absolute;top:160px;bottom:50px">
  <div style="margin-top:20px;padding:0px;margin-left:10px">
    <ul v-for="tableName in tableNames">
      <folder>
        <b>{{tableName}}</b>

        <div slot="items">
          <table style="border-collapse: collapse;" v-if="tables[tableName]">
            <tr v-if="tables[tableName].length>0">
              <th></th>
              <th v-for="(col,name) in tables[tableName][0]" v-text="name"></th>
            </tr>
            <tr v-for="(row, rowIndex) in tables[tableName]">
              <td>
                <div class="table-cell">
                  <i @click="rowToUpdate=tables[tableName][rowIndex];updateDialogVisible=true" class="el-icon-edit button"></i><i class="el-icon-delete button"></i>
                </div>
              </td>
              <td v-for="(col,name) in tables[tableName][rowIndex]">
                <div class="table-cell" v-text='tables[tableName][rowIndex][name]'>
                </div>
              </td>
            </tr>
          </table>
        </div>
      </folder>
    </ul>
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
      rowToUpdate: {},

      updateDialogVisible: false,

      tables: {
        mems: [],
        pubs: [],
        users: []
      }
    }
  },

  computed: {
    tableNames () {
      var v = this
      return Object.keys(v.tables)
    }
  },

  methods: {
    getTable (name) {
      var v = this

      axios({
        method: 'get',
        url: v.$config.HOST + `/dokhlab/actions/table.php?name=${name}`
      }).then(response => {
//        console.log(response.data)
//        console.log('hi')
        console.log(v.tables)
//        console.log(Object.keys(v.tables))
        v.tables[name] = []
        for (var i = 0; i < response.data.length; i++) {
//          console.log(response.data[i])
          v.tables[name].push(response.data[i])
        }
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

    console.log(Object.keys(v.tables))
//    v.tables = {}
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

.table-cell {
  white-space: nowrap;
  overflow: hidden;
  text-overflow:ellipsis;
  min-width:50px;
  max-width:200px;
}

.button {
  cursor: pointer;
}

table tr:nth-child(even) {
  background-color: #f2f2f2
}

</style>
