<template>
    <div class="p-4">
      <div class="col-lg-12 text-right">
        <v-btn
          color="info"
          @click="toggleShow"
        >
        <i class="ti-plus"></i>
          Add Agent
        </v-btn>      
      </div>
      <v-card class="mb-4 mt-4" v-if="showForm === true">
        <div class="card-header">
          <h5>New Agent</h5>
        </div>
        <div class=" p-4 ">
          <v-form  ref="form">
            <v-row align="center">
              <v-col
                :md="6"
                :lg="6"
              >
                <div class="form-row">
                  <div class="col-sm-12">
                    <v-select
                      v-model="details.site"
                      required
                      outlined
                      dense
                      label="Site"
                      :items="sites"
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
                  <div class="col-sm-12">
                    <v-text-field
                      v-model="details.phone_number"
                      required
                      outlined
                      dense
                      label="Telephone Number"
                    ></v-text-field>
                  </div>
                </div>
                <div class="form-row">
                  <div class="col-sm-12">
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

              </v-col>
            </v-row>

            <v-row class="no-padding">
              <v-col :md="6" :lg="6" class="no-padding">
                  <div class="col-sm-12">
                    <v-checkbox
                      v-model="details.credit_status"
                      outlined
                      dense
                      label="Limit Credit to Customers"
                      @click="changeLimit"
                    ></v-checkbox>
                  </div>
              </v-col>
              <v-col :lg="6" :md="6" class="no-padding">
                  <div class="col-sm-12" v-if="is_credit_limit === true">
                    <v-text-field
                      v-model="details.threshold"
                      required
                      outlined
                      dense
                      label="Threshold"
                      type="number"
                    ></v-text-field>
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
                @click="createAgent"
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
        <div class="card-header"><h5>Agents</h5></div>
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
              :items="items"
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
          </v-row>         
        </div>
        <v-data-table
          :headers="headers"
          :items="desserts"
          :search="search"
        ></v-data-table>
      </v-card>
    </div>
</template>
<script>
  export default {
    data () {
      return {
        search: '',
        is_credit_limit: false,
        loading: false,
        details: {
          threshold: 0
        },
        sites: [],
        languages: ['English','French'],
        showForm: false,
        items: [],        
        headers: [
          {
            text: 'Site',
            align: 'start',
            filterable: false,
            value: 'site',
          },
          { text: 'First Name', value: 'fname', align: 'center' },
          { text: 'Last Name', value: 'lname', align: 'center' },
          { text: 'Phone Number', value: 'telephone', align: 'center' },
          { text: 'Credit Limit?', value: 'credit', align: 'center' },
          { text: 'Balance', value: 'balance', align: 'center' },
        ],
        desserts: [],
      }
    },
    methods: {
      // notifyVue(type, message, icon, horizontalAlign, verticalAlign) {
      //   this.$notify({
      //     message: message,
      //     icon: icon,
      //     horizontalAlign: horizontalAlign,
      //     verticalAlign: verticalAlign,
      //     type: type
      //   });
      // },      
      toggleShow() {
        this.showForm = !this.showForm
      },
      changeLimit() {
        this.is_credit_limit = !this.is_credit_limit
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
      
      createAgent() {
        this.loading =  true
        this.$http.post('/admin/agents', this.details)
        .then(response => {
          console.log(`response`, response)
          this.loading = false
          if(response.data.status === true) {
            //this.notifyVue('success', response.data.message, 'ti-check', 'right', 'top')
            this.$toast.success(response.data.message)
            this.desserts .push(response.data.data)
            this.reset()
            this.is_credit_limit = false
          }
          // else {
          //   this.notifyVue('danger', response.data.message, 'ti-close', 'right', 'top')
          // }
        })
      },

      getAgents() {
        this.$http.get('/admin/agents')
        .then(response => {
          if(response.data.status === true) {
            this.desserts = response.data.data
          }
        })
      },

      getSites() {
        this.$http.get('/api/v1/sites')
        .then(response => {
          // console.log(`response`, response)
          if(response.data.status === true) {
            this.sites = response.data.data.map(site => {
              return site.name
            })
          }
        })
      },        
    },
    mounted() {
      this.getAgents()
      this.getSites()
    },    
  }
</script>

<style lang="scss">
  .no-padding {
    padding: 0 !important;
  }
</style>