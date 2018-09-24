// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import Vuex from 'vuex'
import App from './App'
import ElementUI from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'
import router from './router'
import vueConfig from 'vue-config'
import backConfig from '../../config.json'
import upperFirst from 'lodash/upperFirst'
import camelCase from 'lodash/camelCase'
import axios from 'axios'

const configs = {
  HOST: backConfig.host
}

Vue.config.productionTip = false

Vue.use(ElementUI)
Vue.use(Vuex)
Vue.use(vueConfig, configs)

const store = new Vuex.Store({
  state: {
    user: '',

    view: 'fullscreen',

    frontConfig: {
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
  },

  mutations: {
    login (state, user) {
      state.user = user
    },

    logout (state) {
      state.user = ''
    },

    viewFullscreen (state) {
      state.view = 'fullscreen'
    },

    viewWindow (state) {
      state.view = 'window'
    },

    initFrontConfig (state, conf) {
      state.frontConfig = conf
    },

    updateFrontConfig (state, conf) {
      let formData = new FormData()

      formData.append('config', JSON.stringify(conf))

      var oldConf = state.frontConfig
      state.frontConfig = conf

      axios({
        method: 'post',
        url: configs.HOST + `/dokhlab/actions/front-config/update.php`,
        data: formData,
        config: {headers: {'Content-Type': 'multipart/form-data'}},
        withCredentials: true
      }).then(response => {
        console.log(response)
      }).catch(error => {
        alert('Update website config failed!!!')
        state.frontConfig = oldConf
        console.log(error.response)
      })
    }
  }
})

const requireComponent = require.context(
  // The relative path of the components folder
  './components/base',
  // Whether or not to look in subfolders
  false,
  // The regular expression used to match base component filenames
  /[A-Z]\w+\.(vue|js)$/
)

requireComponent.keys().forEach(fileName => {
  // Get component config
  const componentConfig = requireComponent(fileName)

  // Get PascalCase name of component
  const componentName = upperFirst(
    camelCase(
      // Strip the leading `./` and extension from the filename
      fileName.replace(/^\.\/(.*)\.\w+$/, '$1')
    )
  )

  // Register component globally
  Vue.component(
    componentName,
    // Look for the component options on `.default`, which will
    // exist if the component was exported with `export default`,
    // otherwise fall back to module's root.
    componentConfig.default || componentConfig
  )
})

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  store,
  template: '<App/>',
  components: { App },
  render: h => h(App)
})
