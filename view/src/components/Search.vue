<template>
  <gcse:search></gcse:search>
</template>

<script>

import { bus } from '../bus.js'

function foo () {
  var cx = '000830983957212989610:l1defdlgcwa'
  var gcse = document.createElement('script')
  gcse.type = 'text/javascript'
  gcse.async = true
  gcse.src = 'https://cse.google.com/cse.js?cx=' + cx
  var s = document.getElementsByTagName('script')[0]
  s.parentNode.insertBefore(gcse, s)
}

foo()

export default {
  name: 'Search',

  data () {
    return {
    }
  },

  computed: {
    frontConfig () {
      return this.$store.state.frontConfig
    }
  },

  mounted () {
    this.$nextTick(() => {
      bus.$emit('switch-router', 'Search')
    })
  }
}
</script>

