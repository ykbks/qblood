<script>
import vSelect from 'vue-select'

export default {
  name: 'donorlist',
  components: {
    vSelect
  },
  data () {
    return {
      apiUrl: 'http://localhost:8000/api/1/',
      showFilter: false,
      working: false,
      bloodGroups: ['A+', 'A-', 'AB+', 'AB-', 'O+', 'O-'],
      regTypes: ['QA', 'QG', 'QPM'],
      religions: ['Islam', 'Hindu', 'Buddhist', 'Christian'],
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
        this.currentPage = response.body.current_page
        this.totalPages = response.body.last_page
        this.totalDonors = response.body.total
      }, response => {
        // error
      })

      this.working = false
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
    prevPage () {
      if (this.currentPage !== 1) {
        this.currentPage--
      }
    },
    nextPage () {
      if (this.currentPage !== this.totalPages) {
        this.currentPage++
      }
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
                <v-select v-model="listOptions.orderBy" :options="['Name']" ></v-select>
              </div>
            </div>

            <div class="col-sm-2">
              <div class="form-group">
                <label>Sort By</label>
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

      <table class="table table-bordered table-hover table-striped">
        <thead>
          <tr>
            <th class="text-center" colspan="7">
                <p v-if="!working">entries found: {{ totalDonors }}. Showing page {{ currentPage }}/{{ totalPages }}</p>
                <p v-if="working"><i class="fa fa-spin fa-spinner"></i> loading list...</p>
            </th>
          </tr>
          <tr>
            <th>Sl.</th>
            <th>Donor</th>
            <th>Blood Group</th>
            <th>Available</th>
            <th>Last Donation</th>
            <th>Contact</th>
            <th>Actions</th>
          </tr>
        </thead>

        <tbody>
          
          <tr v-if="donors.length < 1">
            <td colspan="7">Sorry, nothing found</td>
          </tr>

          <tr v-for="(donor, index) in donors">
            <td>{{ ( 10 * (currentPage - 1) ) + index + 1 }}</td>
            <td>
                <a href="">{{ donor.name }}</a>
                <p>{{ donor.reg_complete }}</p>
            </td>
            <td>
                {{ donor.blood_group }} 
                <br> 
                <span class="label label-danger" v-if="!donor.can_donate">Can't Donate</span>
            </td>
            <td>
                <span class="text-success" v-if="donor.available">Yes</span>
                <span class="text-danger" v-if="!donor.available">No</span>
                <span class="text-danger" v-if="!donor.available && donor.can_donate != null">till {{donor.unavailable_till}}</span>
            </td>
            <td></td>
            <td></td>
            <td></td>
          </tr>

        </tbody>

        <tfoot>
          <tr>
            <th>Sl.</th>
            <th>Donor</th>
            <th>Blood Group</th>
            <th>Available</th>
            <th>Last Donation</th>
            <th>Contact</th>
            <th>Actions</th>
          </tr>
        </tfoot>
      </table>

      <ul class="pager">
        <li><button v-on:click="prevPage()" class="btn btn-default" :disabled="currentPage === 1 || working"><i class="fa fa-arrow-left"></i> Previous</button></li>
        <li>
          <select v-model="currentPage" class="" style="padding: 8px 10px; border-radius: 0px;" :disabled="working">
            <option v-for="n in totalPages" :value="n">{{ n }}</option>
          </select>
        </li>
        <li><button v-on:click="nextPage()" class="btn btn-default" :disabled="currentPage == totalPages || working">Next <i class="fa fa-arrow-right"></i></button></li>
      </ul>
      <p class="text-center" v-if="working"><i class="fa fa-spin fa-spinner"></i> working...</p>

    </div>

  </div>

</template>
