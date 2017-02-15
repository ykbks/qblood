<script>
import vSelect from 'vue-select'

export default {
  name: 'donorlist',
  components: {
    vSelect
  },
  data () {
    return {
      bloodGroups: ['A+', 'A-', 'AB+', 'AB-', 'O+', 'O-'],
      donors: [],
      currentPage: 1,
      totalPages: 10,
      showFilter: false,
      listOptions: {
        groups: [],
        canDonate: 1
      }
    }
  },
  created () {
    var apiUrl = 'http://localhost:8000/api/1/'

    this.$http.get(apiUrl + 'donors')
        .then(response => {
          this.donors = response.body
        })
  }
}
</script>

<template>

  <div id="donorlist">

    <div class="content-box-large">
      <h3 class="page-header clearfix">
        <span class="pull-left">Donors</span>
        <span class="pull-right">
          <button class="btn btn-sm btn-default"><i class="fa fa-filter"></i></button>
        </span>
      </h3>

      <div class="panel panel-info">
        <div class="panel-body">
          <pre>{{ listOptions }}</pre>
          <div class="row">
            <div class="col-sm-2">
              <div class="form-group">
                <label for="blood_group">Blood Group</label>
                <v-select v-model="listOptions.groups" :options="bloodGroups" multiple></v-select>
              </div>
            </div>
            
            <div class="col-sm-2">
              <div class="form-group">
                <label for="blood_group">Can Donate</label>
                <div class="radio">
                  <label>
                    <input value="1" type="radio" v-model="listOptions.canDonate">
                    Yes
                  </label>
                  <br>
                  <label>
                    <input value="0" type="radio" v-model="listOptions.canDonate">
                    No
                  </label>
                </div>
              </div>
            </div>

          </div>
          <!-- /.row -->
        </div>
      </div>

      

      <pre>{{ donors }}</pre>


      

    </div>

  </div>

</template>
