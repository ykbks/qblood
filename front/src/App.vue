<script>
import Navbar from 'components/Navbar'
import Sidebar from 'components/Sidebar'

export default {
  name: 'app',
  components: {
    Navbar,
    Sidebar
  },
  data () {
    return {
      bloodGroups: ['A+', 'A-', 'AB+', 'AB-', 'O+', 'O-']
    }
  }
}

</script>

<template>
  <div id="app">
    <navbar :groups="bloodGroups"></navbar>
    <div class="page-content">
      <div class="row">
        <sidebar></sidebar>
        <div class="col-md-10" id="main-content">
          <router-view></router-view>
        </div>
      </div>
    </div>
  </div>
</template>
