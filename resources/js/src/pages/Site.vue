<template>
  <div class="p-4">
    <div class="col-lg-12 text-right">
      <v-btn
        color="info"
        @click="toggleShow"
      >
      <i class="ti-plus"></i>
        New Site
      </v-btn>      
    </div>  

    <v-card class="mb-4 mt-4" v-if="showForm === true">
      <div class="card-header">
        <h5>New Site</h5>
      </div>
      <div class=" p-4 ">
        <v-form  ref="form">
          <v-row align="center">
            <v-col
              :md="4"
              :lg="4"
            >
              <div class="form-row">
                <div class="col-sm-12">
                  <v-text-field
                    v-model="details.name"
                    required
                    outlined
                    dense
                    label="Site Name"
                  ></v-text-field>
                </div>
              </div>
            </v-col>
            <v-col
              :md="4"
              :lg="4"
            >            
              <div class="form-row">
                <div class="col-sm-12">
                  <v-text-field
                    v-model="details.currency"
                    required
                    outlined
                    dense
                    label="Currency"
                  ></v-text-field>
                </div>
              </div>
            </v-col>
            <v-col
              :md="4"
              :lg="4"
            >            
              <div class="form-row">
                <div class="col-sm-12">
                  <v-text-field
                    v-model="details.phone_number"
                    required
                    outlined
                    dense
                    label="Phone Number"
                    type="tel"
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
              @click="createSite"
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
      <div class="card-header"><h5>Sites</h5></div>
      <v-card-title>
        <span>Filter by:</span>
      </v-card-title>
      <div class="px-4 py-2">
        <v-row align="center">
          <v-text-field
            label="By Site Name"
            dense
            outlined
            class="mx-2"
          ></v-text-field>

          <v-text-field
            label="Label"
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
        loading: false,
        showForm: false,
        search: '',
        details: {},
        headers: [
          {
            text: 'Site',
            align: 'start',
            filterable: false,
            value: 'name',
          },
          { text: 'Meter Number', value: 'Meterno', align: 'center' },
          { text: 'Currency', value: 'currency', align: 'center' },
          { text: '30-Days Revenue', value: 'revenue', align: 'center' },
          { text: '30-Days Utility use (kWh)', value: 'utility', align: 'center' },
          { text: 'Label', value: 'Label' },
        ],
        desserts: []
      }
    },
    methods: {        
      toggleShow() {
        this.showForm = !this.showForm
      },    
      reset () {
        this.$refs.form.reset()
      },      
      createSite() {
        this.loading = true
        this.$http.post('/api/v1/sites', this.details)
        .then(response => {
          console.log(`response`, response)
          this.loading = false
          if(response.data.status === true) {
            this.desserts .push(response.data.data)
            this.reset()
            this.$toast.success(response.data.data)
            this.getSites()
          }
        })
      },  
      getSites() {
        this.$http.get('/api/v1/sites')
        .then(response => {
          console.log(`response`, response)
          if(response.data.status === true) {
            this.desserts = response.data.data
          }
        })
      },            
    },
    mounted() {
      this.getSites()
    },
  }
</script>