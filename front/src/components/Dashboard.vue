<script>
export default {
  name: 'dashboard',
  data () {
    return {
      bloodGroups: ['A+', 'A-', 'AB+', 'AB-', 'O+', 'O-'],
      birthdayCount: 20,
      birthdays: [
        {id: 2, name: 'Donor 1', phone_primary: 64651324, phone_secondary: [123231, 1233211], phone_request: 884533},
        {id: 4, name: 'Donor 4', phone_primary: 64651324, phone_secondary: [123231, 1233211], phone_request: 884533},
        {id: 9, name: 'Donor 9', phone_primary: 64651324, phone_secondary: [123231, 1233211], phone_request: 884533},
        {id: 13, name: 'Donor 13', phone_primary: 64651324, phone_secondary: [123231, 1233211], phone_request: 884533},
        {id: 45, name: 'Donor 45', phone_primary: 64651324, phone_secondary: [123231, 1233211], phone_request: 884533}
      ],
      latestDonations: [
        {id: 2, name: 'Donor 1', reg: 'QA', phone_primary: 64651324, phone_secondary: [123231, 1233211], phone_request: 884533, blood_group: 'A+', time: new Date(), area: 'Some Area'},
        {id: 4, name: 'Donor 4', reg: 'M-122/11', phone_primary: 64651324, phone_secondary: [123231, 1233211], phone_request: 884533, blood_group: 'A-', time: new Date(), area: 'Some Area'},
        {id: 9, name: 'Donor 9', reg: 'QA', phone_primary: 64651324, phone_secondary: [123231, 1233211], phone_request: 884533, blood_group: 'AB+', time: new Date(), area: 'Some Area'},
        {id: 13, name: 'Donor 13', reg: '155/300/S', phone_primary: 64651324, phone_secondary: [123231, 1233211], phone_request: 884533, blood_group: 'AB-', time: new Date(), area: 'Some Area'},
        {id: 45, name: 'Donor 45', reg: 'F-322/400', phone_primary: 64651324, phone_secondary: [123231, 1233211], phone_request: 884533, blood_group: 'O+', time: new Date(), area: 'Some Area'}
      ],
      latestActivities: [
        { time: new Date(), activity: 'When you use the class attribute on a custom component, those classes will be added to the component’s root element. ' },
        { time: new Date(), activity: 'When you use the class attribute on a custom component, those classes will be added to the component’s root element. ' },
        { time: new Date(), activity: 'When you use the class attribute on a custom component, those classes will be added to the component’s root element. ' },
        { time: new Date(), activity: 'When you use the class attribute on a custom component, those classes will be added to the component’s root element. ' },
        { time: new Date(), activity: 'When you use the class attribute on a custom component, those classes will be added to the component’s root element. ' },
        { time: new Date(), activity: 'When you use the class attribute on a custom component, those classes will be added to the component’s root element. ' }
      ]
    }
  }
}
</script>

<template>

  <div id="dashboard">
    <div class="alert alert-success">
      <p>
        Welcome back, User Name!
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </p>
    </div>
    <div class="row">

      <div class="col-sm-6" id="front-available-donors">
        <div class="panel panel-default">
          <div class="panel-heading">
            <strong>Available Donors</strong>
          </div>
          <div class="panel-body">
            <div class="row">
              <div v-for="(group, index) in bloodGroups" class="col-xs-6 front-available-donor-count">
                <a href="#">{{ group }} <span class="badge">{{ (index * 5) + 1 }}</span></a>
              </div>
            </div>
          </div>
          <div class="panel-footer">
            <i class="fa fa-exclamation-circle text-info"></i> Click on an item to go to the list
          </div>
        </div>
      </div>
      <!-- /#front-available-donors -->
      <div id="front-birthday-today" class="col-sm-6">
        <div class="panel panel-default">
          <div class="panel-heading">
            <strong>Birthday today</strong>
          </div>
          <div class="panel-body">
            <table class="table table-striped table-hover">
              <thead>
                <tr>
                  <th>Sl.</th>
                  <th>Donor</th>
                  <th><i class="fa fa-mobile-phone"></i> Primary</th>
                  <th><i class="fa fa-phone-square"></i> Other</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(person, index) in birthdays">
                  <td>{{ index + 1 }}</td>
                  <td>{{ person.name }}</td>
                  <td>{{ person.phone_primary }}</td>
                  <td>
                    <span v-if="person.phone_secondary.length">{{ person.phone_secondary.join(', ') }},</span>
                    <span v-if="person.phone_request">{{ person.phone_request }} (request)</span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="panel-footer">
            <a href="#" class="btn btn-default btn-sm"><i class="fa fa-calendar"></i> List all <span class="badge badge-blood">{{birthdayCount}}</span></a>
          </div>
        </div>
      </div>
      <!-- /#front-birthday-today -->
      <div class="col-sm-6" id="front-latest-donations">
        <div class="panel panel-default">
          <div class="panel-heading">
            <strong>Latest Donations</strong>
          </div>
          <div class="panel-body">
            <table class="table table-striped table-hover">
              <thead>
                <tr>
                  <th width="40%">Donor</th>
                  <th>Bl. Gr.</th>
                  <th>Date-Time</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(person, index) in latestDonations">
                  <td>
                    <a href="#">{{ person.name }}</a>
                    <p><small>{{person.reg}}</small> | <small>{{person.area}}</small></p>
                  </td>
                  <td><span class="badge badge-blood">{{ person.blood_group }}</span></td>
                  <td>{{ person.time }}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="panel-footer">
            <a href="#" class="btn btn-default btn-sm"><i class="fa fa-list-alt"></i> List all</a>
          </div>
        </div>
      </div>
      <!-- /#front-latest-donations -->
      <div class="col-sm-6">
        <div class="panel panel-default">
          <div class="panel-heading">
            <strong>Latest Activities</strong>
          </div>
          <div class="panel-body">
            <table class="table table-striped table-hover">
              <thead>
                <tr>
                  <th width="15%">Date-Time</th>
                  <th>Activity</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="activity in latestActivities">
                  <td>{{ activity.time }}</td>
                  <td>
                    <p>{{activity.activity}}</p>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="panel-footer">
            <a href="#" class="btn btn-default btn-sm"><i class="fa fa-book"></i> List all</a>
          </div>
        </div>
      </div>

    </div>
  </div>

</template>
