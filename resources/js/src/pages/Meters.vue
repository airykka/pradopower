<template>
    <div class="p-4">
      <v-card>
        <div class="card-header"><h5>Meters</h5></div>
        <v-card-title>
          <span>Filter by:</span>
        </v-card-title>
        <div class="px-4 py-2">
          <v-row align="center">
          <v-col md="6" lg="6">
            <v-text-field
              label="By Site Name"
              dense
              solo
              class="mx-2"
              v-model="searchQuery.name"
            ></v-text-field>
          </v-col>
<!--             
            <v-select
              :items="items"
              label="By Core"
              dense
              solo
              class="mx-2"
            ></v-select>   -->
          <v-col md="6" lg="6">
            <v-text-field
              label="By Reference"
              dense
              solo
              class="mx-2"
              v-model="searchQuery.reference"
            ></v-text-field>
          </v-col>
          </v-row>          
          <!-- <v-row align="center">              
            <v-select
              :items="items"
              label="Version"
              dense
              solo
              class="mx-2"
            ></v-select>

            <v-select
              :items="items"
              label="Assigned to active site?"
              dense
              solo
              class="mx-2"
            ></v-select>

            <v-select
              :items="items"
              label="Communication indicator"
              dense 
              solo
              class="mx-2"
            ></v-select>
          </v-row>         -->
          <v-dialog v-model="dialogAssign" max-width="500px">
            <v-card>
              <v-card-title class="headline">Assign Meter</v-card-title>
              <v-card-text>
                <v-container>
                  <v-row>
                    <v-col
                      cols="12"
                      sm="6"
                      md="6"
                    >
                      <v-select
                        v-model="details"
                        label="Customer"
                        :items="customerList"
                        :hint="`${details.name}, ${details.id}`"
                        item-text="name"
                        item-value="id"
                        persistent-hint
                        return-object
                      >
                      </v-select>
                    </v-col>
                    <v-col
                      cols="12"
                      sm="6"
                      md="6"
                    >
                      <v-text-field
                        v-model="meter.meter_number"
                        label="Meter Number"
                        readonly
                      ></v-text-field>
                    </v-col>
                  </v-row>                
                </v-container>
              </v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn class="btn-danger btn" color="white ldarken-1" text @click="closeAssign">Cancel</v-btn>
                <v-btn class="btn btn-success" color="white ddarken-1" text @click="submitData">
                  Submit
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
                <v-spacer></v-spacer>
              </v-card-actions>
            </v-card>
          </v-dialog>      
        </div>
        <v-data-table
          :headers="headers"
          :items="meterList"
        >
          <template v-slot:item.actions="{ item }">
            <v-icon
              small
              class="mr-2"
              @click="assignMeter(item)"
            >
              mdi-pencil
            </v-icon>
          </template>        
        </v-data-table>
      </v-card>
    </div>
</template>
<script>
  export default {
    data () {
      return {
        details: {id: '', name: ''},
        searchQuery: {
          site: '',
          reference: ''
        },
        dialogAssign: false,
        meter: {},
        defaultMeter: {},
        meterIndex: -1,
        items: [],
        headers: [
          {
            text: 'Meter Number',
            align: 'start',
            filterable: false,
            value: 'meter_number',
          },
          {
            text: 'Core',
            align: 'start',
            filterable: false,
            value: 'site',
          },
          { text: 'Reference', value: 'count', align: 'start' },
          { text: 'Utility', value: 'utility', align: 'center' },
          { text: 'Customer', value: 'customer_id', align: 'center' },
          { text: 'Connection Status', value: 'status', align: 'center' },
          { text: 'Power Limit Active?', value: 'active_unit', align: 'center' },
          { text: 'Uptime Last 7-Days', value: 'uptime', align: 'center'},
          { text: 'Actions', value: 'actions', sortable: false, align: 'center'},
        ],
        meters: [],
        customers: [],
        loading: false,
      }
    },
    computed: {
      meterList() {
        return this.meters.filter(meter => meter.meter_number.includes(this.searchQuery.reference))
        // return this.meters.filter(meter => meter.site.toLowerCase().includes(this.searchQuery.name) 
        // || meter.meter_number.includes(this.searchQuery.reference))
      },
      customerList() {
        return this.customers.map((customer, id) => {
          return {
            id: customer.id,
            name: customer.fname+' '+customer.lname,

          }
        })
      }
    },
    watch: {
      dialog (val) {
        val || this.close()
      },
      dialogAssign (val) {
        val || this.close()
      },
    },    
    methods: { 
      close () {
        this.dialogAssign = false
      },   
      closeAssign () {
        this.$nextTick(() => {
          this.meter = Object.assign({}, this.defaultMeter)
          this.meterIndex = -1
        })
        this.dialogAssign = false
      },   
      assignMeter(meter) {
        this.meterIndex = this.meters.indexOf(meter)
        this.meter = Object.assign({}, meter)
        this.dialogAssign = true        
        console.log("METER", meter)
      } , 
      submitData() {
        this.loading = true
        this.$http.post('/api/v1/meters/operations/assign-customer', {
          meter_number: this.meter.meter_number,
          customer_id: this.details.id
        })
        .then(response => {
          console.log(response)
          if(response.data.status) {
            this.$toastr.success(response.data.message)
          }
        })
        .catch(err => console.log(err))
        .finally(() => this.loading = false)
      }, 
      getMeters() {
        this.$http.get('/api/meters/meter-list')
        .then(response => {
          console.log(response.data)
          if(response.status) {
            this.meters = response.data
          }
        })
        // .catch( err => console.log(`err`, err))
      },
      steamaMeters() {
        this.$http.get('/api/v1/meters/steama/')
        .then(response => {
          if(response.data.status === true) {
            this.meters = response.data.data.results
          }
        })
      },
      getMeterList() {
        this.$http.get('/api/v1/meters')
        .then(response => {
          // console.log(`response.data.data`, response.data)
          if(response.status) {
            this.meters = response.data.data
          }
        })
      },
      getCustomers() {
        this.$http.get('/api/v1/customer')
        .then(response => {
          // console.log(`response.data.data`, response.data)
          if(response.status) {
            this.customers = response.data.data
          }
        })
      },
    },
    created() {
      this.getCustomers()
      this.getMeterList()
      //this.steamaMeters()
    },
  }
</script>