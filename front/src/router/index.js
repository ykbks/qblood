import Vue from 'vue'
import Router from 'vue-router'
import Dashboard from 'components/Dashboard'
import Activities from 'components/Activities'
import AddDonor from 'components/AddDonor'

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
    }
  ]
})
