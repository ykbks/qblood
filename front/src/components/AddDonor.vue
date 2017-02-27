<script>
export default {
  name: 'adddonor',
  props: ['groups', 'religions'],
  data () {
    return {
      apiUrl: 'http://localhost:8000/api/1/',
      expandedItem: '0',
      secondaryInputVis: false,
      requestInputVis: false,
      tempInput: '',
      areas: [],
      newDonor: {
        name: '',
        reg_type: '',
        reg_no: '',
        reg_batch: '',
        reg_batch_suffix: '',
        gender: '',
        religion: '',
        dob: '',
        blood_group: '',
        phone_primary: '',
        phone_secondary: [],
        phone_request: [],
        can_donate: true,
        available: true,
        unavailable_till: '',
        area_id: '',
        address: ''
      },
      formStatus: {
        busy: false,
        errors: []
      },
      help: [
        {
          item: 'What is "Can Donate"?',
          text: 'Can this person donate blood now? Sometimes due to illness or other matters the donor may no longer donate blood. Set this as a <strong>permanent</strong> status of the person'
        },
        {
          item: 'What is "Available for Donation"?',
          text: 'Sometimes due to being away or illness the donor may not be able to donate blood for some time. This is a <strong>Temporary</strong> status which you can set and set a date until which the donor may be unavailble.'
        },
        {
          item: 'How do I pick dates',
          text: 'Use the date picker to select the date. You can change months, years by clicking on the months name or year on the calendar. Then click on the day of the month to select that date'
        },
        {
          item: 'Reg. No.',
          text: 'Reg. No = the Seat No. of the person (not the whole reg). <br /> Reg. Batch = the batch. <br /> Batch Suffix = Location ID like S for Sylhet etc. Like 40/202/<strong class="text-danger">S</strong> <i class="fa fa-arrow-left"></i> this'
        },
        {
          item: 'What is "Phone (primary)" ?',
          text: 'It is the main and preferably the personal phone number of the donor. Enter only one phone number in this field.'
        },
        {
          item: 'Phone (secondary) and Phone (request)',
          text: 'All other phone numbers for the donor. Personal/home/work/on-request. Can add multiple.'
        },
        {
          item: 'Errors?',
          text: 'If you see errors when you click on the Add button, check below each input to see what is missing or what went wrong. Correct your input and the form should submit properly without any errors. If you still see errors, <a href="#">please report them to me using this form</a>'
        }
      ]
    }
  },
  created () {
    this.$http.get(this.apiUrl + 'areas')
      .then(response => {
        this.areas = response.body
      })
  },
  watch: {
    'newDonor.can_donate': 'watchCanDontate'
  },
  computed: {
    fullReg: function () {
      let reg = ''
      let regType = this.newDonor.reg_type
      let gender = this.newDonor.gender
      let regNo = this.newDonor.reg_no
      let regBatch = this.newDonor.reg_batch
      let regBatchSuffix = this.newDonor.reg_batch_suffix

      switch (regType) {
        case 'QG':
          if (gender === 'male') {
            // do nothing
          } else if (gender === 'female') {
            reg += 'F-' // we add F- for female Graduates
          } else {
            reg = 'Plesae select the Gender' // we need the gender for a correct reg no
          }
          break

        case 'QPM':
          reg += 'M-' // Add M- before the reg
          break

        default:
          reg = 'QA'
          break
      }

      if (gender) {
        if (regType && regType !== 'QA') {
          reg += regNo || '(enter reg. no.)'
          reg += '/'
          reg += regBatch || '(enter reg. batch)'
        }

        if (regType && regType !== 'QA' && regBatchSuffix) {
          reg += '/' + regBatchSuffix.toUpperCase()
        }
      }
      // we will do this again in the back-end
      // for now simply display
      return reg
    }
  },
  methods: {
    watchCanDontate () {
      if (this.newDonor.available) {
        this.newDonor.available = (this.newDonor.can_donate)
      }
    },
    handleSecondaryRemove (phone) {
      this.newDonor.phone_secondary.splice(this.newDonor.phone_secondary.indexOf(phone), 1)
    },
    handleSecondarySave () {
      let tempInput = this.tempInput
      if (tempInput) {
        this.newDonor.phone_secondary.push(tempInput)
      }
      this.secondaryInputVis = false
      this.tempInput = ''
    },
    handleRequestRemove (phone) {
      this.newDonor.phone_request.splice(this.newDonor.phone_secondary.indexOf(phone), 1)
    },
    handleRequestSave () {
      let tempInput = this.tempInput
      if (tempInput) {
        this.newDonor.phone_request.push(tempInput)
      }
      this.requestInputVis = false
      this.tempInput = ''
    },
    submitInfo () {
      this.formStatus.busy = true
      let url = this.apiUrl + 'donors'
      this.$http.post(url, this.newDonor)
        .then(response => {
          // successful
          // redirect to the details page
          this.$router.push({name: 'donors.details', params: {id: response.body.donor_id}})
          // show a success message
          this.$message.success('Success: The Donor was successfully added.')
        }, response => {
          // errors
          this.formStatus.errors = response.body
          console.log(response.body)
          this.$message.error('Failed to store the data. There was an error')
        })
      setTimeout(function () { this.formStatus.busy = false }.bind(this), 500)
    },
    cancel () {
      this.$confirm('Are you sure?', 'Warning', {
        // we're reversing the role here
        // we want to make the No button look blue
        confirmButtonText: 'No',
        cancelButtonText: 'Yes',
        type: 'warning'
      }).then(() => {
        // do nothing
      }).catch(() => {
        // Go to the donors list page
        this.$router.push({name: 'donors.list'})
      })
    }
  }
}
</script>

<template>

  <div id="add-donor">

    <div class="content-box-large" v-loading="formStatus.busy">
      <h3 class="page-header">Add a Donor</h3>

      <div class="row">
        <div class="col-sm-4 col-sm-offset-2 pull-right">

          <div class="well">
            <h4 class="lead">Help</h4>
          
            <el-collapse v-model="expandedItem" accordion>
              <el-collapse-item v-for="(row, index) in help" :title="row.item" :name="index">
                <div v-html="row.text"></div>
              </el-collapse-item>
            </el-collapse>

          </div>
        </div>
        <div class="col-sm-6 pull-left">
          
          <el-form ref="form" :model="newDonor" label-width="200px" label-position="right">

            <el-form-item label="Full Name">
              <el-input v-model.trim="newDonor.name"></el-input>
              <div class="text-danger" v-if="formStatus.errors.name">
                <span v-for="err in formStatus.errors.name">{{err}}</span>
              </div>
            </el-form-item>

            <el-row>
              <el-col :span="12">
                <el-form-item label="Gender">
                  <el-select v-model="newDonor.gender" placeholder="Please select">
                    <el-option label="Female" value="female"></el-option>
                    <el-option label="Male" value="male"></el-option>
                  </el-select>
                  <div class="text-danger" v-if="formStatus.errors.gender">
                    <span v-for="err in formStatus.errors.gender">{{err}}</span>
                  </div>
                </el-form-item>
              </el-col>
              
              <el-col :span="12">
                <el-form-item label="Blood Group">
                  <el-select v-model="newDonor.blood_group" placeholder="Please select">
                    <el-option v-for="g in groups" :label="g" :value="g"></el-option>
                  </el-select>
                  <div class="text-danger" v-if="formStatus.errors.blood_group">
                    <span v-for="err in formStatus.errors.blood_group">{{err}}</span>
                  </div>
                </el-form-item>
              </el-col>
            </el-row>

            <el-row>
              <el-col :span="12">
                <el-form-item label="Can Donate?">
                  <el-switch on-text="Yes" off-text="No" v-model="newDonor.can_donate"></el-switch>
                </el-form-item>
              </el-col>
              <el-col :span="12">
                <el-form-item label="Available for donation">
                  <el-switch on-text="Yes" off-text="No" v-model="newDonor.available"></el-switch>
                </el-form-item>
              </el-col>
            </el-row>

            <el-form-item label="Unavailable Till" v-if="!newDonor.available && newDonor.can_donate">
              <el-date-picker 
                type="date" 
                placeholder="Pick a date" 
                v-model="newDonor.unavailable_till"
                format="yyyy-MM-dd"></el-date-picker>
                <div class="text-danger" v-if="formStatus.errors.unavailable_till">
                  <span v-for="err in formStatus.errors.unavailable_till">{{err}}</span>
                </div>
            </el-form-item>

            <el-form-item label="Birthday">
              <el-date-picker 
                type="date"
                placeholder="Pick a date" 
                v-model="newDonor.dob"
                format="yyyy-MM-dd"></el-date-picker>
                <div class="text-danger" v-if="formStatus.errors.dob">
                  <span v-for="err in formStatus.errors.dob">{{err}}</span>
                </div>
            </el-form-item>

            <el-form-item label="QA/QG/QPM">
              <el-select v-model="newDonor.reg_type" placeholder="Please select">
                <el-option v-for="type in ['QA','QG','QPM']" :label="type" :value="type"></el-option>
              </el-select>
              <div class="text-danger" v-if="formStatus.errors.reg_type">
                <span v-for="err in formStatus.errors.reg_type">{{err}}</span>
              </div>
            </el-form-item>

            <div class="well" v-if="newDonor.reg_type && newDonor.reg_type !== 'QA'">
                <el-form-item 
                  label="Reg. No.">
                  <el-input v-model.number.trim="newDonor.reg_no"></el-input>
                  <div class="text-danger" v-if="formStatus.errors.reg_no">
                    <span v-for="err in formStatus.errors.reg_no">{{err}}</span>
                  </div>
                </el-form-item>
                <el-form-item label="Reg. Batch">
                  <el-input v-model.number.trim="newDonor.reg_batch"></el-input>
                  <div class="text-danger" v-if="formStatus.errors.reg_batch">
                    <span v-for="err in formStatus.errors.reg_batch">{{err}}</span>
                  </div>
                </el-form-item>
                <el-form-item label="Batch Suffix">
                  <el-input v-model.trim="newDonor.reg_batch_suffix"></el-input>
                  <div class="text-danger" v-if="formStatus.errors.reg_batch_suffix">
                    <span v-for="err in formStatus.errors.reg_batch_suffix">{{err}}</span>
                  </div>
                </el-form-item>
                <el-form-item label="Full Reg:">
                  <big>{{fullReg}}</big>
                </el-form-item>
            </div>

            <el-form-item label="Phone (primary)">
              <el-input v-model.trim="newDonor.phone_primary"></el-input>
              <p class="help-block"><small>Only enter one phone number. Must be the primary contact number. Add other numbers below.</small></p>
              <div class="text-danger" v-if="formStatus.errors.phone_primary">
                <span v-for="err in formStatus.errors.phone_primary">{{err}}</span>
              </div>
            </el-form-item>

            <el-form-item label="Phone (extra)">
              <el-tag
                :key="phone"
                v-for="phone in newDonor.phone_secondary"
                :closable="true"
                :close-transition="false"
                @close="handleSecondaryRemove(phone)"
              >
              {{phone}}
              </el-tag>

              <el-input
                class="input-new-tag"
                v-if="secondaryInputVis"
                v-model.trim="tempInput"
                ref="saveSecondaryInput"
                @keyup.enter.native="handleSecondarySave"
                @blur="handleSecondarySave"
              >
              </el-input>
              <el-button v-else class="button-new-tag" size="small" @click="secondaryInputVis = true">+ Add</el-button>
              <div class="text-danger" v-if="formStatus.errors.phone_secondary">
                <span v-for="err in formStatus.errors.phone_secondary">{{err}}</span>
              </div>
            </el-form-item>

            <el-form-item label="Phone (request)">
              <el-tag
                :key="phone"
                v-for="phone in newDonor.phone_request"
                :closable="true"
                :close-transition="false"
                @close="handleRequestRemove(phone)"
              >
              {{phone}}
              </el-tag>

              <el-input
                class="input-new-tag"
                v-if="requestInputVis"
                v-model.trim="tempInput"
                ref="saveSecondaryInput"
                @keyup.enter.native="handleRequestSave"
                @blur="handleRequestSave"
              >
              </el-input>
              <el-button v-else class="button-new-tag" size="small" @click="requestInputVis = true">+ Add</el-button>
              <div class="text-danger" v-if="formStatus.errors.phone_request">
                <span v-for="err in formStatus.errors.phone_request">{{err}}</span>
              </div>
            </el-form-item>

            <el-form-item label="Religion">
              <el-select v-model="newDonor.religion" filterable>
                <el-option v-for="religion in religions" :label="religion.toUpperCase()" :value="religion.toLowerCase()"></el-option>
              </el-select>
              <div class="text-danger" v-if="formStatus.errors.religion">
                <span v-for="err in formStatus.errors.religion">{{err}}</span>
              </div>
            </el-form-item>

            <el-form-item label="Area">
              <el-select v-model="newDonor.area_id" filterable>
                <el-option v-for="area in areas" :label="area.label" :value="area.value"></el-option>
              </el-select>
              <div class="text-danger" v-if="formStatus.errors.area_id">
                <span v-for="err in formStatus.errors.area_id">{{err}}</span>
              </div>
            </el-form-item>

            <el-form-item label="Address">
              <el-input v-model.trim="newDonor.address"></el-input>
              <div class="text-danger" v-if="formStatus.errors.address">
                <span v-for="err in formStatus.errors.address">{{err}}</span>
              </div>
            </el-form-item>


            <el-form-item>
              <el-button type="primary" @click="submitInfo" icon="plus" :loading="formStatus.busy">Add Donor</el-button>
              <el-button @click="cancel">Cancel</el-button>
            </el-form-item>
            
          </el-form>
        </div>
      </div>

    </div>

  </div>

</template>

<style scoped>
  .el-tag + .el-tag {
    margin-left: 5px;
  }
  .input-new-tag {
    font-size: 14px;
    max-width: 120px;
  }
</style>
