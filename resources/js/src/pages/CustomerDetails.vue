<template>
  <div>
    <v-card class="mb-4 mt-4">
      <div class="card-header">
        <h5>Customer Details</h5>
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
                  <v-text-field
                    v-model="details.site"
                    required
                    outlined
                    dense
                    label="Site"
                  ></v-text-field>
                </div>
                <div class="col-sm-6">
                  <v-text-field
                    v-model="details.meter_id"
                    required
                    outlined
                    dense
                    label="Meter"
                  ></v-text-field>
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
                  <v-text-field
                    v-model="details.language"
                    required
                    outlined
                    dense
                    label="Language"
                  ></v-text-field>
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
                    type="text"
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
              @click="saveChanges"
            >
              Save Changes
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

    <v-card class="mt-4 mb-4">
      <div class="card-header">
        <h5>Details</h5>
      </div>
      <div class="card-body">
        <v-row>
          <v-col :md="6" :lg="6">
            <h5>Bio Data</h5>
            <p><strong>Name:</strong> <span>{{details.fname+' '+details.lname}}</span></p>
            <p><strong>Phone Number:</strong> <span>{{details.phone_number}}</span></p>
            <p><strong>Email:</strong> <span>{{details.email}}</span></p>
            <p><strong>Account Type:</strong> <span>{{details.user_type}}</span></p>
          </v-col>
        </v-row>
        <hr>

        <v-row>
          <v-col :md="6" :lg="6">
            <h5>Wallet</h5>
            <div v-if="details.wallet">
              <p>
                <strong>Account Number:</strong> 
                <span v-if="details.wallet">{{details.wallet.account_number}}</span>
              </p>
              <p><strong>Current Balance:</strong> <span>₦{{details.wallet.balance}}</span></p>
              <p><strong>Prev Balance:</strong> <span>₦{{details.wallet.prev_balance}}</span></p>
              <p><strong>Last  Topup Amount:</strong> <span>₦{{details.wallet.amount}}</span></p>
              <p><strong>Last Updated:</strong> <span>{{details.wallet.updated_at_formatted}}</span></p>
            </div>
            <p v-else>No Wallet created</p>
          </v-col>
          <v-col :md="6" :lg="6">
            <h5>Electricity</h5>
            <p><strong>Site:</strong> <span>{{details.site}}</span></p>
            <p><strong>Meter Balance:</strong> <span v-if="details.wallet">{{details.wallet.balance}}</span><span v-else>0.00</span></p>
            <p><strong>Last  Topup unit:</strong> <span v-if="details.wallet">₦{{details.wallet.amount}}</span><span v-else>₦0.00</span></p>
            <p><strong>Last Topup Date:</strong> <span v-if="details.wallet">{{details.wallet.updated_at_formatted}}</span><span v-else>NIL</span></p>
            <v-btn
              color="primary"
              @click="toggleForm"
              class="mb-4"
            >
              Recharge Meter 
            </v-btn>             
            <v-form v-show="isPurchase">
              <div v-show="isSuccess">
                <p><strong>Amount:</strong> <span>{{tokenData.total_paid}}</span></p>
                <p><strong>Unit:</strong> <span>{{tokenData.total_unit}}</span></p>
                <p><strong>Token:</strong> <span>{{tokenData.token}}</span></p>
              </div>              
              <div class="row">
                <div class="col">
                  <v-text-field
                    v-model="purchase.units"
                    required
                    outlined
                    dense
                    label="Units"
                    type="number"
                  ></v-text-field>
                </div>
                <div class="col">
                  <v-btn
                    color="success"
                    @click="rechargeMeter"
                  >
                    Submit 
                    <v-progress-circular
                      width="3"
                      size="20"
                      color="white"
                      indeterminate
                      class="ml-2"
                      v-show="isLoading === true"
                    >                
                    </v-progress-circular>
                  </v-btn>              
                </div>
              </div>
            </v-form>
          </v-col>
        </v-row>
        <hr>

        <v-row>
          <v-col :lg="6" :md="6">
            <h5>Transactions</h5>
            <div class="v-data-table v-data-table--has-bottom theme--light">
              <div class="v-data-table__wrapper">
                <table class="table-striped table-light">
                  <thead class="v-data-table-header">
                    <th>SN</th>
                    <th>Reference</th>
                    <th>Amount</th>
                    <th>Type</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Date</th>
                  </thead>
                  <tbody>
                    <tr v-for="(transaction, index) in details.transactions" :key="index">
                      <td>{{index+1}}</td>
                      <td>{{transaction.reference}}</td>
                      <td>{{transaction.formatted_amount}}</td>
                      <td>{{transaction.type}}</td>
                      <td>{{transaction.description}}</td>
                      <td>{{transaction.status}}</td>
                      <td>{{transaction.created_at}}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </v-col>
          <v-col :lg="6" :md="6">
            <h5>Purchases</h5>
            <div class="v-data-table v-data-table--has-bottom theme--light">
              <div class="v-data-table__wrapper">
                <table class="table-striped table-light">
                  <thead class="v-data-table-header">
                    <th>SN</th>
                    <th>Total Units</th>
                    <th>Total Paid</th>
                    <th>Unit</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Date</th>
                  </thead>
                  <tbody>
                    <tr v-for="(purchase, index) in details.purchases" :key="index">
                      <td>{{index+1}}</td>
                      <td>{{purchase.total_unit}}</td>
                      <td>{{purchase.total_paid}}</td>
                      <td>{{purchase.unit}}</td>
                      <td>{{purchase.price}}</td>
                      <td>{{purchase.status}}</td>
                      <td>{{purchase.created_at}}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </v-col>
        </v-row>

        <hr>

      </div>
    </v-card>
  </div>
</template>
<script>
export default {
  data() {
    return {
      customer: {},
      details: {},
      purchase: {},
      loading: false,
      isLoading: false,
      isPurchase: false,
      isSuccess: false,
      tokenData: {}
    }
  },
  methods: {
    reset () {
      //this.$refs.form.reset()
      this.getCustomer()
    },
    toggleForm() {
      this.isPurchase = !this.isPurchase
    },    
    getCustomer() {
      let data = localStorage.getItem('customers')
      let customers = JSON.parse(data)
      this.customer = customers.filter(customer => customer.id === this.$route.params.id)[0]
      this.details = this.customer
      this.details.first_name = this.customer.fname
      this.details.last_name = this.customer.lname
      this.details.energy_price = this.customer.balance
      this.purchase.meter_number = this.customer.meter.meter_number
      this.purchase.user_id = this.customer.id
      this.purchase.phone_number = this.customer.telephone
      this.purchase.last_name = this.customer.lname
      this.purchase.email = this.customer.email
      console.log(`this.customer`, this.customer)
    },
    saveChanges() {
      this.loading = true
    },
    rechargeMeter() {
      this.isLoading = true
      this.$http.post('/api/v1/meters/operations/purchase', this.purchase)
      .then(response => {
        this.isLoading = false
        if(response.data.status === true) {
          this.$toast.success(response.data.message)
          this.purchase.amount = ''
          this.tokenData = response.data.data
          this.isSuccess = true
        }
      })
    }
  },
  mounted() {
    this.getCustomer()
  },
}
</script>