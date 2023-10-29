import DashboardLayout from "../layout/dashboard/DashboardLayout.vue";
// GeneralViews
import NotFound from "../pages/NotFoundPage.vue";

// Admin pages
import Dashboard from "../pages/Dashboard.vue";
import UserProfile from "../pages/UserProfile.vue";
import Notifications from "../pages/Notifications.vue";
import Icons from "../pages/Icons.vue";
import Maps from "../pages/Maps.vue";
import Typography from "../pages/Typography.vue";
import TableList from "../pages/TableList.vue";
import Site from '../pages/Site.vue';
import Site2 from '../pages/Site2.vue';
import Cores from '../pages/Cores.vue';
import Meters from '../pages/Meters.vue';
import Customers from '../pages/Customers.vue';
import CustomerDetails from '../pages/CustomerDetails.vue';
import Agents from '../pages/Agents.vue';
import Transactions from '../pages/Transactions.vue';
import AgentMessages from '../pages/AgentMessages.vue';
import CustomerMessages from '../pages/CustomerMessages.vue';
import HardwareMessages from '../pages/HardwareMessages.vue';
import Reports from '../pages/Reports.vue';
import Account from '../pages/Account.vue';
import Users from '../pages/Users.vue';
import Uploads from '../pages/Uploads.vue';
import Settings from '../pages/Settings.vue';

const routes = [
  {
    path: "/",
    component: DashboardLayout,
    redirect: "/dashboard",
    children: [
      {
        path: "dashboard",
        name: "dashboard",
        component: Dashboard
      },
      {
        path: "stats",
        name: "stats",
        component: UserProfile
      },
      {
        path: "notifications",
        name: "notifications",
        component: Notifications
      },
      {
        path: "icons",
        name: "icons",
        component: Icons
      },
      {
        path: "maps",
        name: "maps",
        component: Maps
      },
      {
        path: "typography",
        name: "typography",
        component: Typography
      },
      {
        path: "table-list",
        name: "table-list",
        component: TableList
      },
      {
        path: "sites",
        name: "sites",
        component: Site2
      },
      {
        path: "cores",
        name: "cores",
        component: Cores
      },
      {
        path: "meters",
        name: "meters",
        component: Meters
      },
      {
        path: "customers",
        name: "customers",
        component: Customers,
        // children: [
        // ]
      },
      {
        path: 'customers/:id',
        name: 'customerDetails',
        component: CustomerDetails
      },
      {
        path: "agents",
        name: "agents",
        component: Agents
      },
      {
        path: "transactions",
        name: "transactions",
        component: Transactions
      },
      {
        path: "agent-messages",
        name: "agent-messages",
        component: AgentMessages
      },
      {
        path: "customer-messages",
        name: "customer-messages",
        component: CustomerMessages
      },
      {
        path: "hardware-messages",
        name: "hardware-messages",
        component: HardwareMessages
      },
      {
        path: "reports",
        name: "reports",
        component: Reports
      },
      {
        path: "account",
        name: "account",
        component: Account
      },
      {
        path: "users",
        name: "users",
        component: Users
      },
      {
        path: "uploads",
        name: "uploads",
        component: Uploads
      },
      {
        path: "alerts",
        name: "alerts",
        component: Notifications
      },
      {
        path: "settings",
        name: "settings",
        component: Settings
      },
    ]
  },
  { path: "*", component: NotFound }
];

/**
 * Asynchronously load view (Webpack Lazy loading compatible)
 * The specified component must be inside the Views folder
 * @param  {string} name  the filename (basename) of the view to load.
function view(name) {
   var res= require('../components/Dashboard/Views/' + name + '.vue');
   return res;
};**/

export default routes;
