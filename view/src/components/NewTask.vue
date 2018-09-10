<template>
<div class="new-task">
  <el-steps :active="step" style="cursor: pointer" finish-status="success">
    <el-step @click.native="step=0" :status="getStatus(0)" title="Upload Structure"></el-step>
    <el-step @click.native="step=1" :status="getStatus(1)" title="Set Parameters"></el-step>
    <el-step @click.native="step=2" :status="getStatus(2)" title="Submit"></el-step>
  </el-steps>

  <div id="input-area" style="height: 500px">
    <div v-show="step===0">
      <!-- Select the structure -->
      <div v-show="step===0">
        <div class="label">Please
          <a class="jn-button" onclick="window.document.getElementById('receptor_pdb').click()">upload the structure</a>
          <el-tooltip class="item" effect="dark" content="Only PDB format is supported now." placement="bottom">
            <i class="el-icon-question"></i>
          </el-tooltip>
          or
          <el-popover ref="popover-pdbid" placement="bottom-start" v-model="showPdbIdInput" width="400" trigger="click">
            <table>
              <tr>
                <td><el-input @keyup.enter.native="setReceptor()" ref="pdbInput" v-model="pdbid" placeholder="PDB ID"></el-input></td>
                <td><el-button type="success" plain @click="setReceptor()">Confirm</el-button></td>
              </tr>
            </table>
          </el-popover>
          <a class="jn-button" v-popover:popover-pdbid>input the PDB ID</a>
          <el-tooltip class="item" effect="dark" content="Example: 5i3y" placement="bottom">
            <i class="el-icon-question"></i>
          </el-tooltip>
          of the protein.
        </div>
        <input type="file" id="receptor_pdb" ref="receptor_pdb" @change="setReceptor($event)" style="display:None;">
      </div>

      <!-- Viewport -->
      <div style="position:relative">
        <!-- Picking Menu -->
        <div :style="pickingMenuStyle">
          <ul class="picking-menu">
            <li class="picking-menu-item picking-menu-item-text">{{pickedResidue}}</li>
            <li class="picking-menu-item picking-menu-item-button">Set as Source</li>
            <li class="picking-menu-item picking-menu-item-button">Set as Target</li>
          </ul>
        </div>
        <!-- Cover -->
        <div id="cover" v-show="showCover">Loading the Structure<i class="el-icon-loading"></i></div>

        <!-- Names -->
        <div id="structure-name" v-if="step===0">
          <div v-if="structureName!==''">Structure: <a :href="$config.HOST+`/allos/actions/file.php?name=${structureName}&format=`" v-text="structureName"></a></div>
          <div v-if="structureName!==''&&form.source_chain!==''&&form.source_residue!==''">Source: {{`${form.source_chain}-${form.source_residue}`}}</div>
          <div v-if="structureName!==''&&form.target_chain!==''&&form.target_residue!==''">Target: {{`${form.target_chain}-${form.target_residue}`}}</div>
        </div>

        <!-- More -->
        <div style="position: absolute; top: 8px; right: 15px; z-index: 99; font-size: 18px; line-height: 35px" v-if="step===0&&structureName!==''">
          <more>
            <more-button><i class="el-icon-more"></i></more-button>

            <more-dropdown placement="bottom-right">
              <more-menuitem>
                Show
                <more-submenu>
                  <more-menuitem @click.native="toggleRepresentation('cartoon')">Cartoon <i v-if="checkRepresentation('cartoon')" class="el-icon-check"></i></more-menuitem>
                  <more-menuitem @click.native="toggleRepresentation('surface')">Surface <i v-if="checkRepresentation('surface')" class="el-icon-check"></i></more-menuitem>
                  <more-menuitem @click.native="toggleRepresentation('ball+stick')">Sticks <i v-if="checkRepresentation('ball+stick')" class="el-icon-check"></i></more-menuitem>
                  <more-menuitem @click.native="toggleRepresentation('label')">Label <i v-if="checkRepresentation('label')" class="el-icon-check"></i></more-menuitem>
                </more-submenu>
              </more-menuitem>

              <more-menuitem :href="$config.HOST + `/allos/actions/file.php?name=${structureName}&format=`">
                Download
              </more-menuitem>
            </more-dropdown>
          </more>
        </div>

        <div id="viewport"></div>

      </div>

    </div>

    <!-- Set parameters -->
    <div style="position: relative" v-show="step===1">
      <div>
        <div class="label">Task Title</div>
        <el-input @change="useDefaultTitle=(form.title===defaultTitle)" v-model="form.title" style="width: 400px"></el-input>
      </div>

      <div v-if="!user">
        <div class="label">Email Address</div>
        <el-input v-model="form.email" style="width: 400px"></el-input>
      </div>

      <table>
        <!-- from and to -->
        <tr>
          <td>
            <div class="label">From
              <el-tooltip class="item" effect="dark" placement="bottom">
              <div slot="content">From which residue is the allosteric path to be calculated.</div><i class="el-icon-question"></i>
              </el-tooltip>
            </div>
          </td>
          <td>
            <div class="label">To
              <el-tooltip class="item" effect="dark" placement="bottom">
              <div slot="content">To which residue is the allosteric path to be calculated.</div><i class="el-icon-question"></i>
              </el-tooltip>
            </div>
          </td>
          <td>
              <div class="label">At Chains:
                  <el-tooltip class="item" effect="dark" placement="bottom">
                  <div slot="content">Example: A,B,C</div><i class="el-icon-question"></i>
                  </el-tooltip>
              </div>
          </td>
      </tr>
        <tr>
          <td>Chain <el-input @change="form.title=(useDefaultTitle?defaultTitle:form.title)" v-model="form.source_chain" style="width: 50px"></el-input><br>Residue No.<el-input @change="form.title=(useDefaultTitle?defaultTitle:form.title)" v-model="form.source_residue" style="width: 100px"></el-input></td>
          <td>Chain <el-input @change="form.title=(useDefaultTitle?defaultTitle:form.title)" v-model="form.target_chain" style="width: 50px"></el-input><br>Residue No.<el-input @change="form.title=(useDefaultTitle?defaultTitle:form.title)" v-model="form.target_residue" style="width: 100px"></el-input></td>
          <td><el-input v-model="form.chains" style="width: 150px"></el-input></td>
        </tr>

        <!-- k cutoff threshold -->
        <tr>
          <td><div class="label">K
              <el-tooltip class="item" effect="dark" placement="bottom">
              <div slot="content">Number of Clusters</div><i class="el-icon-question"></i>
              </el-tooltip>
            </div>
          </td>
          <td><div class="label">Cutoff
              <el-tooltip class="item" effect="dark" placement="bottom">
              <div slot="content">Distance in A to count a contact between atoms</div><i class="el-icon-question"></i>
              </el-tooltip>
            </div>
          </td>
          <td><div class="label">Threshold
              <el-tooltip class="item" effect="dark" placement="bottom">
              <div slot="content">To eliminate residues that have less than certain number fo contacts, for example if you want llok only into the core or remove loops</div><i class="el-icon-question"></i>
              </el-tooltip>
            </div>
          </td>
        </tr>
        <tr>
          <td><el-input v-model="form.k" style="width: 150px"></el-input></td>
          <td><el-input v-model="form.cutoff" style="width: 150px"></el-input></td>
          <td><el-input v-model="form.threshold" style="width: 150px"></el-input></td>
        </tr>
      </table>

      <div></div>
      <div style="margin-top: 30px; float: right">
        <el-button type="success" @click="submit">Submit<i class="el-icon-arrow-right el-icon--right"></i></el-button>
      </div>
      <div style="clear: both"></div>
    </div>
  </div>

  <el-dialog :visible.sync="show_dialog" width="30%">
    <span>Submitted successfully!</span>
    <span slot="footer" class="dialog-footer">
    <el-button @click="show_dialog = false">New Task</el-button>
    <el-button type="primary" @click="show_dialog = false">Task Status</el-button>
    </span>
  </el-dialog>
</div>

</template>

<script>
import axios from 'axios'
import { bus } from '../bus.js'
import * as NGL from 'ngl'
import { Vector3, Matrix4 } from 'three'
// import { sprintf } from 'sprintf-js'

axios.defaults.headers.post['Content-Type'] = 'multipart/form-data'

export default {
  name: 'Index',
  data () {
    return {
      reprs: {}, // Representations
      representationVisibilities: {}, // Representation Visibilities
      structureName: '',

      pdbid: '',
      showCover: false,
      showPdbIdInput: false,
      step: 0,

      stage: '',

      show_dialog: false,
      show_options: false,

      useDefaultTitle: true,

      form: {
        email: '',
        title: 'A5-A25',
        chains: 'A',
        source_chain: 'A',
        target_chain: 'A',
        source_residue: 5,
        target_residue: 25,
        k: 500,
        cutoff: 4.0,
        threshold: 1.0
      },

      pickedResidue: '',
      pickingMenuStyle: {
        display: 'none',
        position: 'fixed',
        zIndex: 99,
//        pointerEvents: 'none',
//        backgroundColor: 'rgba( 1, 1, 1, 0.6 )',
        backgroundColor: 'white',
//        color: 'lightgrey',
        padding: '0px',
        fontFamily: 'sans-serif'
      }
    }
  },

  computed: {
    user () {
      return this.$store.state.user
    },

    defaultTitle () {
      var v = this
      return `${v.form.source_chain}${v.form.source_residue}-${v.form.target_chain}${v.form.target_residue}`
    }
  },

  methods: {
    toggleRepresentation (t) {
      var v = this
      v.showCover = true
      console.log(`toggle ${t}`)
      if (t in v.reprs && v.reprs[t] != null) {
        v.reprs[t].toggleVisibility()
      } else {
        if (t === 'label') {
          v.reprs[t] = v.comp.addRepresentation(t, {
            sele: '.CA',
            color: 'grey',
            radiusScale: 0.7,
            fixedSize: true,
            labelType: 'format',
            labelFormat: '%(chainname)s%(resno)s'
//            labelFormat: '%(resname)s %(chainname)s%(resno)s'
          })
        } else {
          v.reprs[t] = v.comp.addRepresentation(t, { probeRadius: 1.5, multipleBond: true })
        }
      }
      v.checkRenderStatus(function () {
        v.representationVisibilities[t] = v.reprs[t].getVisibility()
        v.showCover = false
      })
    },

    checkRepresentation (t) {
      var v = this
      return (t in v.reprs) && (v.reprs[t] != null) && (v.representationVisibilities[t])
    },

    getStatus (n) {
      var v = this
      if (n === v.step) {
        return 'process'
      } else {
        if (n === 0) {
          if (v.comp) {
            return 'success'
          } else if (n > v.step) {
            return 'wait'
          } else {
            return 'error'
          }
        } else if (n === 1) {
          if (n < v.step) {
            return 'success'
          } else {
            return 'wait'
          }
        }
      }
    },

    next () {
      var v = this
      v.step += 1
    },

    prev () {
      var v = this
      v.step -= 1
    },

    checkRenderStatus (cb) {
      var v = this
      setTimeout(function () {
        var c = v.stage.tasks.count
        console.log(c)
        if (c !== 0) {
          v.checkRenderStatus(cb)
        } else {
          cb()
        }
      }, 500)
    },

    setReceptor () {
      let v = this
      let formData = new FormData()

      console.log(arguments)
      if (arguments.length > 0) {
        let f = arguments[0].target.files[0]
        formData.append('file', f)
      } else if (v.pdbid !== '') {
        formData.append('pdbid', v.pdbid)
      } else {
        alert('Please specify the receptor!')
        return
      }

      v.showCover = true
      v.showPdbIdInput = false
      v.stage.removeComponent(v.comp)
      v.reprs = []

      axios({
        method: 'post',
        url: v.$config.HOST + '/allos/actions/set_structure.php',
        data: formData,
        config: {headers: {'Content-Type': 'multipart/form-data'}}
      }).then(response => {
        v.structureName = response.data
        v.stage.loadFile(v.$config.HOST + `/allos/actions/file.php?name=${v.structureName}&format=`, { ext: v.structureName.split('.').pop() }).then(function (comp) {
          v.comp = comp
          v.toggleRepresentation('cartoon')
          v.checkRenderStatus(function () {
            v.stage.autoView()
            v.showCover = false
          })
        })
      }).catch(() => {
        alert('Pre-processing Structure Failed!')
      })
    },

    panComponentXY (x, y) {
      let v = this
      const tmpPanMatrix = new Matrix4()
      const tmpPanVector = new Vector3()

      let t = v.stage.trackballControls

      const scaleFactor = t.controls.getCanvasScaleFactor(0)
      tmpPanVector.set(x, y, 0)
      tmpPanVector.multiplyScalar(scaleFactor)

      tmpPanMatrix.extractRotation(t.component.transform)
      tmpPanMatrix.premultiply(t.viewer.rotationGroup.matrix)
      tmpPanMatrix.getInverse(tmpPanMatrix)
      tmpPanVector.applyMatrix4(tmpPanMatrix)

      t.component.position.add(tmpPanVector)
      t.component.updateMatrix()

      v.colorPocket()
    },

    panComponentZ (x, y) {
      let v = this
      const tmpPanMatrix = new Matrix4()
      const tmpPanVector = new Vector3()

      let t = v.stage.trackballControls

      let r = Math.sqrt(x * x + y * y)
      r = (y > 0 ? r : -r)
      const scaleFactor = t.controls.getCanvasScaleFactor(0)
      tmpPanVector.set(0, 0, r)
      tmpPanVector.multiplyScalar(scaleFactor)

      tmpPanMatrix.extractRotation(t.component.transform)
      tmpPanMatrix.premultiply(t.viewer.rotationGroup.matrix)
      tmpPanMatrix.getInverse(tmpPanMatrix)
      tmpPanVector.applyMatrix4(tmpPanMatrix)

      t.component.position.add(tmpPanVector)
      t.component.updateMatrix()

      v.colorPocket()
    },

    submit () {
      let v = this
      let formData = new FormData()

      if (v.user) {
        v.form.email = v.user.email
      }

      for (let i in v.form) {
        if (('' + v.form[i]).trim() === '') {
          alert('Please provide the ' + i)
          return
        }
        formData.append(i, v.form[i])
      }

      if (v.comp) {
        formData.append('receptor', v.structureName)
      } else {
        alert('Please provide the structure!')
        return
      }

      axios({
        method: 'post',
        url: v.$config.HOST + '/allos/actions/submit.php',
        data: formData,
        config: {headers: {'Content-Type': 'multipart/form-data'}}
      }).then(response => {
        alert('Submitted successfully!')
        window.location.href = '#/' + 'TaskManagement'
      }).catch(() => {
        alert('Submitted failed!')
      })
    },

    hidePickingMenu () {
      var v = this

      v.pickingMenuStyle.display = 'none'
    },

    resetPickingMenu (stage, x, y) {
      var v = this

      v.hidePickingMenu()

      var pickingProxy = stage.pickingControls.pick(x, y)
      console.log(`X: ${x}, Y: ${y}`)
      console.log(pickingProxy)
      if (pickingProxy && (pickingProxy.atom || pickingProxy.bond)) {
        var atom = pickingProxy.atom || pickingProxy.closestBondAtom
        var mp = pickingProxy.mouse.position
        v.pickedResidue = `${atom.chainname}-${atom.resno}[${atom.resname}]`
//        v.pickingMenuStyle.bottom = window.innerHeight - mp.y + 3 + 'px'
        v.pickingMenuStyle.top = mp.y + 3 + 'px'
        v.pickingMenuStyle.left = mp.x + 10 + 'px'
        v.pickingMenuStyle.display = 'block'
      } else {
        v.hidePickingMenu()
      }
    },

    resetRightClick () {
      var v = this

      // remove default hoverPick mouse action
      v.stage.mouseControls.remove('click-right')

      // listen to `hovered` signal to move tooltip around and change its text
      v.stage.mouseControls.add('click-right', function (stage, x, y) {
        v.resetPickingMenu(stage, x, y)
      })
    },

    resetRightClickPick () {
      var v = this

      // remove default hoverPick mouse action
      v.stage.mouseControls.remove('clickPick-right')
    },

    resetClick () {
      var v = this

      // remove default click mouse action
      v.stage.mouseControls.remove('click-left')

      // listen to `click` signal
      v.stage.mouseControls.add('click-left', function (stage, x, y) {
        v.hidePickingMenu()
      })
    }
  },

  mounted () {
    let v = this

    v.$nextTick(function () {
      bus.$emit('switch-router', 'NewTask')

      v.stage = new NGL.Stage('viewport', { backgroundColor: 'white' })
      v.stage.mouseControls.remove('drag-left-ctrl')
      v.stage.mouseControls.add('drag-left-ctrl', function (stage, x, y) {
        v.panComponentXY(x, y)
      })
      v.stage.mouseControls.remove('drag-right-ctrl')
      v.stage.mouseControls.add('drag-right-ctrl', function (stage, x, y) {
        v.panComponentZ(x, y)
      })

      // remove default hoverPick mouse action
      v.stage.mouseControls.remove('hoverPick')

      v.resetRightClick()
      v.resetRightClickPick()
    })
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

.new-task {
  padding-top: 30px;
}

i.prompt {
  font-size: 12px;
}

.el-form-item {
  margin-bottom: 3px;
}

#input-area {
  margin-top: 10px;
}

.label {
  font-size: 14px;
  height: 40px;
  line-height: 40px;
  vertical-align: middle;
}

#viewport {
  width: 720px;
  height: 450px;
  margin: 0px auto 0px 0px;
  box-shadow: 0 0 5px #888888;
}

#structure-name {
  font-size: 16px;
  color: rgb(180, 188, 204);
  background-color: rgb(0, 0, 0, 0);
  position: absolute;
  left: 0;
  top: 0;
  padding: 10px;
  z-index: 98;
}

#cover {
  font-size: 30px;
  width: 720px;
  height: 450px;
  line-height: 450px;
  vertical-align: middle;
  text-align: center;
  color: rgb(180, 188, 204);
  position: absolute;
  left: 0;
  top: 0;
  z-index: 99;
}

.residue-label {
  cursor: pointer;
  color: rgb(180, 188, 204);
  font-family: consolas, manaco, monospace;
  font-size: 14px;
  height: 30px;
  width: 80px;
  float: left
}

.residue-label.residue-selected {
  font-weight: 600;
  color: black;
}

.residue-label-res {
  width: 55px;
  float: left;
  text-align: right;
}

.residue-label-atom {
  width: 25px;
  float: left;
  text-align: left;
}

.atom-label {
  cursor: pointer;
  width: 100%;
  font-size: 12px;
  height: 20px;
}

.atom-label:hover {
  /*
  background-color: #e4ecf4;
  */
  background-color: #bfcddc;
}

.constraint-label {
  cursor: pointer;
  padding-left: 10px;
  font-size: 12px;
}

.constraint-label:hover {
  background-color: #bfcddc;
}

.constraint-label.constraint-selected {
  background-color: #bfcddc;
}

.picking-menu {
  padding: 0px;
  font-size: 12px;
}

.picking-menu-item {
  padding: 8px;
  margin: 0px;
}

.picking-menu-item.picking-menu-item-button {
  background-color: white;
  font-weight: normal;
  cursor: pointer;
}

.picking-menu-item.picking-menu-item-button:hover {
  background-color: #67c23a;
  font-weight: bold;
  color: white;
}

</style>
