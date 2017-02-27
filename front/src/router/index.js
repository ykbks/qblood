import Vue from 'vue'
import Router from 'vue-router'
// components
import Dashboard from 'components/Dashboard'
import Activities from 'components/Activities'
import AddDonor from 'components/AddDonor'
import DonorList from 'components/DonorList'
import Available from 'components/Available'
import DonorDetails from 'components/DonorDetails'
// components end

Vue.use(Router)

export default new Router({
  mode: 'history',
  routes: [
    {
      path: '/',
      name: 'dashboard',
      component: Dashboard
    },
    {
      path: '/activities',
      name: 'activities',
      component: Activities
    },
    {
      path: '/donors/add',
      name: 'donors.add',
      component: AddDonor
    },
    {
      path: '/donors/list',
      name: 'donors.list',
      component: DonorList
    },
    {
      path: '/donors/:id/details',
      name: 'donors.details',
      component: DonorDetails
    },
    {
      path: '/donors/available',
      name: 'donors.available',
      component: Available
    },
    {
      path: '/donors/available/:group',
      name: 'donors.availableByGroup',
      component: Available
    }
  ]
})
