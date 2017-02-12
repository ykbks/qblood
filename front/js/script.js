var app = new Vue({
  el : '#app',
  data : {
    bloodGroups : ['A+','A-','AB+','AB-','O+','O-'],
    birthdayCount : 20,
    birthdays: [
      { id: 2, name : 'Donor 1' , phone_primary: 64651324, phone_secondary : [123231,1233211], phone_request: 884533},
      { id: 4, name : 'Donor 4' , phone_primary: 64651324, phone_secondary : [123231,1233211], phone_request: 884533},
      { id: 9, name : 'Donor 9' , phone_primary: 64651324, phone_secondary : [123231,1233211], phone_request: 884533},
      { id: 13, name : 'Donor 13' , phone_primary: 64651324, phone_secondary : [123231,1233211], phone_request: 884533},
      { id: 45, name : 'Donor 45' , phone_primary: 64651324, phone_secondary : [123231,1233211], phone_request: 884533}
    ],
    latestDonations: [
      { id: 2, name : 'Donor 1', reg: 'QA' , phone_primary: 64651324, phone_secondary : [123231,1233211], phone_request: 884533, blood_group: 'A+', time: new Date(), area: 'Some Area'},
      { id: 4, name : 'Donor 4', reg: 'M-122/11' , phone_primary: 64651324, phone_secondary : [123231,1233211], phone_request: 884533, blood_group: 'A-', time: new Date(), area: 'Some Area'},
      { id: 9, name : 'Donor 9', reg: 'QA' , phone_primary: 64651324, phone_secondary : [123231,1233211], phone_request: 884533, blood_group: 'AB+', time: new Date(), area: 'Some Area'},
      { id: 13, name : 'Donor 13', reg: '155/300/S' , phone_primary: 64651324, phone_secondary : [123231,1233211], phone_request: 884533, blood_group: 'AB-', time: new Date(), area: 'Some Area'},
      { id: 45, name : 'Donor 45', reg: 'F-322/400' , phone_primary: 64651324, phone_secondary : [123231,1233211], phone_request: 884533, blood_group: 'O+', time: new Date(), area: 'Some Area'}
    ],
    latestActivities: [
      { time : new Date(), activity: 'When you use the class attribute on a custom component, those classes will be added to the component’s root element. ' },
      { time : new Date(), activity: 'When you use the class attribute on a custom component, those classes will be added to the component’s root element. ' },
      { time : new Date(), activity: 'When you use the class attribute on a custom component, those classes will be added to the component’s root element. ' },
      { time : new Date(), activity: 'When you use the class attribute on a custom component, those classes will be added to the component’s root element. ' },
      { time : new Date(), activity: 'When you use the class attribute on a custom component, those classes will be added to the component’s root element. ' },
      { time : new Date(), activity: 'When you use the class attribute on a custom component, those classes will be added to the component’s root element. ' }
    ],
  }
});
