<template>
  <div class="wrapper">
    <side-bar>
      <template slot="links">
        <sidebar-link to="/dashboard" name="Dashboard" icon="ti-home"/>
        <!-- <sidebar-link to="/stats" name="Clusters" icon="ti-harddrives"/> -->
        <sidebar-link to="/cores" name="Cores" icon="ti-harddrives"/>
        <sidebar-link to="/meters" name="Meters" icon="ti-harddrives"/>
        <!-- <sidebar-link to="/table-list" name="Mini Grids" icon="ti-view-list-alt"/> -->
        <sidebar-link to="/typography" name="Tickets" icon="ti-layers-alt"/>
        <!-- <sidebar-link to="/icons" name="Tariffs" icon="ti-money"/> -->
        <!-- <sidebar-link to="/maps" name="Targets" icon="ti-target"/> -->
        <sidebar-link to="/notifications" name="SMS" icon="ti-email"/>
        <sidebar-link to="/reports" name="Reports" icon="ti-stats-up"/>
        <sidebar-link to="/settings" name="Settings" icon="ti-settings"/>
      </template>
      <mobile-menu>
        <li class="nav-item">
          <a class="nav-link">
            <i class="ti-panel"></i>
            <p>Stats</p>
          </a>
        </li>
        <drop-down class="nav-item"
                   title="5 Notifications"
                   title-classes="nav-link"
                   icon="ti-bell">
          <a class="dropdown-item">Notification 1</a>
          <a class="dropdown-item">Notification 2</a>
          <a class="dropdown-item">Notification 3</a>
          <a class="dropdown-item">Notification 4</a>
          <a class="dropdown-item">Another notification</a>
        </drop-down>
        <li class="nav-item">
          <a class="nav-link">
            <i class="ti-settings"></i>
            <p>Settings</p>
          </a>
        </li>
        <li class="divider"></li>
      </mobile-menu>
    </side-bar>
    <div class="main-panel">
      <top-navbar></top-navbar>

      <dashboard-content @click.native="toggleSidebar">

      </dashboard-content>

      <content-footer></content-footer>
    </div>
  </div>
</template> 
<style lang="scss">
</style>
<script>
import TopNavbar from "./TopNavbar.vue";
import ContentFooter from "./ContentFooter.vue";
import DashboardContent from "./Content.vue";
import MobileMenu from "./MobileMenu";
export default {
  components: {
    TopNavbar,
    ContentFooter,
    DashboardContent,
    MobileMenu
  },
  methods: {
    toggleSidebar() {
      if (this.$sidebar.showSidebar) {
        this.$sidebar.displaySidebar(false);
      }
    }
  }
};
</script>
