<script>
import topNav from 'components/Navbar'
import Sidebar from 'components/Sidebar'

export default {
  name: 'app',
  components: {
    topNav,
    Sidebar
  },
  data () {
    return {
      bloodGroups: ['A+', 'A-', 'AB+', 'AB-', 'B+', 'B-', 'O+', 'O-'],
      religions: ['Islam', 'Hindu', 'Buddhist', 'Christian', 'Others'],
      availableCount: [],
      apiUrl: 'http://localhost:8000/api/1/',
      getAvlInt: undefined
    }
  },
  created () {
    this.getAvailableCount()
    this.getAvlInt = setInterval(this.getAvailableCount, 5000)
  },
  beforeDestroy () {
    if (this.getAvlInt) {
      clearInterval(this.getAvlInt)
      this.getAvlInt = undefined
      console.log(this.getAvlInt)
    }
  },
  methods: {
    getAvailableCount () {
      this.$http.get(this.apiUrl + 'getDonorCount').then(response => {
        this.availableCount = response.body
      })
    }
  }
}

</script>

<template>
  <div id="app">
    <topNav :groups="bloodGroups" :availableCount="availableCount"></topNav>
    <div class="page-content">
      <div class="row">
        <sidebar></sidebar>
        <div class="col-md-10" id="main-content">
          <router-view :groups="bloodGroups" :availableCount="availableCount" :religions="religions"></router-view>
        </div>
      </div>
    </div>
  </div>
</template>
