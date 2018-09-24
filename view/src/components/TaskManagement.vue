<template>
<div class="taskmanagement">
  <!-- Task introduction dialog -->
  <el-dialog :title="`Task`" :visible.sync="taskPanelVisible" width="50%">
    <table>
      <tr><td>Task ID</td><td>{{taskToShow.id}}</td></tr>
      <tr><td>Structure</td><td><a :href="$config.HOST + `/dokhlab/actions/file.php?name=${taskToShow.receptor}&format=`">{{taskToShow.receptor}}</a></td></tr>
      <tr><td>Source</td><td>{{`${taskToShow.source_chain}-${taskToShow.source_residue}`}}</td></tr>
      <tr><td>Target</td><td>{{`${taskToShow.target_chain}-${taskToShow.target_residue}`}}</td></tr>
      <tr><td>At Chains</td><td>{{taskToShow.chains}}</td></tr>
      <tr><td>Threshold</td><td>{{taskToShow.threshold}}</td></tr>
      <tr><td>Cutoff</td><td>{{taskToShow.cutoff}}</td></tr>
      <tr><td>K</td><td>{{taskToShow.k}}</td></tr>
      <tr></tr>
    </table>
    <span slot="footer" class="dialog-footer">
      <el-button type="primary" @click="taskPanelVisible=false">Close</el-button>
    </span>
  </el-dialog>

  <!-- Delete Task dialog -->
  <el-dialog :title="`Delete Task`" :visible.sync="deleteTaskPanelVisible" width="50%">
    <p>Are you sure to delete the Task {{taskToDelete.id}}?</p>
    <table>
      <tr><td>Structure</td><td><a :href="$config.HOST + `/dokhlab/actions/file.php?name=${taskToDelete.receptor}&format=`">{{taskToDelete.receptor}}</a></td></tr>
      <tr><td>Source</td><td>{{`${taskToDelete.source_chain}-${taskToDelete.source_residue}`}}</td></tr>
      <tr><td>Target</td><td>{{`${taskToDelete.target_chain}-${taskToDelete.target_residue}`}}</td></tr>
      <tr><td>Threshold</td><td>{{taskToDelete.threshold}}</td></tr>
      <tr><td>Cutoff</td><td>{{taskToDelete.cutoff}}</td></tr>
      <tr><td>K</td><td>{{taskToDelete.k}}</td></tr>
      <tr></tr>
    </table>
    <span slot="footer" class="dialog-footer">
      <el-button type="primary" @click="deleteTaskPanelVisible=false">Cancel</el-button>
      <el-button type="warning" @click="deleteTask();deleteTaskPanelVisible=false">Confirm</el-button>
    </span>
  </el-dialog>

  <!-- Stop Task dialog -->
  <el-dialog :title="`Stop Task`" :visible.sync="stopTaskPanelVisible" width="50%">
    <p>Are you sure to stop the Task {{taskToStop.id}}?</p>
    <table>
      <tr><td>Structure</td><td><a :href="$config.HOST + `/dokhlab/actions/file.php?name=${taskToStop.receptor}&format=`">{{taskToStop.receptor}}</a></td></tr>
      <tr><td>Source</td><td>{{`${taskToStop.source_chain}-${taskToStop.source_residue}`}}</td></tr>
      <tr><td>Target</td><td>{{`${taskToStop.target_chain}-${taskToStop.target_residue}`}}</td></tr>
      <tr><td>Threshold</td><td>{{taskToStop.threshold}}</td></tr>
      <tr><td>Cutoff</td><td>{{taskToStop.cutoff}}</td></tr>
      <tr><td>K</td><td>{{taskToStop.k}}</td></tr>
      <tr></tr>
    </table>
    <span slot="footer" class="dialog-footer">
      <el-button type="primary" @click="stopTaskPanelVisible=false">Cancel</el-button>
      <el-button type="warning" @click="stopTask();stopTaskPanelVisible=false">Stop</el-button>
    </span>
  </el-dialog>

  <!-- Resubmit Task Dialog -->
  <el-dialog :title="`Resubmit Task`" :visible.sync="resubmitTaskPanelVisible" width="50%">
    <table>
      <tr><td>Structure</td><td><a :href="$config.HOST + `/dokhlab/actions/file.php?name=${taskToResubmit.receptor}&format=`">{{taskToResubmit.receptor}}</a></td></tr>

      <tr>
        <td>Source</td>
        <td v-if="!doEditResubmit">{{`${taskToResubmit.source_chain}-${taskToResubmit.source_residue}`}}</td>
        <td v-if="doEditResubmit"><input type='text' v-model='form.source_chain'>-<input type='text' v-model='form.source_residue'></td>
      </tr>

      <tr>
        <td>Target</td>
        <td v-if="!doEditResubmit">{{`${taskToResubmit.target_chain}-${taskToResubmit.target_residue}`}}</td>
        <td v-if="doEditResubmit"><input type='text' v-model='form.target_chain'>-<input type='text' v-model='form.target_residue'></td>
      </tr>

      <tr>
        <td>At Chains</td>
        <td v-if="!doEditResubmit">{{taskToResubmit.chains}}</td>
        <td v-if="doEditResubmit"><input type='text' v-model='form.chains'></td>
      </tr>

      <tr>
        <td>Threshold</td>
        <td v-if="!doEditResubmit">{{taskToResubmit.threshold}}</td>
        <td v-if="doEditResubmit"><input type='text' v-model='form.threshold'></td>
      </tr>

      <tr>
        <td>Cutoff</td>
        <td v-if="!doEditResubmit">{{taskToResubmit.cutoff}}</td>
        <td v-if="doEditResubmit"><input type='text' v-model='form.cutoff'></td>
      </tr>

      <tr>
        <td>K</td>
        <td v-if="!doEditResubmit">{{taskToResubmit.k}}</td>
        <td v-if="doEditResubmit"><input type='text' v-model='form.k'></td>
      </tr>

      <tr></tr>
    </table>

    <span slot="footer" class="dialog-footer">
      <el-button type="primary" @click="resubmitTaskPanelVisible=false">Cancel</el-button>
      <el-button type="success" @click="doEditResubmit=true" v-if="!doEditResubmit">Edit</el-button>
      <el-button type="warning" @click="resubmitTask();resubmitTaskPanelVisible=false">Resubmit</el-button>
    </span>
  </el-dialog>

  <!-- Tasks Table -->
  <table class="tasks">
    <thead>
      <tr>
        <th>Task ID</th>
        <th>Task Title</th>
        <th>User</th>
        <th>Submit Time</th>
        <th>Finish Time</th>
        <th>Status</th>
      </tr>
    </thead>

    <tbody>
      <tr v-for="task in tasks" v-if="task.userid==='0'||task.userid===$store.state.user.id||$store.state.user.level==='10'">
        <td><a style="cursor:pointer" @click="taskToShow=task;taskPanelVisible=true">{{ task.id }}</a></td>
        <td>{{task.title}}</td>
        <td>{{task.userid==='0' ? 'Guest' : task.username}}</td>
        <td v-text="task.tsubmit"></td>
        <td v-text="task.tfinish"></td>
        <td>
          <!-- View -->
          <el-tooltip effect="dark" content="View Task" placement="top">
            <a :href="'#/Task/'+task.id"><i style="cursor:pointer" v-if="task.status==='finished'" class="el-icon-view"></i></a>
          </el-tooltip>

          <!-- Queued -->
          <el-tooltip effect="dark" content="Queued" placement="top">
            <i v-if="task.status==='queued'" class="el-icon-tickets"></i>
          </el-tooltip>

          <!-- Failed -->
          <el-tooltip effect="dark" content="Failed" placement="top">
            <i v-if="task.status==='failed'" class="el-icon-warning"></i>
          </el-tooltip>

          <!-- Running -->
          <el-tooltip effect="dark" content="Running" placement="top">
            <i v-if="task.status!=='finished'&&task.status!=='failed'&&task.status!=='queued'" class="el-icon-loading"></i>
          </el-tooltip>

          <!-- Stop Task -->
          <template v-if="$store.state.user && ($store.state.user.level >= 10 || $store.state.user.id === task.userid)">
            <el-tooltip effect="dark" content="Stop Task" placement="top">
              <i style="cursor:pointer" v-if="task.status!=='finished'&&task.status!=='failed'" class="el-icon-close" @click="taskToStop=task;stopTaskPanelVisible=true"></i>
            </el-tooltip>
          </template>

          <!-- Resubmit Task -->
          <template v-if="$store.state.user && ($store.state.user.level >= 10 || $store.state.user.id === task.userid)">
            <el-tooltip effect="dark" content="Resubmit Task" placement="top">
              <i style="cursor:pointer" v-if="task.status==='finished'||task.status==='failed'" class="el-icon-upload" @click="setTaskToResubmit(task);doEditResubmit=false;resubmitTaskPanelVisible=true"></i>
            </el-tooltip>
          </template>

          <!-- Delete Task -->
          <template v-if="$store.state.user && ($store.state.user.level >= 10 || $store.state.user.id === task.userid)">
            <el-tooltip effect="dark" content="Delete Task" placement="top">
              <i style="cursor:pointer" v-if="task.status==='finished'||task.status==='failed'" class="el-icon-delete" @click="taskToDelete=task;deleteTaskPanelVisible=true"></i>
            </el-tooltip>
          </template>
        </td>
      </tr>
    </tbody>
  </table>
  <!--
  <el-table :data="tasks" height="700" style="width: 100%">
    <el-table-column prop="id" label="Task ID">
      <template slot-scope="scope">
        <a :href="'#/Task/'+scope.row.id">{{ scope.row.id }}</a>
      </template>
    </el-table-column>
    <el-table-column prop="userid" label="User ID">
      <template slot-scope="scope">
        {{scope.row.userid==='0' ? 'Guest' : scope.row.userid}}
      </template>
    </el-table-column>
    <el-table-column prop="tsubmit" label="Submit Time"></el-table-column>
    <el-table-column prop="tprocess" label="Process Time"></el-table-column>
    <el-table-column prop="tfinish" label="Finish Time"></el-table-column>
    <el-table-column prop="status" label="Status">
      <template slot-scope="scope">
        <div v-if="scope.row.status==='queued'"><i>Queued</i></div>
        <div v-else-if="scope.row.status==='failed'"><i>Failed</i></div>
        <div v-html="finishedStatus(scope.row.id)" v-else-if="scope.row.status==='finished'"></div>
        <div v-else><i style="margin-left: 10px" class="el-icon-loading"></i></div>
      </template>
    </el-table-column>
  </el-table>
  -->
</div>
</template>

<script>
import axios from 'axios'
import { bus } from '../bus.js'

axios.defaults.headers.post['Content-Type'] = 'multipart/form-data'

export default {
  name: 'TaskManagement',

  data () {
    return {
      taskToShow: '',
      taskToDelete: '',
      taskToStop: '',
      taskToResubmit: '',

      taskPanelVisible: false,
      deleteTaskPanelVisible: false,
      stopTaskPanelVisible: false,
      resubmitTaskPanelVisible: false,

      doEditResubmit: false,

      runningStatus: '<i style="margin-left: 10px" class="el-icon-loading"></i>',
      tasks: [],
      intervalId: 0,

      form: {
        id: '',
        source_chain: '',
        source_residue: '',
        target_chain: '',
        target_residue: '',
        chains: '',
        threshold: '',
        k: '',
        cutoff: ''
      }
    }
  },

  methods: {
    finishedStatus (id) {
      return '<a href="#/Task/' + id + '"><i style="color:#878d99; margin-left: 10px;" class="view el-icon-view"></i></a>'
    },

    check () {
      var v = this
      axios.get(v.$config.HOST + '/dokhlab/actions/check.php').then(response => {
        v.tasks = response.data.tasks
        v.tasks.sort((a, b) => {
          return parseInt(b.userid) - parseInt(a.userid)
        })
      }).catch(() => {
        console.log('get failed')
      })
    },

    deleteTask () {
      var v = this
      axios.get(`${v.$config.HOST}/dokhlab/actions/delete_task.php?id=${v.taskToDelete.id}`).then(response => {
        console.log(response.data)
        alert('Delete successfully')
      }).catch(() => {
        alert('Delete failed')
      })
    },

    stopTask () {
      var v = this
      axios.get(`${v.$config.HOST}/dokhlab/actions/stop_task.php?id=${v.taskToStop.id}`).then(response => {
        console.log(response.data)
        alert('Stop successfully')
      }).catch(() => {
        alert('Stop failed')
      })
    },

    setTaskToResubmit (task) {
      var v = this
      v.taskToResubmit = task
      for (let i in v.form) {
        v.form[i] = task[i]
      }
    },

    resubmitTask () {
      let v = this

      let formData = new FormData()

      for (let i in v.form) {
        formData.append(i, v.form[i])
      }

      axios({
        method: 'post',
        url: v.$config.HOST + '/dokhlab/actions/resubmit.php',
        data: formData,
        config: {headers: {'Content-Type': 'multipart/form-data'}}
      }).then(response => {
        alert('Resubmitted successfully!')
        window.location.href = `#/Task/${v.taskToResubmit.id}`
      }).catch(() => {
        alert('Resubmitted failed!')
      })
    }
  },

  mounted () {
    this.$nextTick(function () {
      bus.$emit('switch-router', 'TaskManagement')

      var v = this
      v.check()
      v.intervalId = setInterval(function () {
        v.check()
      }, 5000)
    })
  },

  destroyed () {
    var v = this
    clearInterval(v.intervalId)
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

.taskmanagement {
  margin-left: 10px;
  height: 100%;
}

.el-form-item {
  margin-bottom: 3px;
}

table.tasks {
  text-align: center;
  color: rgb(96, 98, 102);
  margin: 30px 0px;
}

thead {
  display: block;
}

table.tasks tbody {
  height: 600px;
  display: block;
  overflow-y: auto;
  overflow-x: hidden;
}

th {
  color: #878d99;
}

th {
  padding: 12px 12px 0px 12px;
  width: 120px;
}

td {
  padding: 12px;
  width: 120px;
}

</style>
