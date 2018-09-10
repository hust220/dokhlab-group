<template>
<div class="task">
  <div style="margin: 20px 0px">Status of Task {{id}}: {{task.status}} ( <a style="cursor: pointer" type="text" @click="taskPanelVisible = true">parameters</a> | <a style="cursor: pointer" type="text" @click="logVisible = true">log</a>)</div>

  <div style="position:relative">
    <div class="header">
      <!-- switch -->
      <div class="switch-menu">
        <el-dropdown style="cursor: pointer" placement="bottom-start">
          <span class="el-dropdown-link">
            {{ figureTypes[figureType] }} <i class="el-icon-arrow-down el-icon--right"></i>
          </span>

          <el-dropdown-menu slot="dropdown">
            <el-dropdown-item @click.native="setFigureType(0)">Paths</el-dropdown-item>
            <el-dropdown-item @click.native="setFigureType(1)">Dendrogram</el-dropdown-item>
            <!--
            <el-dropdown-item @click.native="setFigureType(2)">b Factor</el-dropdown-item>
            -->
          </el-dropdown-menu>
        </el-dropdown>
      </div>

      <!-- path pagination -->
      <div class="path-pagination" v-show="figureType===0&&pathComps.length>0">
        <i class="el-icon-arrow-left" @click="setPath(Math.max(0, pathIndex-1))" style="cursor: pointer"></i>
        Path {{ pathIndex + 1 }} / {{ pathComps.length }}
        <i class="el-icon-arrow-right" @click="setPath(Math.min(pathComps.length-1, pathIndex+1))" style="cursor: pointer"></i>
      </div>

      <!-- more -->
      <div style="line-height: 35px; float: right; z-index: 99" v-show="figureType===0&&pathComps.length>0">
        <more>
          <more-button><i class="el-icon-more"></i></more-button>

          <more-dropdown placement="bottom-right">
            <!-- Style -->
            <more-menuitem>
              All
              <more-submenu>
                <more-menuitem v-for="style in ['cartoon', 'surface', 'ball+stick', 'backbone', 'hyperball', 'line', 'licorice', 'point', 'ribbon', 'rope', 'spacefill', 'trace', 'tube']" :key="style">
                  <div @click="toggleRepresentation(style)">{{style}} <i v-show="representationVisibilities[style]" class="el-icon-check"></i></div>
                  <more-submenu v-show="representationVisibilities[style]">
                    <!-- Color -->
                    <more-menuitem>
                      Color
                      <more-submenu>
                        <!-- Custom Color -->
                        <more-menuitem>
                          Custom Color
                          <more-submenu>
                            <div style="width: 300px">
                              <swatches @input="val=>representations[style].setColor(val)" colors="material-basic" inline show-fallback />
                            </div>
                          </more-submenu>
                        </more-menuitem>

                        <more-menuitem v-for="colorScheme in ['atomindex', 'bfactor', 'chainname', 'element', 'sstruc', 'electrostatic']" :key="colorScheme" @click.native="representations[style].setParameters({colorScheme})">
                          By {{colorScheme}}
                        </more-menuitem>
                      </more-submenu>
                    </more-menuitem>

                    <!-- Opacity -->
                    <more-menuitem>
                      Opacity
                      <more-submenu>
                        <div style="width: 300px; padding: 0px 20px">
                          <el-slider v-model="opacities[style]" @change="val=>{representations[style].setParameters({opacity:1-parseFloat(val)/100.0})}" show-input></el-slider>
                        </div>
                      </more-submenu>
                    </more-menuitem>

                  </more-submenu>
                </more-menuitem>
              </more-submenu>
            </more-menuitem>

            <!-- Path -->
            <more-menuitem>
              <div @click="togglePath">Path <i v-if="showPath" class="el-icon-check"></i></div>
              <more-submenu v-if="showPath">
                <!-- Color -->
                <more-menuitem>
                  Color
                  <more-submenu>
                    <div style="width: 300px">
                      <swatches @input="val=>reloadPaths(val)" colors="material-basic" inline show-fallback />
                    </div>
                  </more-submenu>
                </more-menuitem>

                <!-- Label -->
                <more-menuitem>
                  <div @click="doLabelPath^=true; labelPath()">Label <i v-if="doLabelPath" class="el-icon-check"></i></div>
                  <more-submenu v-if="doLabelPath">
                    <!-- Color -->
                    <more-menuitem>
                      Color
                      <more-submenu>
                        <div style="width: 300px">
                          <swatches @input="val=>{for(var r in labelReprs){labelReprs[r].setParameters({color:val})}}" colors="material-basic" inline show-fallback />
                        </div>
                      </more-submenu>
                    </more-menuitem>

                    <!-- Font size -->
                    <more-menuitem>
                      Font Size
                      <more-submenu>
                        <div style="width: 300px; padding: 0px 20px">
                          <el-slider v-model="labelFontSize" @change="val=>{for(var r in labelReprs){labelReprs[r].setParameters({radiusScale:val/100.0+0.4})}}" show-input></el-slider>
                        </div>
                      </more-submenu>
                    </more-menuitem>

                  </more-submenu>
                </more-menuitem>

                <!-- Details -->
                <more-menuitem>
                  <div @click="showPathDetails^=true">Details <i v-if="showPathDetails" class="el-icon-check"></i></div>
                </more-menuitem>
              </more-submenu>
            </more-menuitem>

            <!-- View -->
            <more-menuitem>
              View
              <more-submenu>
                <more-menuitem @click.native="pdbComp.autoView()">All</more-menuitem>
                <more-menuitem @click.native="pdbComp.autoView(pathSelection(''))">Path</more-menuitem>
              </more-submenu>
            </more-menuitem>

            <!-- Re-calculate -->
            <more-menuitem>
              <div>Re-Calculate</div>
              <more-submenu>
                <div style="padding: 20px">
                  <table>
                    <tr><td><el-input v-model="cutoff" style="width: 150px"></el-input></td><td><el-button type="success" @click="submit">Submit</el-button></td></tr>
                  </table>
                </div>
              </more-submenu>
            </more-menuitem>

            <!-- Screenshot -->
            <more-menuitem>
              <div @click="screenShot">Screenshot</div>
            </more-menuitem>

            <!-- Download -->
            <more-menuitem>
              <div>Download</div>
              <more-submenu>
                <more-menuitem :href="$config.HOST + `/allos/actions/file.php?name=${task.pse}&format=`">
                  Pymol Session
                </more-menuitem>
                <more-menuitem :href="$config.HOST + `/allos/actions/file.php?name=${task.output}&format=`">
                  Paths Info
                </more-menuitem>
              </more-submenu>
            </more-menuitem>

          </more-dropdown>
        </more>
      </div>

      <!-- download -->
      <div class="download-button" v-show="figureType===2"><a :href="$config.HOST + `/allos/actions/file.php?name=${task.bfactor}&format=`">Download</a></div>

      <div style="clear: both"></div>
    </div>

    <!-- Cover -->
    <div id="cover" v-show="showCover">{{coverText}}<i class="el-icon-loading"></i></div>

    <!-- Cutoff Input -->
    <div class="viewport" v-show="figureType===0&&!task.path_cutoff">
      <div id="cutoff-input">
        <p>Please Input the Cutoff based on the <a style="cursor:pointer" @click="setFigureType(1)">Dendrogram</a>: <el-input v-model="cutoff" style="width: 150px"></el-input></p>
        <p><el-button type="success" @click="submit">Submit</i></el-button></p>
      </div>
    </div>

    <!-- Dendrogram -->
    <div class="viewport" v-show="figureType==1">
      <div id="dendrogram" :style="dendrogramStyle">
        <!-- dendrogram fullscreen button -->
        <div class="dendrogram-fullscreen" @click="dendrogramFullscreen^=true" v-show="figureType===1"><i class="el-icon-rank"></i></div>

        <!-- Dendrogram Image -->
        <div style="width: 100%; height: 100%; overflow: scroll; text-align: center">
          <img :src="dendrogramSrc">
        </div>
      </div>
    </div>

    <!-- b factor -->
    <div class="viewport" id="b-factor" v-show="(figureType==0&&task.path_cutoff)||figureType==2"></div>

    <!-- Path text -->
    <div class="righter" v-if="figureType===0&&pathNames.length > 0&&showPathDetails">
      <table style="text-align:center">
        <tr>
          <th class="path-residue-name">Residue</th>
          <th class="path-residue-paths"># Paths</th>
          <th class="path-residue-flow">Flow</th>
        </tr>
        <tr v-for="residue in pathNames[pathIndex]">
          <td @click="pdbComp.autoView(`${residue.num}:${residue.chain}`)" class="path-residue-name">{{residue.chain}}{{residue.num}}</td>
          <td class="path-residue-paths">{{residue.paths}}</td>
          <td class="path-residue-flow">{{residue.flow}}</td>
        </tr>
      </table>
    </div>
  </div>

  <!-- Task Parameters Panel -->
  <el-dialog :title="'Log of Task '+id" :visible.sync="taskPanelVisible" width="40%">
    <table>
      <tr><td>Task ID</td><td>{{task.id}}</td></tr>
      <tr><td>Structure</td><td><a :href="$config.HOST + `/allos/actions/file.php?name=${task.receptor}&format=`">{{task.receptor}}</a></td></tr>
      <tr><td>Source</td><td>{{`${task.source_chain}-${task.source_residue}`}}</td></tr>
      <tr><td>Target</td><td>{{`${task.target_chain}-${task.target_residue}`}}</td></tr>
      <tr><td>Threshold</td><td>{{task.threshold}}</td></tr>
      <tr><td>Cutoff</td><td>{{task.cutoff}}</td></tr>
      <tr><td>K</td><td>{{task.k}}</td></tr>
      <tr><td>Path Cutoff</td><td>{{task.path_cutoff}}</td></tr>
      <tr><td>Bfactor Structure</td><td><a :href="$config.HOST + `/allos/actions/file.php?name=${task.bfactor}&format=`">{{task.bfactor}}</a></td></tr>
      <tr><td>Dendrogram</td><td><a :href="$config.HOST + `/allos/actions/file.php?name=${task.dendrogram}&format=`">{{task.dendrogram}}</a></td></tr>
      <tr><td>Pymol Session</td><td><a :href="$config.HOST + `/allos/actions/file.php?name=${task.pse}&format=`">{{task.pse}}</a></td></tr>
      <tr><td>Paths Info</td><td><a :href="$config.HOST + `/allos/actions/file.php?name=${task.output}&format=`">{{task.output}}</a></td></tr>
      <tr></tr>
    </table>
    <span slot="footer" class="dialog-footer">
      <el-button type="success" @click="taskPanelVisible=false">Close</el-button>
    </span>
  </el-dialog>

  <!-- Task Log Panel -->
  <el-dialog :title="'Log of Task '+id" :visible.sync="logVisible" width="60%">
    <pre style="height: 450px; overflow: auto">{{log}}</pre>
    <span slot="footer" class="dialog-footer">
      <el-button type="success" @click="logVisible=false">Close</el-button>
    </span>
  </el-dialog>

</div>
</template>

<script>
import axios from 'axios'
import * as NGL from 'ngl'
import { bus } from '../bus.js'
import Swatches from 'vue-swatches'
import 'vue-swatches/dist/vue-swatches.min.css'

axios.defaults.headers.post['Content-Type'] = 'multipart/form-data'

export default {
  name: 'Task',

  components: { Swatches },

  data () {
    return {
      labelFontSize: 30,
      opacities: {},
      labelColor: '#1CA085',
      figureTypes: ['Paths', 'Dendrogram', 'b Factor'],
      figureType: 2,
      dendrogramSrc: '',
      showCover: false,
      logVisible: false,
      taskPanelVisible: false,
      stage: '',
      cutoff: 5,
      task: {},
      currentModel: 0,
      numModels: 0,
      receptors: [],
      ligands: [],
      energies: [],
      log: '',
      output: '',

      labelReprs: [],
      pathComps: [],
      pathReprs: [],
      pathNames: [],
      pathIndex: 0,
      paths: [],
      coords: [],
      residueNames: [],
      residueIndexMap: {},

      dendrogramFullscreen: false,

      showPathDetails: false,
      showPath: true,
      doLabelPath: false,

      coverText: 'Loading...',

      representations: {},
      representationVisibilities: {},
      colors: {}
    }
  },

  computed: {
    id () {
      return this.$route.params.id
    },

    dendrogramStyle () {
      var v = this
      if (v.dendrogramFullscreen) {
        return {
          position: 'fixed',
          width: '100%',
          height: '100%',
          left: '0',
          top: '0',
//          background: 'rgba(51,51,51,0.7)',
          background: 'white',
          zIndex: '99'
        }
      } else {
        return {
          position: 'relative',
          height: '450px',
          width: '720px'
        }
      }
    }
  },

  watch: {
    colors (val) {
      var v = this
      for (var i in v.representationVisibilities) {
        if (v.representationVisibilities[i]) {
          v.representations[i].setColor(v.colors[i])
        }
      }
    }
  },

  methods: {
    screenShot () {
      var v = this
      v.stage.makeImage({
        factor: 5
//        antialias: antialiasCheckbox.getValue(),
//        trim: trimCheckbox.getValue(),
//        transparent: transparentCheckbox.getValue(),
//        onProgress: onProgress
      }).then(function (blob) {
        NGL.download(blob, 'screenshot.png')
      })
    },

    togglePath () {
      var v = this
      if (v.showPath) {
        v.showPath = false
        v.pathComps.forEach(function (comp) {
          comp.setVisibility(false)
        })
      } else {
        v.showPath = true
        v.setPath(v.pathIndex)
      }
    },

    toggleRepresentation (t) {
      var v = this
      v.coverText = 'Changing Representation...'
      v.showCover = true
//      console.log(`toggle ${t}`)
      if (t in v.representations) {
        var visible = (v.representationVisibilities[t] === false)
        v.representations[t].setVisibility(visible)
        v.checkRenderStatus(function () {
          v.showCover = false
          v.representationVisibilities[t] = visible
//          console.log(`${t} set to ${v.representationVisibilities[t]}`)
        })
      } else {
        var option = {}
        if (t === 'surface') {
          option.color = 'white'
        } else {
          option.colorScheme = 'bfactor'
          option.radiusType = 'sstruc'
          option.radiusScale = 1.0
        }
        v.representations[t] = v.pdbComp.addRepresentation(t, option)
        v.checkRenderStatus(function () {
          v.showCover = false
          v.representationVisibilities[t] = true
//          console.log(`add representation ${t}`)
        })
      }
//      console.log(`Representation of ${t}: ${v.representationVisibilities[t]}`)
    },

    labelPath () {
      var v = this
      if (v.labelReprs.length > 0) {
        v.labelReprs.forEach(r => r.setVisibility(false))
        if (v.doLabelPath) {
          v.labelReprs[v.pathIndex].setVisibility(true)
        }
      }
    },

    setPath (i) {
      var v = this
      if (v.pathComps.length > 0) {
//        console.log(`Set Path: ${i + 1}`)
        v.pathComps.forEach(function (comp) {
          comp.setVisibility(false)
        })
        v.pathIndex = i
        v.pathComps[i].setVisibility(true)
      }
      v.labelPath()
    },

    setFigureType (t) {
      var v = this
      v.figureType = t
      if (t === 0) {
        v.setPath(v.pathIndex)
        if (v.sstructRepr) v.sstructRepr.setVisibility(true)
        if (v.bfactorRepr) v.bfactorRepr.setVisibility(false)
      } else if (t === 2) {
        v.pathComps.forEach(function (comp) {
          comp.setVisibility(false)
        })
        if (v.sstructRepr) v.sstructRepr.setVisibility(false)
        if (v.bfactorRepr) v.bfactorRepr.setVisibility(true)
      }
    },

    checkRenderStatus (cb) {
      var v = this
      setTimeout(function () {
        var c = v.stage.tasks.count
//        console.log(c)
        if (c !== 0) {
          v.checkRenderStatus(cb)
        } else {
          cb()
        }
      }, 500)
    },

    submit () {
      let v = this
      let formData = new FormData()

      formData.append('id', v.id)
      formData.append('path_cutoff', v.cutoff)

      axios({
        method: 'post',
        url: v.$config.HOST + '/allos/actions/submit2.php',
        data: formData,
        config: {headers: {'Content-Type': 'multipart/form-data'}}
      }).then(response => {
        v.secondCheck()
        alert('Submitted successfully!')
      }).catch(() => {
        alert('Submitted failed!')
      })
    },

    hexToRgb (hex) {
      // Expand shorthand form (e.g. "03F") to full form (e.g. "0033FF")
      var shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i
      hex = hex.replace(shorthandRegex, function (m, r, g, b) {
        return r + r + g + g + b + b
      })

      var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex)
      return result ? {
        r: parseInt(result[1], 16),
        g: parseInt(result[2], 16),
        b: parseInt(result[3], 16)
      } : null
    },

    pathSelection (atomName) {
      var v = this
      var selection
      if (atomName === '') {
        selection = v.paths[v.pathIndex].residues.map(i => `${v.residueNames[i].num}:${v.residueNames[i].chain}`).join(' or ')
      } else {
        selection = v.paths[v.pathIndex].residues.map(i => `${v.residueNames[i].num}:${v.residueNames[i].chain}.${atomName}`).join(' or ')
      }
//      console.log(selection)
      return selection
    },

    reloadPaths (color) {
      var v = this

      var rgb = v.hexToRgb(color)
      color = [rgb.r / 255.0, rgb.g / 255.0, rgb.b / 255.0]
//      console.log(color)

      // Remove all the path components
      v.pathComps.forEach(function (comp) {
        v.stage.removeComponent(comp)
      })
      v.pathComps = []

      // Clear pathNames
      v.pathNames = []

      // Load all the paths
      v.paths.forEach(function (path, index) {
        var residues = path.residues
        if (residues.every(residueId => residueId < v.coords.length)) {
          var shape = new NGL.Shape('shape')
          for (var i = 0; i < residues.length - 1; i++) {
            shape.addCylinder(v.coords[residues[i]], v.coords[residues[i + 1]], color, path.radius, path.name)
          }
          var shapeComp = v.stage.addComponentFromObject(shape)
          shapeComp.addRepresentation('buffer')
          shapeComp.setVisibility(false)
          v.pathComps.push(shapeComp)
          v.pathNames.push(residues.map(i => v.residueNames[i]))
        }
      })

      v.setPath(v.pathIndex)
    },

    secondCheck () {
      var v = this
      v.showCover = true
      v.setFigureType(0)

//      v.pathComps.forEach(function (comp) {
//        v.stage.removeComponent(comp)
//      })
      for (var t in v.representations) {
        v.pdbComp.removeRepresentation(v.representations[t])
      }
      v.representations = {}
      v.representationVisibilities = {}

      console.log(`Loading Task ${v.id}...`)
      axios.get(v.$config.HOST + '/allos/actions/task.php?id=' + v.id).then(response => {
        v.task = response.data
        console.log(JSON.stringify(v.task))

        console.log(`Loading Task Log...`)
        axios.get(v.$config.HOST + `/allos/actions/log.php?id=${v.id}`).then(response => {
          v.log = response.data
        })

        console.log('Loading Task Flow...')
        axios.get(v.$config.HOST + `/allos/actions/file.php?name=${v.task.flow}`).then(response => {
//          console.log(response.data)
          console.log('Analyzing Flow...')
          v.residueFlows = response.data.split(/\r?\n/).map(line => { var l = line.split(/\s+/); return [l[1], parseFloat(l[2]).toPrecision(3)] })
          console.log(v.residueFlows)

          if (v.task.paths != null) {
            console.log('Analyzing Paths...')
            var structure = v.pdbComp.structure

            console.log('Set path radii...')
            // Set path radii
            var radii = []
            v.task.path_radii.split(';').forEach(function (radius) {
              if (radius.trim().length > 0) {
                radii.push(1.0 / parseFloat(radius.trim()))
              }
            })
            var maxRadius = Math.max(...radii)
            var minRadius = Math.min(...radii)
            radii = radii.map(x => 0.5 + (x - minRadius) / (maxRadius - minRadius))

            // Set names and coords
            console.log('Set names and coords...')
            var coords = []
            var names = []
            var residueIndex = 0
            var residueIndexMap = {}
            structure.eachResidue(function (rp) {
              console.log(`${rp.chainname} ${rp.resno} ${rp.resname} ${residueIndex}`)
              names.push({ chain: rp.chainname, num: rp.resno, name: rp.resname, paths: v.residueFlows[residueIndex][0], flow: v.residueFlows[residueIndex][1] })
              rp.eachAtom(function (ap) {
                if (ap.atomname === 'CA') {
                  coords[rp.index] = ap.positionToArray()
                }
              })
              if (coords[rp.index] == null) {
                coords[rp.index] = rp.positionToArray()
              }
              if (!(rp.chainname in residueIndexMap)) {
                residueIndexMap[rp.chainname] = {}
              }
              residueIndexMap[rp.chainname][rp.resno] = residueIndex
              residueIndex += 1
            })
            v.residueNames = names
            v.coords = coords
            v.residueIndexMap = residueIndexMap

            // Set paths
            console.log('Set Path Representations...')
            v.paths = []
            v.coverText = 'Loading Paths...'
            v.task.paths.split(';').forEach(function (path, index) {
              if (path.trim().length > 0) {
                v.paths.push({residues: path.trim().split(' ').map(x => parseInt(x)), radius: radii[index], name: path})
              }
            })

            v.reloadPaths('#4CAF50')

            // Set label representations
            console.log('Set Label Representation...')
            v.labelReprs = []
            v.paths.forEach(path => {
              var labelRepr = v.pdbComp.addRepresentation('label', {
                sele: path.residues.map(i => `${v.residueNames[i].num}:${v.residueNames[i].chain}.CA`).join(' or '),
                color: '#607D8B',
                radiusScale: 0.7,
                fixedSize: true,
                labelType: 'format',
                labelFormat: '%(resname)s %(chainname)s%(resno)s'
              })
              v.labelReprs.push(labelRepr)
            })
            v.labelReprs.forEach(r => r.setVisibility(false))

            v.setPath(0)
            v.toggleRepresentation('cartoon')

            v.checkRenderStatus(function () {
              console.log('Check Render Status...')
              v.showCover = false
              v.stage.autoView()
              v.setFigureType(0)

              v.resetToolTip()
            })
          } else {
            v.timeout = setTimeout(() => { v.secondCheck() }, 3000)
          }
        })
      }).catch((err) => {
        console.log(err.message)
        console.log('Generating Paths Failed!')
      })
    },

    firstCheck () {
      var v = this
      v.showCover = true
      v.setFigureType(2)
//      console.log(v.id)
      v.coverText = 'Checking Task Status...'
      axios.get(v.$config.HOST + '/allos/actions/task.php?id=' + v.id).then(response => {
//        console.log(response.data)
        v.task = response.data
//        console.log(v.task)

        v.dendrogramSrc = v.$config.HOST + `/allos/actions/file.php?name=${v.task.dendrogram}&format=`

        axios.get(v.$config.HOST + `/allos/actions/log.php?id=${v.id}`).then(response => {
          v.log = response.data
        })

//        axios.get(v.$config.HOST + `/allos/actions/file.php?name=${v.task.log}&format=`).then(response => {
//          v.log = response.data
//        })

        if (v.task.status === 'finished') {
          v.coverText = 'Loading Structure...'
          axios.get(v.$config.HOST + `/allos/actions/file.php?name=${v.task.bfactor}&format=`).then(response => {
            v.output = response.data
            v.stage.loadFile(new Blob([v.output], { type: 'text/plain' }), { ext: v.task.bfactor.split('.').pop() }).then(function (comp) {
              v.pdbComp = comp
              v.bfactorRepr = comp.addRepresentation('cartoon', { multipleBond: true, color: 'bfactor' })
              v.checkRenderStatus(function () {
                if (v.task.path_cutoff) {
                  v.cutoff = v.task.path_cutoff
                  v.secondCheck()
                } else {
                  v.showCover = false
                  v.stage.autoView()
                  v.setFigureType(1)
                }
              })
            })
          })
        } else {
          v.timeout = setTimeout(() => { v.firstCheck() }, 5000)
        }
      }).catch(() => {
        console.log('Retrieve files failed!')
      })
    },

    resetToolTip () {
      var v = this

      // create tooltip element and add to document body
      var tooltip = document.createElement('div')
      Object.assign(tooltip.style, {
        display: 'none',
        position: 'fixed',
        zIndex: 10,
        pointerEvents: 'none',
        backgroundColor: 'rgba( 0, 0, 0, 0.6 )',
        color: 'lightgrey',
        padding: '8px',
        fontFamily: 'sans-serif'
      })
      document.body.appendChild(tooltip)

      // remove default hoverPick mouse action
      v.stage.mouseControls.remove('hoverPick')

      // listen to `hovered` signal to move tooltip around and change its text
      v.stage.signals.hovered.add(function (pickingProxy) {
        if (pickingProxy && (pickingProxy.atom || pickingProxy.bond)) {
          var atom = pickingProxy.atom || pickingProxy.closestBondAtom
          var mp = pickingProxy.mouse.position
          tooltip.innerText = `${atom.chainname}-${atom.resno}[${atom.resname}] Flow: ${v.residueFlows[v.residueIndexMap[atom.chainname][atom.resno]][1]}`
          tooltip.style.bottom = window.innerHeight - mp.y + 3 + 'px'
          tooltip.style.left = mp.x + 3 + 'px'
          tooltip.style.display = 'block'
        } else {
          tooltip.style.display = 'none'
        }
      })
    }
  },

  mounted () {
    var v = this
    v.$nextTick(function () {
      bus.$emit('switch-router', 'Task')
      bus.$emit('switch-task', this.id)

      v.stage = new NGL.Stage('b-factor', { backgroundColor: 'white' })
      // v.stage.loadFile('rcsb://1crn', {defaultRepresentation: true})

      v.firstCheck()
    })
  },

  destroyed () {
    var v = this
    clearTimeout(v.timeout)
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

.task .header {
  margin: 20px;
  position: absolute;
  top: 0px;
  left: 0px;
  right: 0px;
  z-index: 99;
}

.task .righter {
  margin: 50px 0px 50px 0px;
  position: absolute;
  top: 0px;
  bottom: 0px;
  right: 0px;
  z-index: 98;
  overflow-x: hidden;
  overflow-y: auto;
  color: #5a5e66;
}

.el-form-item {
  margin-bottom: 3px;
}

.compound-list {
  border: 1px solid grey;
  height: 800px;
}
.compound-detail {
  border: 1px solid grey;
  height: 800px;
}

.el-pagination {
  text-align: center;
}

.switch-menu {
  float: left;
}

.path-pagination {
  float: left;
  margin-left: 220px;
  color: #5a5e66;
}

.download-button {
  float: right;
  color: #5a5e66;
  font-size: 14px;
}

.dendrogram-fullscreen {
  margin: 20px;
  position: absolute;
  top: 0px;
  right: 0px;
  z-index: 99;
  color: #5a5e66;
  font-size: 20px;
  cursor: pointer;
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
  z-index: 98;
  /*
  background-color: white;
  */
}

#dendrogram img {
  height: 95%;
}

#cutoff-input {
  /*
  width: 720px;
  height: 450px;
  line-height: 450px;
  vertical-align: middle;
  text-align: center;
  */
  width: 720px;
  height: 450px;
  display: table-cell;
  vertical-align: middle;
  text-align: center;
}

.viewport {
  width: 720px;
  height: 450px;
  margin: 0px auto 0px 0px;
  box-shadow: 0 0 5px #888888;
}

.el-button--text {
  /*
  color: #eb9e05;
  */
  color: #67c23a;
}

tr {
  height: 30px;
}

.path-residue-name {
  height:20px;
  width:60px;
  font-size: 12px;
}

td.path-residue-name {
  cursor: pointer;
}

td.path-residue-name:hover {
  color: #EB9E05;
  font-weight: bold;
}

.path-residue-paths {
  width:60px;
  font-size: 12px;
}

.path-residue-flow {
  width:60px;
  font-size: 12px;
}

</style>
