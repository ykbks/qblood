<script>
import vSelect from 'vue-select'
import moment from 'moment'

export default {
  name: 'donorlist',
  components: {
    vSelect
  },
  props: ['groups', 'religions'],
  data () {
    return {
      working: true,
      apiUrl: 'http://localhost:8000/api/1/',
      showFilter: false,
      regTypes: ['QA', 'QG', 'QPM'],
      batches: [],
      donors: [],
      currentPage: 1,
      totalPages: 1,
      totalDonors: null,
      areas: [],
      listOptions: {
        groups: [],
        areas: [],
        canDonate: null,
        regTypes: [],
        batches: [],
        religions: [],
        available: null,
        term: '',
        orderBy: '',
        order: ''
      }
    }
  },
  created () {
    this.filterList()

    this.$http.get(this.apiUrl + 'areas')
        .then(response => {
          this.areas = response.body
        })

    this.$http.get(this.apiUrl + 'getRegBatches')
        .then(response => {
          this.batches = response.body
        })
  },
  watch: {
    currentPage: function () {
      this.filterList()
    }
  },
  methods: {
    toggleFilter () {
      this.showFilter = !this.showFilter
    },
    filterList (firstpage) {
      this.working = true

      let terms = {}

      terms.canDonate = this.listOptions.canDonate
      terms.available = this.listOptions.available
      terms.term = this.listOptions.term

      if (this.listOptions.groups.length > 0) {
        terms.groups = this.listOptions.groups
      }

      if (this.listOptions.areas.length > 0) {
        terms.areas = []
        for (var i = 0; i < this.listOptions.areas.length; i++) {
          terms.areas.push(this.listOptions.areas[i].value)
        }
      }

      if (this.listOptions.batches.length > 0) {
        terms.batches = []
        for (i = 0; i < this.listOptions.batches.length; i++) {
          terms.batches.push(this.listOptions.batches[i].value)
        }
      }

      if (this.listOptions.regTypes.length > 0) {
        terms.regTypes = this.listOptions.regTypes
      }

      if (this.listOptions.religions.length > 0) {
        terms.religions = this.listOptions.religions
      }
      if (firstpage) {
        terms.page = 1
      } else {
        terms.page = (this.currentPage != null) ? this.currentPage : 1
      }

      this.$http.get(this.apiUrl + 'donors', {params: terms}).then(response => {
        // success
        this.donors = response.body.data
        this.totalPages = response.body.last_page
        this.totalDonors = response.body.total
      }, response => {
        // error
        console.error(response)
      })
      setTimeout(function () { this.working = false }.bind(this), 500)
    },
    clearInputs () {
      this.listOptions = {
        groups: [],
        areas: [],
        canDonate: null,
        regTypes: [],
        batches: [],
        religions: [],
        available: null,
        term: '',
        orderBy: '',
        order: ''
      }
    },
    resetPage () {
      this.clearInputs()
      this.filterList()
    },
    handlePageChange (val) {
      this.currentPage = val
    },
    dateDiff (date) {
      return moment().diff(moment(date), 'months')
    },
    formatDate (date) {
      return moment(date).format('DD MMM, YYYY')
    }
  }
}
</script>

<template>

  <div id="donorlist">
    <div class="content-box-large">

      <h3 class="page-header clearfix">
        <span class="pull-left">Donors</span>
        <span class="pull-right">
          <button v-on:click="toggleFilter()" class="btn btn-sm btn-default" v-bind:class="{ active : showFilter }"><i class="fa" v-bind:class="{ 'fa-filter' : !showFilter, 'fa-times' : showFilter }"></i></button>
        </span>
      </h3>

      <div class="panel panel-info" v-if="showFilter">
        <div class="panel-body">
          <div class="row">
            <div class="col-sm-2">
              <div class="form-group">
                <label for="blood_group">Blood Group(s)</label>
                <v-select v-model="listOptions.groups" :options="groups" multiple></v-select>
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
                  <br>
                  <label>
                    <input value="" type="radio" v-model="listOptions.canDonate">
                    Both
                  </label>
                </div>
              </div>
            </div>

            <div class="col-sm-4">
              <div class="form-group">
                <label for="blood_group">Area(s)</label>
                <v-select v-model="listOptions.areas" :options="areas" multiple></v-select>
              </div>
            </div>

            <div class="col-sm-2">
              <div class="form-group">
                <label>Reg. Type</label>
                <v-select v-model="listOptions.regTypes" :options="regTypes" multiple></v-select>
              </div>
            </div>

            <div class="col-sm-2">
              <div class="form-group">
                <label>Reg. Batch(es)</label>
                <v-select v-model="listOptions.batches" :options="batches" multiple></v-select>
              </div>
            </div>

            <div class="clearfix"></div>

            <div class="col-sm-4">
              <div class="form-group">
                <label>Search term</label>
                <input type="text" class="form-control" placeholder="name/phone/address/reg" v-model.trim="listOptions.term">
              </div>
            </div>

            <div class="col-sm-2">
              <div class="form-group">
                <label>Sort By</label>
                <v-select 
                  v-model="listOptions.orderBy" 
                  :options="[
                    {label: 'Name', value: 'name'}, 
                    {label: 'Date Added', value: 'created_at'},
                    {label: 'Blood Group', value: 'blood_group'},
                    {label: 'Availability', value: 'available'},
                    {label: 'Can Donate', value: 'can_donate'}
                  ]"></v-select>
              </div>
            </div>

            <div class="col-sm-2">
              <div class="form-group">
                <label>Order</label>
                <v-select v-model="listOptions.order" :options="['Asc','Desc']" ></v-select>
              </div>
            </div>

            <div class="col-sm-2">
              <div class="form-group">
                <label>Religion(s)</label>
                <v-select v-model="listOptions.religions" :options="religions" multiple></v-select>
              </div>
            </div>

            <div class="col-sm-2">
              <div class="form-group">
                <label for="blood_group">Available?</label>
                <div class="radio">
                  <label>
                    <input value="1" type="radio" v-model="listOptions.available">
                    Yes
                  </label>
                  <br>
                  <label>
                    <input value="0" type="radio" v-model="listOptions.available">
                    No
                  </label>
                  <br>
                  <label>
                    <input value="" type="radio" v-model="listOptions.available">
                    Both
                  </label>
                </div>
              </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-sm-12">
              <div class="form-group">
                <br>
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
          v-loading.body="working"
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
          <el-table-column label="Blood Group">
            <template scope="scope">
              <strong>{{ scope.row.blood_group }}</strong>
            </template>
          </el-table-column>
          <el-table-column label="Available">
            <template scope="scope">
              <strong class="text-info" v-if="scope.row.can_donate && scope.row.available">Yes</strong>
              <strong class="text-danger" v-if="!scope.row.can_donate || !scope.row.available">No</strong>
              <strong class="text-danger" v-if="!scope.row.available && scope.row.unavailable_till">(till {{ formatDate(scope.row.unavailable_till) }})</strong>
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
            
              <el-dropdown split-button type="primary">
                <i class="fa fa-plus"></i> Add Donation
                <el-dropdown-menu slot="dropdown">
                  <el-dropdown-item><i class="fa fa-info-circle"></i> Details</el-dropdown-item>
                  <el-dropdown-item><i class="fa fa-pencil"></i> Edit</el-dropdown-item>
                  <el-dropdown-item><i class="fa fa-times"></i> Delete</el-dropdown-item>
                </el-dropdown-menu>
              </el-dropdown>

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

</template>
