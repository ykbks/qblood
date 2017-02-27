<script>
import vSelect from 'vue-select'
import moment from 'moment'

export default {
  name: 'available',
  components: {
    vSelect
  },
  props: ['groups'],
  data () {
    return {
      showFilter: false,
      personToContact: {
        show: false,
        loading: false,
        donor: null
      },
      group: null,
      apiUrl: 'http://localhost:8000/api/1/',
      working: true,
      regTypes: ['QA', 'QG', 'QPM'],
      religions: ['Islam', 'Hindu', 'Buddhist', 'Christian'],
      donors: [],
      currentPage: 1,
      totalPages: 1,
      totalDonors: null,
      areas: [],
      listOptions: {
        groups: [this.group],
        areas: [],
        canDonate: 1,
        available: 1,
        orderBy: 'last_contacted_at',
        order: 'asc'
      }
    }
  },
  created () {
    this.fetchData()
    this.$http.get(this.apiUrl + 'areas')
      .then(response => {
        this.areas = response.body
      })
  },
  watch: {
    '$route': 'fetchData',
    'personToContact.show': 'clearPersonToContact'
  },
  methods: {
    clearPersonToContact () {
      if (this.personToContact.show === false) {
        this.personToContact.donor = null
      } else {
        // load contact data
      }
    },
    dateDiff (date) {
      return moment().diff(moment(date), 'months')
    },
    formatDate (date) {
      return moment(date).format('DD MMM, YYYY')
    },
    fetchData () {
      this.group = this.$route.params.group || null
      if (this.group != null) {
        // send request to the api
        this.filterList(true)
      }
    },
    toggleFilter () {
      this.showFilter = !this.showFilter
    },
    filterList (firstpage) {
      this.working = true

      let terms = {}

      terms.canDonate = this.listOptions.canDonate
      terms.available = this.listOptions.available
      terms.listType = 'available'
      terms.groups = [this.group]
      if (this.listOptions.areas.length && this.listOptions.areas.length > 0) {
        terms.areas = []
        for (var i = 0; i < this.listOptions.areas.length; i++) {
          terms.areas.push(this.listOptions.areas[i].value)
        }
      }
      if (firstpage) {
        terms.page = 1
      } else {
        terms.page = (this.currentPage != null) ? this.currentPage : 1
      }

      this.$http.get(this.apiUrl + 'donors', {params: terms}).then(response => {
        // success
        this.donors = response.body.data
        this.currentPage = response.body.current_page
        this.totalPages = response.body.last_page
        this.totalDonors = response.body.total
      }, response => {
        // error
      })

      setTimeout(function () { this.working = false }.bind(this), 500)
    },
    clearInputs () {
      this.listOptions = {
        groups: [this.group],
        areas: [],
        canDonate: 1,
        available: 1,
        orderBy: 'lst_contacted_at',
        order: 'asc'
      }
    },
    resetPage () {
      this.clearInputs()
      this.filterList()
    },
    handlePageChange (val) {
      this.currentPage = val
    },
    showContact (donor) {
      if (donor) {
        this.personToContact.donor = donor
        this.personToContact.show = true
        this.personToContact.loading = true
      }
    }
  }
}
</script>

<template>

  <div id="add-donor">

    <el-dialog 
      title="Contact Donor" 
      v-model="personToContact.show" 
      :close-on-click-modal="false">
      
      <div xv-if="personToContact.donor">

        <p class="text-center text-info" v-show="personToContact.loading"><i class="fa fa-spin fa-spinner fa-3x"></i> <br> Please wait...</p>

        <pre>{{ personToContact }}</pre>

      </div>
      <div xv-else>
        
      </div>
    
    </el-dialog>

    <div class="content-box-large" v-loading.body="group && working">
      
      <div v-if="group == null">
        <h3 class="page-header">Available Donors</h3>

        <p>Please select a group first:</p>

        <div class="row">
          <div class="col-xs-6 col-sm-4 groupListItem" v-for="group in groups">
            <router-link :to="{ name: 'donors.availableByGroup', params: { group: group } }" class="btn btn-block btn-danger">{{ group }}</router-link>
          </div>
        </div>
      </div>
      <div v-else>
        
        <!-- we have data -->

        <el-alert 
          title="Important" 
          type="info" 
          description="This page is for communication only. If you need a more comprehensive list with more options for filter, please use the 'Donors List' page.">
        </el-alert>

        <h3 class="page-header has-donors"><span class="group">
          {{ group.toUpperCase() }}</span> 
          Available Donors: <span class="text-danger">{{totalDonors}}</span>
          <span class="pull-right">
            <button v-on:click="toggleFilter()" class="btn btn-sm btn-default" v-bind:class="{ active : showFilter }"><i class="fa" v-bind:class="{ 'fa-filter' : !showFilter, 'fa-times' : showFilter }"></i></button>
          </span>
        </h3>
        <div class="clearfix"></div>
        <div class="panel panel-info" v-if="showFilter">
          <div class="panel-body">
            <div class="row">
              <div class="col-sm-8">
                <div class="form-group">
                  <label for="blood_group">Area(s)</label>
                  <v-select v-model="listOptions.areas" :options="areas" multiple></v-select>
                </div>
              </div>
              <div class="col-sm-4">
                <div class="form-group" style="padding-top: 22px;">
                  <button v-bind:disabled="working" v-on:click="filterList(true)" class="btn btn-primary"><i class="fa fa-check"></i> Apply</button>
                  <button v-on:click="resetPage()" class="btn btn-link"><i class="fa fa-eraser"></i> Clear</button>
                  <br>
                  <p v-if="working"><i class="fa fa-spin fa-spinner"></i> working</p>
                </div>
              </div>

            </div>
            <!-- /.row -->
          </div>
        </div>

        <div>
          
          <el-table
            :data="donors"
            border
            style="width: 100%">

            <el-table-column label="Sl." width="75">
              <template scope="scope">
                {{ scope.$index + 1 + (10 * (currentPage - 1))  }}
              </template>
            </el-table-column>
            <el-table-column label="Donor">
              <template scope="scope">
                <router-link :to="{ name: 'donors.details', params: {id: scope.row.id} }"><strong>{{ scope.row.name }}</strong></router-link>
                <br>
                <small>{{ scope.row.reg_complete }}</small>
              </template>
            </el-table-column>
            <el-table-column label="Last Donation">
              <template scope="scope">
                <span v-if="scope.row.last_donation === undefined || scope.row.last_donation == null">
                    Never
                  </span>
                  <span v-else>
                    
                    <i class="fa fa-clock-o"></i> {{ dateDiff(scope.row.last_donation.date)  }} months ago
                    <br>
                    <i class="fa fa-calendar"></i> {{ formatDate(scope.row.last_donation.date) }}
                    
                  </span>
              </template>
            </el-table-column>
            <el-table-column label="Address">
              <template scope="scope">
                <i class="fa fa-map-marker"></i> {{scope.row.area.name}} <el-tooltip content="List available donors only in this area" placement="right" effect="light"><el-button type="text" icon="search"></el-button></el-tooltip>
                  <br>
                  <i class="fa fa-envelope"></i> {{scope.row.address}}
              </template>
            </el-table-column>
            <el-table-column label="Action">
              <template scope="scope">
                <el-button @click="showContact(scope.row)" type="primary"><i class="fa fa-phone"></i> Contact</el-button>
              </template>
            </el-table-column>
            
          </el-table>
          <br>
          
          <div class="text-center">
            <el-pagination
              layout="total, prev, pager, next, jumper"
              @current-change="handlePageChange"
              :page-count="totalPages"
              :total="totalDonors">
            </el-pagination>
          </div>
          
        </div>

      </div>

    </div>

  </div>

</template>

<style lang="css" scoped>
  .groupListItem{
    padding: 15px;
  }
  .page-header.has-donors{
    display: block;
    width: 100%;
    padding: 10px;
    border-bottom: 1px solid #f14444;
    /*border-radius: 10px 0px;*/
  }
  .page-header.has-donors span.group{
    background: #f14444;
    padding: 10px 20px;
    margin: -10px;
    margin-right: 10px;
    font-weight: bold;
    color: white;
    /*border-radius: 10px 0px 0px;*/
  }
</style>
