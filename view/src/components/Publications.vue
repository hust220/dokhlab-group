<template>
<div>
  <div style="padding: 5px; width: 800px; height: 41px">
    <div style="margin-right: 5px; height: 30px" align="right">
      <img src="../assets/publications.gif" style="width:300px; height:30px" />
    </div>
  </div>

  <!-- Search -->
  <div>
  </div>

  <div style="display: flex">
    <!-- Navigation -->
    <div>
      <h3>Filters</h3>

      <h5>Autors</h5>
      <p>Adrian W.R. Serohijos (15)</p>
      <p>Andrey Krokhotin (10)</p>
      <p>Barry  Kesner (2)</p>
      <p>Benfeard Williams (6)</p>
      <p>See more</p>

      <h5>Journals</h5>
      <p>Journal of Molecular Biology (20)</p>
      <p>Proceedings of the National Academy of Sciences USA (17)</p>
      <p>Biophysical Journal (6)</p>
      <p>Journal of Biological Chemistry (15)</p>
      <p>See more</p>

      <h5>Topic</h5>
      <p>Proteins: Folding (38)</p>
      <p>Proteins: Aggregation (22)</p>
      <p>RNA  (21)</p>
      <p>Proteins: Evolution (18)</p>
      <p>See more</p>

      <h5>Year</h5>
      <p>2018 (12)</p>
      <p>2017 (17)</p>
      <p>2016 (15)</p>
      <p>2015 (13)</p>
      <p>See more</p>
    </div>

    <!-- Publications -->
    <div>
      <ul>
        <li v-for="publication in publications">
        {{publication.authors}} "{{publication.title}}" {{publication.journal}} {{publication.volume}}:{{publication.fpage}}-{{publication.lpage}} ({{publication.year}})<br>
          [Abstract][PDF][Supporting Information]
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
      publications: []
    }
  },

  methods: {
    getPubs () {
      var v = this

      axios({
        method: 'get',
        url: v.$config.HOST + '/dokhlab/actions/publications.php'
      }).then(response => {
        v.publications = response.data
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
