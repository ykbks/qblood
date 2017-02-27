<script>
export default {
  name: 'navbar',
  props: ['groups', 'availableCount'],
  data () {
    return {
    }
  }
}
</script>

<template>
  <div id="navbar">
      <nav class="navbar navbar-default navbar-static-top" role="navigation">
        <div class="container-fluid">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#nav-top" aria-expanded="false">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <router-link to="/" class="navbar-brand">Q-Blood Sylhet</router-link>
          </div>
          <div class="collapse navbar-collapse" id="nav-top">



            <ul class="nav navbar-nav navbar-right">
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-clock-o"></i> Available <span class="caret"></span></a>
                <ul class="dropdown-menu">
                  <li v-for="item in availableCount">
                    <router-link :to="{ name: 'donors.availableByGroup', params: { group : item.group } }">{{ item.group }} <span class="badge pull-right">{{ item.count }}</span></router-link>
                  </li>
                </ul>
              </li>
              <li><router-link :to="{name :'donors.add'}"><i class="fa fa-plus"></i> Add Donor</router-link></li>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-user"></i> User Name <span class="caret"></span></a>
                <ul class="dropdown-menu">
                  <li><a href="#"><i class="fa fa-cog"></i> Profile</a></li>
                  <li><a href="#"><i class="fa fa-cogs"></i> Settings</a></li>
                  <li class="divider"></li>
                  <li><a href="#"><i class="fa fa-power-off"></i> Log Out</a></li>
                </ul>
              </li>
            </ul>


            <form class="navbar-form navbar-right">
              <div class="form-group">
                <input type="text" class="form-control" placeholder="Search Donors">
              </div>
              <!-- <button type="submit" class="btn btn-default">Submit</button> -->
            </form>

          </div><!-- /.navbar-collapse -->
        </div>
      </nav>

  </div>


</template>
