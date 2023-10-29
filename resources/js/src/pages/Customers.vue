<template>
  <div class="p-4">
    <div class="col-lg-12 text-right">
      <v-btn
        color="info"
        @click="toggleShow"
      >
      <i class="ti-plus"></i>
        Add Customer
      </v-btn>      
    </div>
    <v-card class="mb-4 mt-4" v-if="showForm === true">
      <div class="card-header">
        <h5>New Customer</h5>
      </div>
      <div class=" p-4 ">
        <v-form  ref="form">
          <v-row align="center">
            <v-col
              :md="6"
              :lg="6"
            >
              <div class="form-row">
                <div class="col-sm-6">
                  <v-select
                    v-model="sitename"
                    required
                    outlined
                    dense
                    label="Site"
                    :items="sites"
                    @change="selectSite"
                  ></v-select>
                </div>
                <div class="col-sm-6">
                  <v-select
                    v-model="meter_id"
                    required
                    outlined
                    dense
                    label="Meter"
                    :items="meters"
                    @change="selectMeter"
                  ></v-select>
                </div>
              </div>
              <div class="form-row">
                <div class="col-sm-12">
                  <v-text-field
                    v-model="details.first_name"
                    required
                    outlined
                    dense
                    label="First Name"
                  ></v-text-field>
                </div>
              </div>
              <div class="form-row">
                <div class="col-sm-12">
                  <v-text-field
                    v-model="details.last_name"
                    required
                    outlined
                    dense
                    label="Last Name"
                  ></v-text-field>
                </div>
              </div>
            </v-col>

            <v-col
              :md="6"
              :lg="6"
            >
              <div class="form-row">
                <div class="col-sm-6">
                  <v-text-field
                    v-model="details.phone_number"
                    required
                    outlined
                    dense
                    label="Telephone Number"
                  ></v-text-field>
                </div>
                <div class="col-sm-6">
                  <v-text-field
                    v-model="details.email"
                    required
                    outlined
                    dense
                    label="Email"
                    type="email"
                  ></v-text-field>
                </div>
              </div>
              <div class="form-row">
                <div class="col-sm-12">
                  <v-select
                    v-model="details.language"
                    :items="languages"
                    required
                    outlined
                    dense
                    label="Language"
                  ></v-select>
                </div>
              </div>
              <div class="form-row">
                <div class="col-sm-12">
                  <v-text-field
                    v-model="details.energy_price"
                    required
                    outlined
                    dense
                    label="Energy Price"
                    type="number"
                  ></v-text-field>
                </div>
              </div>
            </v-col>
          </v-row>

          <div class="buttons text-right">
            <v-btn
              color="error"
              class="mr-4"
              @click="reset"
            >
              Cancel
            </v-btn>
            <v-btn
              color="success"
              @click="createCustomer"
            >
              Save 
              <v-progress-circular
                width="3"
                size="20"
                color="white"
                indeterminate
                class="ml-2"
                v-show="loading === true"
              >                
              </v-progress-circular>
            </v-btn>
          </div>
        </v-form>
      </div>
    </v-card>

    <v-card>
      <div class="card-header">
        <h5>Customers</h5>          
      </div>
      <v-card-title>
        <span>Filter by:</span>
      </v-card-title>
      <div class="px-4 py-2">
        <v-row align="center">
          <v-text-field
            label="By Name"
            dense
            outlined
            class="mx-2"
          ></v-text-field>
          
          <v-select
            label="By Site"
            dense
            outlined
            class="mx-2"
          ></v-select>  

          <v-text-field
            label="By Phone Number"
            dense
            outlined
            class="mx-2"
          ></v-text-field>             

          <v-text-field
            label="By Integration ID"
            dense
            outlined
            class="mx-2"
          ></v-text-field>
          <v-text-field
            label="By Label"
            dense
            outlined
            class="mx-2"
          ></v-text-field>
        </v-row>        
      </div>

      <div class="col-md-12 col-lg-12">
        <div class="v-data-table v-data-table--has-bottom theme--light">
          <div class="v-data-table__wrapper">
        <table class="table-striped table-light">
          <thead class="v-data-table-header">
            <th class="text-center">SN</th>
            <th class="text-center">Site</th>
            <th class="text-center">First Name</th>
            <th class="text-center">Last Name</th>
            <th class="text-center">Phone Number</th>
            <th class="text-center">Credit Limit?</th>
            <th class="text-center">Connection Status</th>
            <th class="text-center">Account Balance</th>
            <th class="text-center">30 Days Energy User(KWh)</th>
            <th class="text-center">Label</th>
            <th class="text-center">Manage</th>
          </thead>
          <tbody>
            <tr v-for="(customer, index) in customers" :key="index">
              <td>{{index+1}}</td>
              <td>{{customer.name}}</td>
              <td>{{customer.fname}}</td>
              <td>{{customer.lname}}</td>
              <td>{{customer.telephone}}</td>
              <td>{{customer.credit}}</td>
              <td>{{customer.status}}</td>
              <td>{{customer.balance}}</td>
              <td>{{customer.energy}}</td>
              <td>{{customer.site}}</td>
              <td>
                <div class="btn-group">
                  <a :href="'#/customers/'+customer.id" class="btn btn-sm btn-primary"><i class="ti-eye"></i></a>
                  <a href='javascript:;' @click="viewCustomer(customer.id)" class="btn btn-sm btn-secondary"><i class="ti-pencil"></i></a>
                  <a href="javascript:;" @click="deleteCustomer(customer.id)" class="btn btn-sm btn-danger"><i class="ti-trash"></i></a>
                </div>
              </td>
            </tr>
          </tbody>
        </table>

          </div>
        </div>
      </div>
    </v-card>
  </div>
</template>
<script>
  import spinner from '../assets/img/loader.gif'
  export default {
    data () {
      return {
        search: '',
        spinner: spinner,
        meter_id: '',
        sitename: '',
        showForm: false,
        details: {},
        loading: false,
        meters: [],
        meterList: [],
        siteList: [],
        sites: [],
        languages: ['English','French'],
        type: ["", "info", "success", "warning", "danger"],
        notifications: {
          topCenter: false
        },
        headers: [
          {
            text: 'Site',
            align: 'start',
            filterable: false,
            value: 'name',
          },
          { text: 'First Name', value: 'fname', align: 'center' },
          { text: 'Last Name', value: 'lname', align: 'center' },
          { text: 'Phone Number', value: 'telephone', align: 'center' },
          { text: 'Credit Limit?', value: 'credit', align: 'center' },
          { text: 'Connection Status', value: 'status', align: 'center' },
          { text: 'Account Balance', value: 'balance', align: 'center' },
          { text: '30-Days Energy use(kWh)', value: 'energy', align: 'center' },
          { text: 'Labels', value: 'labels', align: 'center' },
          { text: 'Integration ID', value: 'intID', align: 'center' },
        ],
        customers: [],
      }
    },
    methods: {  
      selectMeter() {
        this.details.meter_id = this.meterList.filter(meter => meter.meter_number == this.meter_id)[0].id
      },
      selectSite() {
        let site = this.siteList.filter(site => site.name == this.sitename)
        this.details.site_id = site[0].id
        this.details.site = site[0].name
      },
      toggleShow() {
        this.showForm = !this.showForm
      },
      validate () {
        this.$refs.form.validate()
      },
      reset () {
        this.$refs.form.reset()
      },
      resetValidation () {
        this.$refs.form.resetValidation()
      },  

      viewCustomer(id) {
        console.log(id)
        this.$router.push({name:'customerDetails', params: {id}})
      },          
      
      deleteCustomer(id) {
        this.$http.delete(`/api/v1/customer/${id}`)
        .then(response => {
          if(response.data.status) {
            console.log(response.data.message)
          }
          else {
            console.log(response.data.message)
          }
        })
      },
      
      createCustomer() {
        this.loading = true
        this.$http.post('/admin/customers', this.details)
        .then(response => {
          this.loading = false
          if(response.data.status === true) {
            this.$toast.success(response.data.data)
            this.customers .push(response.data.data)
            this.reset()
            this.getMeters()
          }
        })
      },

      getCustomers() {
        this.$http.get('/admin/customers')
        .then(response => {
          //console.log(`response`, response)
          if(response.data.status === true) {
            this.customers = response.data.data
            localStorage.setItem('customers', JSON.stringify(this.customers))
          }
        })
      },
      
      getMeters() {
        this.$http.get('/api/v1/meters/unasigned')
        .then(response => {
          if(response.status) {
            this.meterList = response.data.data
            this.meters = this.meterList.map(meter => {
              return meter.meter_number
            })
          } 
          else {
            this.meters = []
          }
        })
        //.catch(err => console.log(`err`, err))
      },
      
      getSites() {
        this.$http.get('/api/v1/sites')
        .then(response => {
          if(response.status) {
            this.siteList = response.data.data
            this.sites = this.siteList.map(site => {
              return site.name
            })
          } 
          else {
            this.sites = []
          }
        })
        //.catch(err => console.log(`err`, err))
      }
    },
    mounted() {
      this.getCustomers()
      this.getMeters()
      this.getSites()
    },
  }
</script>

<style lang="scss">

  .btn-group {
    border: none !important;
    a {
      color: #fff !important;
    }

    .btn-danger {
      background: #e3342f;
    }

    .btn-primary {
      background: #3490dc;
    }
    
  }
</style>