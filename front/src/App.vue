<script>
import Navbar from 'components/Navbar'
import Sidebar from 'components/Sidebar'

export default {
  name: 'app',
  components: {
    Navbar,
    Sidebar
  },
  data: function () {
    return {
    }
  }
}

</script>

<template>
  <div id="app">
    <navbar></navbar>
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
