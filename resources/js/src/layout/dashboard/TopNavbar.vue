<template>
  <nav class="navbar navbar-expand-lg navbar-dark navbar-fixed-top">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">{{routeName}}</a>
      <button class="navbar-toggler navbar-burger"
              type="button"
              @click="toggleSidebar"
              :aria-expanded="$sidebar.showSidebar"
              aria-label="Toggle navigation">
        <span class="navbar-toggler-bar"></span>
        <span class="navbar-toggler-bar"></span>
        <span class="navbar-toggler-bar"></span>
      </button>
      <div class="collapse navbar-collapse">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a href="/#/sites" class="nav-link">
              <i class="ti-server"></i>
              <p>Sites</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/#/agents" class="nav-link">
              <i class="ti-user"></i>
              <p>Agents</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/#/customers" class="nav-link">
              <i class="ti-user"></i>
              <p>Customers</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/#/transactions" class="nav-link">
              <i class="ti-briefcase"></i>
              <p>Transactions</p>
            </a>
          </li>
          <!-- <drop-down class="nav-item"
                     title="Hardwares"
                     title-classes="nav-link"
                     icon="ti-harddrives">
            <a class="dropdown-item" href="/#/cores">Cores</a>
            <a class="dropdown-item" href="/#/meters">Meters</a>
          </drop-down> -->
          <drop-down class="nav-item"
                     title="Messasges"
                     title-classes="nav-link"
                     icon="ti-email">
            <a class="dropdown-item" href="/#/agent-messages">Agent Messages</a>
            <a class="dropdown-item" href="/#/customer-messages">Cusomer Messages</a>
            <a class="dropdown-item" href="/#/hardware-messages">Hardware Messages</a>
          </drop-down>
          <drop-down class="nav-item"
            title="More"
            title-classes="nav-link"
          >
            <a class="dropdown-item" href="#">Help Center</a>
            <a class="dropdown-item" href="#">Support</a>
            <a class="dropdown-item" href="/#/alerts">Alerts</a>
            <a class="dropdown-item" href="#">Charts</a>
            <a class="dropdown-item" href="/#/reports">Reports</a>
            <a class="dropdown-item" href="/#/uploads">Data Uploads</a>
            <a class="dropdown-item" href="/#/account">My Account</a>
            <a class="dropdown-item" href="/#/users">User Admin</a>
            <hr>
            <a class="dropdown-item" href="#">Logout</a>
          </drop-down>
        </ul>
      </div>
    </div></nav>
</template>
<script>
export default {
  computed: {
    routeName() {
      const { name } = this.$route;
      return this.capitalizeFirstLetter(name);
    }
  },
  data() {
    return {
      activeNotifications: false
    };
  },
  methods: {
    capitalizeFirstLetter(string) {
      return string.charAt(0).toUpperCase() + string.slice(1);
    },
    toggleNotificationDropDown() {
      this.activeNotifications = !this.activeNotifications;
    },
    closeDropDown() {
      this.activeNotifications = false;
    },
    toggleSidebar() {
      this.$sidebar.displaySidebar(!this.$sidebar.showSidebar);
    },
    hideSidebar() {
      this.$sidebar.displaySidebar(false);
    }
  }
};
</script>
<style>
</style>
