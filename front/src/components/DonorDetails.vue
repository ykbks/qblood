<script>
export default {
  name: 'donorDetails',
  data () {
    return {
      apiUrl: 'http://localhost:8000/api/1/',
      working: true,
      details: null
    }
  },
  created () {
    this.loadDetails()
  },
  methods: {
    loadDetails () {
      if (this.$route.params.id) {
        let donorId = this.$route.params.id
        this.$http.get(this.apiUrl + 'donors/' + donorId)
          .then(response => {
            this.details = response.body
          }, response => {
            this.dError = response.body
          })
        this.working = false
      }
    }
  }
}
</script>

<template>

  <div id="donorDetails">

    <div class="content-box-large" v-loading.body="working">
      <h3 class="page-header">Donor Details:</h3>
      
      <div class="alert alert-warning" xv-if="!details.current">
        <div class="row">
          <div class="col-xs-2">
            <div class="text-center">
              <i class="fa fa-4x fa-exclamation"></i>
            </div>
          </div>
          <div class="col-xs-10">
            <h4>Donor Not Found</h4>
            <p>Sorry, no donor with the ID is found. Please recheck the ID or try searching.</p>
            <router-link :to="{ name: 'donors.list' }" class="btn btn-default"><i class="fa fa-list"></i> Donors List</router-link>
          </div>
        </div>
      </div>
      <div sv-else>
        
        <pre>{{details}}</pre>

      </div>

    </div>

  </div>

</template>

<style scoped>
  
</style>
