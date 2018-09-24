<template>
<div>

  <!-- New Image -->
  <input type="file" id="upload_image" ref="upload_image" @change="setTempImage($event)" style="display:None;">
  <!--
  <input type="file" id="upload_image" ref="upload_image" style="display:None;">
  -->
  <!-- New Image Dialog -->
  <el-dialog :title="tempImage.name" :visible.sync="tempImage.visible">
    <div style="height: 500px; overflow-x: auto">
      <img style="display:block;height:100%;width:auto;margin:0px auto" :src="$config.HOST+'/dokhlab/actions/image/temp.php?path='+tempImage.path+'&name='+tempImage.name" />
    </div>

    <div slot="footer" class="dialog-footer">
      <el-button @click="tempImage.visible=false">Cancel</el-button>
      <el-button type="primary" @click="setImage();tempImage.visible=false">Confirm</el-button>
    </div>
  </el-dialog>

  <!-- Context Menu -->
  <div :style="context.style">
    <ul v-if="context.node">
      <li class="context-menu-item" v-if="context.node.sons!==''" @click="newImageClick">New Image</li>
      <li class="context-menu-item" v-if="context.node.sons!==''" @click="newImageFolder">New Folder</li>
      <li class="context-menu-item" v-if="context.node.sons!==''" @click="renameImageFolder">Rename</li>
      <li class="context-menu-item" v-if="context.node.sons!==''" @click="deleteImageFolder">Delete</li>

      <!--
      <li class="context-menu-item" v-if="context.node.sons===''" @click="replaceClick">Replace</li>
      -->
      <li class="context-menu-item" v-if="context.node.sons===''" @click="renameImage">Rename</li>
      <li class="context-menu-item" v-if="context.node.sons===''" @click="deleteImage">Delete</li>
    </ul>
  </div>

  <!-- Image Dialog -->
  <el-dialog :title="image.name" :visible.sync="image.visible">
    <div style="height: 500px; overflow-x: auto">
      <img style="display:block;height:100%;width:auto;margin:0px auto" :src="$config.HOST+'/dokhlab/actions/image.php?name='+image.path" />
    </div>

    <div slot="footer" class="dialog-footer">
      <el-button @click="image.visible=false">Close</el-button>
      <el-button type="primary" @click="image.visible=false">Close</el-button>
    </div>
  </el-dialog>

  <!-- Update Dialog -->
  <el-dialog title="Update" :visible.sync="actions.update.dialogVisible">
    <div style="height: 500px; overflow-y: auto">
      <el-form :model="actions.update.record" label-width="80px">

        <!-- Event Title -->
        <el-form-item v-for="(col, name) in actions.update.record" :key="name" :label="name">
          <el-input v-model="actions.update.record[name]" auto-complete="off" :disabled="name===keys[actions.update.tableName]"></el-input>
        </el-form-item>
      </el-form>
    </div>

    <div slot="footer" class="dialog-footer">
      <el-button @click="actions.update.dialogVisible = false">Cancel</el-button>
      <el-button type="primary" @click="actions.update.dialogVisible = false; updateRecord()">Update</el-button>
    </div>
  </el-dialog>

  <!-- Insert Dialog -->
  <el-dialog title="Insert" :visible.sync="actions.insert.dialogVisible">
    <div style="height: 500px; overflow-y: auto">
      <el-form :model="actions.insert.record" label-width="80px">

        <!-- Event Title -->
        <el-form-item v-for="(col, name) in actions.insert.record" v-if="name!==keys[actions.insert.tableName]" :key="name" :label="name">
          <el-input v-model="actions.insert.record[name]" auto-complete="off"></el-input>
        </el-form-item>
      </el-form>
    </div>

    <div slot="footer" class="dialog-footer">
      <el-button @click="actions.insert.dialogVisible = false">Cancel</el-button>
      <el-button type="primary" @click="insertRecord()">Insert</el-button>
    </div>
  </el-dialog>

  <!-- Erase Dialog -->
  <el-dialog title="Delete" :visible.sync="actions.erase.dialogVisible">
    <div style="height: 500px; overflow-y: auto">
      <el-form :model="actions.erase.record" label-width="80px">

        <!-- Event Title -->
        <el-form-item v-for="(col, name) in actions.erase.record" :key="name" :label="name">
          <el-input v-model="actions.erase.record[name]" auto-complete="off" disabled></el-input>
        </el-form-item>
      </el-form>
    </div>

    <div slot="footer" class="dialog-footer">
      <el-button @click="actions.erase.dialogVisible = false">Cancel</el-button>
      <el-button type="primary" @click="eraseRecord()">Delete</el-button>
    </div>
  </el-dialog>

  <!-- Main panel -->
  <div style="font-size:13px;font-family: Geneva, arial, helvetica, sans-serif;border:1px solid #BBBBBB;width:800px;overflow:scroll;position:absolute;top:160px;bottom:0px">
    <div style="margin-top:20px;padding:0px;margin-left:10px">
      <!-- Layout -->
      <folder>
        <b>Layout</b>
        <div slot="items">
          <el-radio-group :value="frontConfig.layout.align">
            <el-radio @change="updateLayout('left')" label="left">Left</el-radio>
            <el-radio @change="updateLayout('center')" label="center">Center</el-radio>
            <el-radio @change="updateLayout('right')" label="right">Right</el-radio>
          </el-radio-group>
        </div>
      </folder>

      <!-- Home -->
      <folder>
        <b>Home</b>
        <div slot="items">
          <div style="padding-left:20px">
            <!-- Home source -->
            <div>
              <el-input @change="updateHomeSrc" :value="frontConfig.home.src">
                <template slot="prepend">Src:</template>
              </el-input>
            </div>

            <!-- Home edit link -->
            <div>
              <el-input @change="updateHomeEdit" :value="frontConfig.home.edit">
                <template slot="prepend">
                  <a :href="frontConfig.home.edit" target="_blank">Edit</a>
                </template>
              </el-input>
            </div>
          </div>
        </div>
      </folder>

      <!-- Research -->
      <folder>
        <b>Research</b>
        <div slot="items">
          <div style="padding-left:20px">
            <!-- Research source -->
            <div>
              <el-input @change="updateResearchSrc" :value="frontConfig.research.src">
                <template slot="prepend">Src:</template>
              </el-input>
            </div>

            <!-- Research edit link -->
            <div>
              <el-input @change="updateResearchEdit" :value="frontConfig.research.edit">
                <template slot="prepend">
                  <a :href="frontConfig.research.edit" target="_blank">Edit</a>
                </template>
              </el-input>
            </div>
          </div>
        </div>
      </folder>

      <!-- Tools -->
      <folder>
        <b>Tools</b>
        <div slot="items">
          <div style="padding-left:20px">
            <!-- Tools source -->
            <div>
              <el-input @change="updateToolsSrc" :value="frontConfig.tools.src">
                <template slot="prepend">Src:</template>
              </el-input>
            </div>

            <!-- Tools edit link -->
            <div>
              <el-input @change="updateToolsEdit" :value="frontConfig.tools.edit">
                <template slot="prepend">
                  <a :href="frontConfig.tools.edit" target="_blank">Edit</a>
                </template>
              </el-input>
            </div>
          </div>
        </div>
      </folder>

      <!-- Tables -->
      <ul v-for="tableName in tableNames">
        <folder>
          <b>{{tables[tableName].name}}</b>

          <div style="margin-bottom:30px" slot="items">
            <div style="padding-top:10px"><span style="cursor:pointer" @click="setInsert(tableName)"><i>Add Record...</i></span></div>

            <table style="border-collapse: collapse;" v-if="tables[tableName].data">
              <!-- Table Head -->
              <tr v-if="tables[tableName].data.length>0">
                <th></th>
                <th v-for="(col,name) in tables[tableName].data[0]" v-text="name"></th>
              </tr>

              <!-- Table Body -->
              <tr v-for="(row, rowIndex) in tables[tableName].data">
                <!-- Buttons -->
                <td>
                  <div :style="tableCellStyle">
                    <i style="cursor:pointer" @click="setUpdate(tableName, tables[tableName].data[rowIndex])" class="el-icon-edit"></i><i @click="setErase(tableName, tables[tableName].data[rowIndex])" style="cursor:pointer" class="el-icon-delete"></i>
                  </div>
                </td>

                <!-- Record Content -->
                <td v-for="(col,name) in tables[tableName].data[rowIndex]">
                  <div :style="tableCellStyle" v-text='tables[tableName].data[rowIndex][name]'></div>
                </td>
              </tr>
            </table>

            <div style="padding-top:10px"><span style="cursor:pointer" @click="setInsert(tableName)"><i>Add Record...</i></span></div>
          </div>
        </folder>
      </ul>

      <!-- Images -->
      <tree :content="images" root="../">
        <template slot-scope="slotProps">
          <span @contextmenu.stop="showContextMenu($event, slotProps.node, slotProps.path)" @click="if(slotProps.node.sons===''){image.name=slotProps.node.name;image.path=slotProps.path;image.visible=true}" class="tree-leaf"><b>{{slotProps.node.name}}</b></span>
        </template>
      </tree>

    </div>
  </div>
</div>
</template>

<script>
import { bus } from '../bus.js'
import axios from 'axios'
// import ajax from './ajax.js'

export default {
  name: 'Members',

  data () {
    return {
      context: {
        style: {
          width: '100px',
          position: 'absolute',
          left: '0px',
          top: '0px',
          zIndex: '99',
          display: 'none',
          backgroundColor: 'white',
          boxShadow: '0 24px 38px 3px rgba(0,0,0,0.14),0 9px 46px 8px rgba(0,0,0,0.12),0 11px 15px -7px rgba(0,0,0,0.2)',
          borderRadius: '2px'
        }
      },

      actions: {
        update: {
          tableName: '',
          record: {},
          dialogVisible: false
        },
        insert: {
          tableName: '',
          record: {},
          dialogVisible: false
        },
        erase: {
          tableName: '',
          record: {},
          dialogVisible: false
        }
      },

      tables: {
        mems: {
          data: [],
          key: 'mem_id',
          name: 'Members'
        },
        pubs: {
          data: [],
          key: 'pub_id',
          name: 'Publications'
        },
        users: {
          data: [],
          key: 'id',
          name: 'Users'
        }
      },

      images: {
        name: 'Images',
        sons: ''
      },

      image: {
        path: '',
        visible: false,
        name: ''
      },

      tempImage: {
        name: '',
        path: '',
        visible: false
      },

      config: {
        layout: {
          align: 'left'
        },

        home: {
          src: '',
          edit: ''
        },

        research: {
          src: '',
          edit: ''
        },

        tools: {
          src: '',
          edit: ''
        }
      }
    }
  },

  computed: {
    tableCellStyle () {
      return {
        whiteSpace: 'nowrap',
        overflow: 'hidden',
        textOverflow: 'ellipsis',
        minWidth: '50px',
        maxWidth: '200px'
      }
    },

    tableNames () {
      var v = this
      return Object.keys(v.tables)
    },

    keys () {
      var v = this
      var k = {}
      for (var i in v.tables) {
        k[i] = v.tables[i].key
      }
      return k
    },

    frontConfig () {
      return this.$store.state.frontConfig
    }
  },

  methods: {
    newImageClick () {
//      var v = this
//      v.tempImage.name = v.context.path + '/' + prompt('Please input an Image name:')
      window.document.getElementById('upload_image').click()
    },

    replaceClick () {
      var v = this
      v.tempImage.name = v.context.path
      window.document.getElementById('upload_image').click()
    },

    setTempImage (e) {
      var v = this
      let formData = new FormData()

      let f = e.target.files[0]
      formData.append('file', f)

      axios({
        method: 'post',
        url: v.$config.HOST + '/dokhlab/actions/image/upload.php',
        data: formData,
        config: {headers: {'Content-Type': 'multipart/form-data'}},
        withCredentials: true
      }).then(response => {
        v.tempImage.name = response.data.name
        v.tempImage.path = response.data.tmp_name
        v.tempImage.visible = true
      }).catch(() => {
        alert('Uploading Image Failed!')
      })
    },

    updateLayout (l) {
      var v = this
      var conf = v.frontConfig
      conf.layout.align = l
      v.$store.commit('updateFrontConfig', conf)
    },

    updateHomeSrc (l) {
      var v = this
      var conf = v.frontConfig
      conf.home.src = l
      v.$store.commit('updateFrontConfig', conf)
    },

    updateHomeEdit (l) {
      var v = this
      var conf = v.frontConfig
      conf.home.edit = l
      v.$store.commit('updateFrontConfig', conf)
    },

    updateResearchSrc (l) {
      var v = this
      var conf = v.frontConfig
      conf.research.src = l
      v.$store.commit('updateFrontConfig', conf)
    },

    updateResearchEdit (l) {
      var v = this
      var conf = v.frontConfig
      conf.research.edit = l
      v.$store.commit('updateFrontConfig', conf)
    },

    updateToolsSrc (l) {
      var v = this
      var conf = v.frontConfig
      conf.tools.src = l
      v.$store.commit('updateFrontConfig', conf)
    },

    updateToolsEdit (l) {
      var v = this
      var conf = v.frontConfig
      conf.tools.edit = l
      v.$store.commit('updateFrontConfig', conf)
    },

    setImage () {
      var v = this
      var name = v.context.path + '/' + v.tempImage.name
      axios({
        method: 'get',
        url: v.$config.HOST + `/dokhlab/actions/image/set.php?name=${name}&path=${v.tempImage.path}`,
        withCredentials: true
      }).then(response => {
        v.setImages()
      }).catch(error => {
        console.log(error.response)
        alert(`Set Image ${v.tempImage.name} failed!`)
      })
    },

    newImageFolder () {
      var v = this
      var folder = v.context.path + '/' + prompt('Please input a folder name:')
      axios({
        method: 'get',
        url: v.$config.HOST + `/dokhlab/actions/image/new_folder.php?name=${folder}`,
        withCredentials: true
      }).then(response => {
        v.setImages()
      }).catch(error => {
        console.log(error.response)
        alert(`Create New Image Folder ${folder} failed!`)
      })
    },

    renameImageFolder () {
      var v = this
      var oldname = v.context.path
      var newname = prompt('Please input a new folder name:')
      axios({
        method: 'get',
        url: v.$config.HOST + `/dokhlab/actions/image/rename.php?oldname=${oldname}&newname=${newname}`,
        withCredentials: true
      }).then(response => {
        v.setImages()
      }).catch(error => {
        console.log(error.response)
        alert(`Rename Image Folder ${oldname} failed!`)
      })
    },

    deleteImageFolder () {
      var v = this
      var r = confirm('Are you sure?')
      if (r === true) {
        axios({
          method: 'get',
          url: v.$config.HOST + `/dokhlab/actions/image/remove.php?name=${v.context.path}`,
          withCredentials: true
        }).then(response => {
          v.setImages()
        }).catch(error => {
          console.log(error.response)
          alert(`Delete Image Folder ${v.context.path} failed!`)
        })
      }
    },

    getFilePath (fileName) {
      return fileName.substring(0, fileName.lastIndexOf('/') + 1)
    },

    getBaseName (fileName) {
      return fileName.split(/[\\/]/).pop()
    },

    getFileExt (fileName) {
      var name = this.getBaseName(fileName)
      return name.substring(name.lastIndexOf('.'))
    },

    renameImage () {
      var v = this
      var oldname = v.context.path
      var newname = v.getFilePath(v.context.path) + '/' + prompt('Please input a new name:')
      if (v.getFileExt(oldname) === v.getFileExt(newname)) {
        axios({
          method: 'get',
          url: v.$config.HOST + `/dokhlab/actions/image/rename.php?oldname=${oldname}&newname=${newname}`,
          withCredentials: true
        }).then(response => {
          v.setImages()
        }).catch(error => {
          console.log(error.response)
          alert(`Rename Image ${oldname} failed!`)
        })
      } else {
        alert('The file extension must be the same!')
      }
    },

    deleteImage () {
      var v = this
      var r = confirm('Are you sure?')
      if (r === true) {
        axios({
          method: 'get',
          url: v.$config.HOST + `/dokhlab/actions/image/remove.php?name=${v.context.path}`,
          withCredentials: true
        }).then(response => {
          v.setImages()
        }).catch(error => {
          console.log(error.response)
          alert(`Delete Image ${v.context.path} failed!`)
        })
      }
    },

    showContextMenu (e, node, path) {
      console.log(e)

      var v = this

      e.preventDefault()

      var el = e.target.closest('span')
      var p = el.getBoundingClientRect()
      v.context.style.top = `${p.top + 3}px`
      v.context.style.left = `${p.left + el.offsetWidth + 3}px`
      v.context.style.display = 'block'

      v.context.node = node
      v.context.path = path
    },

    setUpdate (tableName, record) {
      var v = this
      v.actions.update = {
        tableName: tableName,
        record: record,
        dialogVisible: true
      }
    },

    setInsert (tableName) {
      var v = this
      v.actions.insert = {
        tableName: tableName,
        record: {},
        dialogVisible: true
      }
      for (var k in v.tables[tableName].data[0]) {
        v.actions.insert.record[k] = ''
      }
    },

    setErase (tableName, record) {
      var v = this
      v.actions.erase = {
        tableName: tableName,
        record: record,
        dialogVisible: true
      }
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

    updateRecord () {
      let v = this

      let formData = new FormData()

      // Add Table Name
      formData.append('__table', v.actions.update.tableName)
      var key = v.keys[v.actions.update.tableName]
      formData.append('__key', key)

      for (let i in v.actions.update.record) {
        formData.append(i, v.actions.update.record[i])
      }

      axios({
        method: 'post',
        url: v.$config.HOST + `/dokhlab/actions/update.php`,
        data: formData,
        config: {headers: {'Content-Type': 'multipart/form-data'}},
        withCredentials: true
      }).then(response => {
//        v.arrayAssignIf(v.tables[v.actions.update.tableName].data, (el, ind) => {
//          return el[key] === response.data[key]
//        }, response.data)
        v.getTable(v.actions.update.tableName)
        alert(`Update successfully!`)
        v.actions.update.dialogVisible = false
      }).catch(() => {
        alert(`Update failed!`)
        v.actions.update.dialogVisible = false
      })
    },

    insertRecord () {
      let v = this

      let formData = new FormData()
      formData.append('__table', v.actions.insert.tableName)

      var key = v.keys[v.actions.insert.tableName]
      for (let i in v.actions.insert.record) {
        if (i !== key) {
          formData.append(i, v.actions.insert.record[i])
        }
      }

      axios({
        method: 'post',
        url: v.$config.HOST + `/dokhlab/actions/insert.php`,
        data: formData,
        config: {headers: {'Content-Type': 'multipart/form-data'}},
        withCredentials: true
      }).then(response => {
        // Copy the data in the response to insert-record
//        for (var i in response.data) {
//          v.actions.insert.record[i] = response.data[i]
//        }
//        v.tables[v.actions.insert.tableName].data.push(v.actions.insert.record)

//        console.log(response)
        v.getTable(v.actions.insert.tableName)
        alert(`Insert successfully!`)
        v.actions.insert.dialogVisible = false
      }).catch(error => {
        console.log(error.response)
        alert(`Insert failed!`)
        v.actions.insert.dialogVisible = false
      })
    },

    eraseRecord () {
      let v = this

      let formData = new FormData()

      // Add Table Name
      formData.append('__table', v.actions.erase.tableName)

      // Add Key
      var key = v.keys[v.actions.erase.tableName]
      formData.append(key, v.actions.erase.record[key])

      axios({
        method: 'post',
        url: v.$config.HOST + `/dokhlab/actions/erase.php`,
        data: formData,
        config: {headers: {'Content-Type': 'multipart/form-data'}},
        withCredentials: true
      }).then(response => {
//        v.arrayEraseIf(v.tables[v.actions.erase.tableName].data, (el, ind) => {
//          return el[key] === v.actions.erase.record[key]
//        })
        v.getTable(v.actions.erase.tableName)
        alert(`Delete successfully!`)
        v.actions.erase.dialogVisible = false
      }).catch(() => {
        alert(`Delete failed!`)
        v.actions.erase.dialogVisible = false
      })
    },

    getTable (name) {
      var v = this

      axios({
        method: 'get',
        url: v.$config.HOST + `/dokhlab/actions/table.php?name=${name}`,
        withCredentials: true
      }).then(response => {
        v.tables[name].data = []
        for (var i = 0; i < response.data.length; i++) {
          v.tables[name].data.push(response.data[i])
        }
      }).catch(error => {
        console.log(error.response)
        alert(`Fetch Table ${name} failed!`)
      })
    },

    setImages () {
      var v = this

      axios({
        method: 'get',
        url: v.$config.HOST + `/dokhlab/actions/images.php`,
        withCredentials: true
      }).then(response => {
        v.images = response.data
        console.log(v.images)
      }).catch(() => {
        alert(`Fetch Images failed!`)
      })
    },

    documentClick (e) {
      if (e.which === 1) {
        this.context.style.display = 'none'
      }
    }
  },

  mounted () {
    var v = this

    this.$nextTick(function () {
      bus.$emit('switch-router', 'Members')
    })

    for (var i in v.tableNames) {
      v.getTable(v.tableNames[i])
    }

    v.setImages()
  },

  created () {
    document.addEventListener('click', this.documentClick)
  },

  destroyed () {
    // important to clean up!!
    document.removeEventListener('click', this.documentClick)
  }

}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="stylus" scoped>

theme-color = #666666

.theme-color {
  color: theme-color;
}

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

.button {
  cursor: pointer;
}

table tr:nth-child(even) {
  background-color: #f2f2f2
}

.tree-leaf {
  cursor: pointer;
}

.tree-leaf:hover {
  text-decoration: underline;
}

.context-menu-item {
  padding: 5px;
  font-size: 11px;
  cursor: pointer;
  font-weight: normal;
}

.context-menu-item:hover {
  background-color: theme-color;
  color: white;
  font-weight: bold;
}

.input-confirm {
  cursor: pointer;
  font-weight: normal;
}

.input-confirm:hover {
  font-weight: bold;
}

</style>
