<template>
<div>
  <!-- Abstract Preview -->
  <el-dialog :title="pubToShow.code" :visible.sync="pubToShow.visible">
    <div align="center" style="height: 500px; overflow-x: auto">
      <div><h3>{{pubToShow.title}}</h3></div>
      <div>{{pubToShow.authors.join(",")}}</div>
      <br>
      <div align="justify">{{pubToShow.abstract}}</div>
    </div>
  </el-dialog>

  <!-- Logo -->
  <div style="padding: 5px; width: 800px; height: 41px">
    <div style="margin-right: 5px; height: 30px" align="right">
      <img src="../assets/publications.gif" style="width:300px; height:30px" />
    </div>
  </div>

  <!-- Search -->
  <div>
    <div style="display:flex;align-items:center;float:right">
      <div v-if="searchKey!==''" style="margin-right:30px;color:#bbbbbb;height:100%">
        {{pubsToShow.length}} Results
      </div>

      <div class="greybox">
        Look for <input @keyup.enter="updateSearch()" style="width:80px" type="text" v-model="searchKey" /> in
        <select style="width:80px" v-model="searchFilter">
          <option disabled value="">Please select one</option>
          <option>All</option>
          <option>Authors</option>
          <option>Journal</option>
          <option>Topic</option>
          <option>Year</option>
        </select>
        <el-button @click="updateSearch()" type="info" size="mini" plain>Search</el-button>
      </div>
    </div>

    <div style="clear:both"></div>
  </div>

  <div style="display: flex;width:100%">
    <!-- Navigation -->
    <div class="greybox" style="width:300px">
      <h3>Filters</h3>

      <div v-for="(filter,ind) in defaultFilters" :key="ind">
        <h5>{{filter}}</h5>
        <ul>
          <li v-for="c in (filterConfigs[filter].numClusters === 'all' ? defaultClusters[filter] : defaultClusters[filter].slice(0, filterConfigs[filter].numClusters))">
            <a style="cursor:pointer;color:black" @click="searchFilter=filter;searchKey=c.key;updateAccurateSearch()">{{c.key}}</a> ({{c.values.length}})
          </li>
          <li><a @click="filterConfigs[filter].numClusters = (filterConfigs[filter].numClusters === 'all' ? 4 : 'all')" style="cursor:pointer">{{filterConfigs[filter].numClusters === 'all' ? 'Hide' : 'Show More'}}</a></li>
        </ul>
      </div>
    </div>

    <!-- Publications -->
    <div class="greybox" style="width:100%">
      <ul>
        <li v-for="publication in pubsToShow">
          <div style="margin-bottom:10px">
            {{publication.originalAuthors}} "{{publication.title}}", <i>{{publication.journal}}</i>, {{(publication.fpage) ? `${publication.volume}:${publication.fpage}-${publication.lpage}` : `in press`}}, ({{publication.year}})<br>
            [<a style="cursor:pointer" @click="showAbstract(publication)">Abstract</a>] [<a :href="$config.HOST+'/dokhlab/actions/download.php?name=papers/pdf/'+publication.code+'.pdf'">PDF</a>] <template v-if="publication.suppinfo==='yes'">[<a :href="$config.HOST+'/dokhlab/actions/download.php?name=papers/suppl/'+publication.code+'-SuppInfo.pdf'">Supporting Information</a>]</template>
          </div>
        </li>
      </ul>
    </div>
  </div>
</div>
</template>

<script>
import { bus } from '../bus.js'
import axios from 'axios'

export default {
  name: 'Publications',

  data () {
    return {
      publications: [],

      pubToShow: {
        code: '',
        authors: ['', ''],
        title: '',
        visible: false,
        abstract: ''
      },

      searchKey: '',

      searchFilter: 'All',

      pubsToShow: [],

      filterConfigs: {
        Authors: {
          numClusters: 4
        },
        Journal: {
          numClusters: 4
        },
        Topic: {
          numClusters: 4
        },
        Year: {
          numClusters: 4
        }
      }
    }
  },

  computed: {
    defaultFilters () {
      return Object.keys(this.filterConfigs)
    },

    defaultClusters () {
      var clusters = {}
      this.defaultFilters.forEach(filter => {
        clusters[filter] = this.cluster(this.accurateSearch(this.publications, '', filter), filter).sort((a, b) => b.values.length - a.values.length)
      })
      return clusters
    }
  },

  methods: {
    showAbstract (publication) {
      var v = this
      v.pubToShow.authors = publication.authors
      v.pubToShow.title = publication.title
      v.pubToShow.abstract = publication.abstract
      v.pubToShow.visible = true
    },

    updateSearch () {
      var ls = this.search(this.publications, this.searchKey, this.searchFilter)
      ls.sort((a, b) => b.pub_id - a.pub_id)
      this.pubsToShow = ls
    },

    updateAccurateSearch () {
      var ls = this.accurateSearch(this.publications, this.searchKey, this.searchFilter)
      ls.sort((a, b) => b.pub_id - a.pub_id)
      this.pubsToShow = ls
    },

    search (pubs, key, filter) {
      if (key === '') {
        return pubs
      } else if (filter === '' || filter.toUpperCase() === 'ALL') {
        return pubs.filter(pub => this.getPubContent(pub).toUpperCase().includes(key.toUpperCase()))
      } else if (filter.toUpperCase() === 'AUTHORS') {
        return pubs.filter(pub => this.getProp(pub, 'authors').filter(author => author.toUpperCase().includes(key.toUpperCase())).length > 0)
      } else {
        return pubs.filter(pub => this.getProp(pub, filter).toUpperCase().includes(key.toUpperCase()))
      }
    },

    accurateSearch (pubs, key, filter) {
      if (key === '') {
        return pubs
      } else if (filter.toUpperCase() === 'AUTHORS') {
        return pubs.filter(pub => this.getProp(pub, 'authors').filter(author => author.toUpperCase() === key.toUpperCase()).length > 0)
      } else {
        return pubs.filter(pub => this.getProp(pub, filter).toUpperCase() === key.toUpperCase())
      }
    },

    filterKeys (pubs, filter) {
      var keys = []
      pubs.forEach((pub, ind) => {
        let prop = this.getProp(pub, filter)
        let props = this.splitProp(prop, filter)
        props.forEach((p, i) => {
          if (!keys.includes(p)) {
            keys.push(p)
          }
        })
      })
      return keys.filter(key => key != null && key !== '')
    },

    cluster (pubs, filter) {
      return this.filterKeys(pubs, filter).map(key => { return {key: key, values: this.accurateSearch(pubs, key, filter)} })
    },

    splitProp (prop, filter) {
      if (filter.toUpperCase() === 'AUTHORS') {
//          var found = prop.match(/^(?:\s*((?:[A-Za-z-]+),\s*[A-Za-z-]+\.\s*(?:\s*[^.\s]+\.\s*)*),?\s*)+(?:\s*and\s+([A-Za-z-]+\s*,\s*[^.\s]+\.\s*(?:\s*[^.\s]+\.\s*)*),?\s*)?$/)
        return prop
      } else {
        return [prop]
      }
    },

    getPubContent (publication) {
      return `${publication.originalAuthors} "${publication.title}" ${publication.journal} ${publication.volume}:${publication.fpage}-${publication.lpage} (${publication.year})`
    },

    getProp (obj, key) {
      for (var k in obj) {
        if (k.toUpperCase() === key.toUpperCase()) {
          return obj[k]
        }
      }
      return ''
    },

    formatAuthors () {
      this.publications.forEach(pub => {
        let authors = []
        let firstName = ''
        let lastName = ''
        pub.authors.split(/and |[, ]/).forEach((name, ind) => {
//          console.log(name)
          name = name.replace(/\s+/g, '')
          if (name !== '') {
            if (name[name.length - 1] === '.') {
              firstName += name
            } else {
              if (ind !== 0) {
                authors.push(`${lastName}, ${firstName}`)
                firstName = ''
                lastName = ''
              }
              lastName = name
            }
          }
        })
        authors.push(`${lastName}, ${firstName}`)
        pub.originalAuthors = pub.authors
        pub.authors = authors
      })
    },

    getPubs () {
      var v = this

      axios({
        method: 'get',
        url: v.$config.HOST + '/dokhlab/actions/publications.php'
      }).then(response => {
        v.publications = response.data
        v.publications.sort((a, b) => b.pub_id - a.pub_id)
        v.formatAuthors()
        v.pubsToShow = v.publications
      }).catch(() => {
        alert('Fetch publications failed!')
      })
    }
  },

  mounted () {
    var v = this

    this.$nextTick(function () {
      bus.$emit('switch-router', 'Publications')
    })

    v.getPubs()
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

</style>
