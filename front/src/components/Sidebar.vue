<script>
export default {
  name: 'sidebar',
  data () {
    return {
    }
  }
}
</script>

<template>

  <div class="col-md-2" id="sidebar">
    <div class="sidebar content-box" style="display: block;">
        <ul class="nav">
            <!-- Main menu -->
            <li class=""><router-link :to="{name :'dashboard'}" exact><i class="glyphicon glyphicon-home"></i> Dashboard</router-link></li>
            <li class="submenu">
                 <a href="#">
                    <i class="fa fa-heartbeat"></i> Donors
                    <span class="caret pull-right"></span>
                 </a>
                 <!-- Sub menu -->
                 <ul>
                    <li><router-link :to="{name :'donors.add'}"><i class="fa fa-plus"></i> Add</router-link></li>
                    <li><router-link :to="{name: 'donors.list'}"><i class="fa fa-list-alt"></i> List</router-link></li>
                    <li><a href="#"><i class="fa fa-copy"></i> Reports</a></li>
                </ul>
            </li>
            <li class="submenu">
                 <a href="#">
                    <i class="fa fa-heart"></i> Donations
                    <span class="caret pull-right"></span>
                 </a>
                 <!-- Sub menu -->
                 <ul>
                    <li><a href="#"><i class="fa fa-plus"></i> Add</a></li>
                    <li><a href="#"><i class="fa fa-list-alt"></i> List</a></li>
                    <li><a href="#"><i class="fa fa-copy"></i> Reports</a></li>
                </ul>
            </li>
            <li class="submenu">
                 <a href="#">
                    <i class="fa fa-cogs"></i> Settings
                    <span class="caret pull-right"></span>
                 </a>
                 <!-- Sub menu -->
                 <ul>
                    <li><a href="#"><i class="fa fa-users"></i> Users</a></li>
                    <li><a href="#"><i class="fa fa-map-marker"></i> Areas</a></li>
                </ul>
            </li>
            <li><router-link to="/activities"><i class="fa fa-list-ul"></i> Activities</router-link></li>
            <li><a href="#"><i class="fa fa-power-off"></i> Log Out</a></li>
        </ul>
     </div>
  </div>

</template>
