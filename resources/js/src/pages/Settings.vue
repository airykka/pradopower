<template>
  <div class="p-4 content-wrapper">
    <v-card class="mt-4 mb-4">
      <div class="card-header"><h5>Payment Settings</h5></div>
      <div class=" p-4 ">
        <v-form ref="form">
          <v-row class="align-center">
            <v-col :md="6" :lg="6">
              <div class="form-row">
                <div class="col-sm-12">
                  <v-text-field
                    v-model="details.monnify_url"
                    required
                    outlined
                    dense
                    label="Monnify Url"
                  ></v-text-field>
                </div>
              </div>
            </v-col>
            <v-col :lg="6" :md="6">
              <div class="form-row">
                <div class="col-sm-12">
                  <v-text-field
                    v-model="details.monnify_apiKey"
                    required
                    outlined
                    dense
                    label="Monnify API Key"
                  ></v-text-field>
                </div>
              </div>
            </v-col>
          </v-row>
          
          <v-row class="align-center">
            <v-col :md="6" :lg="6">
              <div class="form-row">
                <div class="col-sm-12">
                  <v-text-field
                    v-model="details.monnify_contract_code"
                    required
                    outlined
                    dense
                    label="Contract Code"
                  ></v-text-field>
                </div>
              </div>
            </v-col>
            <v-col :lg="6" :md="6">
              <div class="form-row">
                <div class="col-sm-12">
                  <v-text-field
                    v-model="details.monnify_secrete_key"
                    required
                    outlined
                    dense
                    label="Secret Key"
                  ></v-text-field>
                </div>
              </div>
            </v-col>

          </v-row>
          <v-row class="align-center">
            <v-col :md="12" :lg="12">
              <div class="form-row">
                <div class="col-sm-12">
                  <v-text-field
                    v-model="details.monnify_walletID"
                    required
                    outlined
                    dense
                    label="Monnify Wallet ID/Account number"
                  ></v-text-field>
                </div>
              </div>
            </v-col>

          </v-row>

          <!-- <div class="buttons text-right">
            <v-btn
              color="success"
              @click="saveSettings"
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
          </div> -->
        </v-form>
      </div>
    </v-card>

    <v-card class="mt-4 mb-4">
      <div class="card-header"><h5>Finance Settings</h5></div>
      <div class=" p-4 ">
        <v-form ref="form">
          <v-row class="align-center">
            <v-col :md="4" :lg="4">
              <div class="form-row">
                <div class="col-sm-12">
                  <v-text-field
                    v-model="details.agent_commission"
                    required
                    outlined
                    dense
                    type="number"
                    label="Agent Commission"
                  ></v-text-field>
                </div>
              </div>
            </v-col>
            <v-col :lg="4" :md="4">
              <div class="form-row">
                <div class="col-sm-12">
                  <v-text-field
                    v-model="details.kwh_charge"
                    required
                    outlined
                    dense
                    label="KWh charge"
                    type="number"
                  ></v-text-field>
                </div>
              </div>
            </v-col>
            <v-col :lg="4" :md="4">
              <div class="form-row">
                <div class="col-sm-12">
                  <v-text-field
                    v-model="details.unit_price"
                    required
                    outlined
                    dense
                    label="Unit Price"
                    type="number"
                  ></v-text-field>
                </div>
              </div>
            </v-col>
          </v-row>

          <!-- <div class="buttons text-right">
            <v-btn
              color="success"
              @click="saveSettings"
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
          </div> -->
        </v-form>
      </div>
    </v-card>

    <v-card class="mt-4 mb-4">
      <div class="card-header"><h5>Calin Settings</h5></div>
      <div class=" p-4 ">
        <v-form ref="form">
          <v-row class="align-center">
            <v-col :md="6" :lg="6">
              <div class="form-row">
                <div class="col-sm-12">
                  <v-text-field
                    v-model="details.calin_company_name"
                    required
                    outlined
                    dense
                    type="text"
                    label="Company Name"
                  ></v-text-field>
                </div>
              </div>
            </v-col>
            <v-col :lg="6" :md="6">
              <div class="form-row">
                <div class="col-sm-12">
                  <v-text-field
                    v-model="details.calin_user_name"
                    required
                    outlined
                    dense
                    type="text"
                    label="User Name"
                  ></v-text-field>
                </div>
              </div>
            </v-col>
            <v-col :lg="6" :md="6">
              <div class="form-row">
                <div class="col-sm-12">
                  <v-text-field
                    v-model="details.calin_password"
                    required
                    outlined
                    dense
                    type="password"
                    label="Password"
                  ></v-text-field>
                </div>
              </div>
            </v-col>
            <v-col :lg="6" :md="6">
              <div class="form-row">
                <div class="col-sm-12">
                  <v-text-field
                    v-model="details.calin_password_vend"
                    required
                    outlined
                    dense
                    type="password"
                    label="Vend Password"
                  ></v-text-field>
                </div>
              </div>
            </v-col>
          </v-row>
        </v-form>
      </div>
    </v-card>

    <v-card class="mt-4 mb-4">
      <div class="card-header"><h5>Steama Settings</h5></div>
      <div class=" p-4 ">
        <v-form ref="form">
          <v-row class="align-center">
            <v-col :md="12" :lg="12">
              <div class="form-row">
                <div class="col-sm-12">
                  <v-text-field
                    v-model="details.steama_url"
                    required
                    outlined
                    dense
                    type="text"
                    label="API url"
                  ></v-text-field>
                </div>
              </div>
            </v-col>
            <v-col :lg="6" :md="6">
              <div class="form-row">
                <div class="col-sm-12">
                  <v-text-field
                    v-model="details.steama_username"
                    required
                    outlined
                    dense
                    type="text"
                    label="Steama Username"
                  ></v-text-field>
                </div>
              </div>
            </v-col>
            <v-col :lg="6" :md="6">
              <div class="form-row">
                <div class="col-sm-12">
                  <v-text-field
                    v-model="details.steama_password"
                    required
                    outlined
                    dense
                    type="password"
                    label="Password"
                  ></v-text-field>
                </div>
              </div>
            </v-col>
          </v-row>
        </v-form>
      </div>
    </v-card>

    <v-card class="mt-4 mb-4">
      <div class="card-header"><h5>SMS Provider Settings</h5></div>
      <div class=" p-4 ">
        <v-form ref="form">
          <v-row class="align-center">
            <v-col :md="6" :lg="6">
              <div class="form-row">
                <div class="col-sm-12">
                  <v-text-field
                    v-model="details.twilio_SID"
                    required
                    outlined
                    dense
                    type="text"
                    label="Twilio SID"
                  ></v-text-field>
                </div>
              </div>
            </v-col>
            <v-col :lg="6" :md="6">
              <div class="form-row">
                <div class="col-sm-12">
                  <v-text-field
                    v-model="details.twilio_token"
                    required
                    outlined
                    dense
                    type="text"
                    label="Twilio Token"
                  ></v-text-field>
                </div>
              </div>
            </v-col>
            <v-col :lg="6" :md="6">
              <div class="form-row">
                <div class="col-sm-12">
                  <v-text-field
                    v-model="details.twilio_from"
                    required
                    outlined
                    dense
                    type="text"
                    label="Twilio From Phone Number"
                  ></v-text-field>
                </div>
              </div>
            </v-col>
            <v-col :lg="6" :md="6">
              <div class="form-row">
                <div class="col-sm-12">
                  <v-text-field
                    v-model="details.sms_apitoken"
                    required
                    outlined
                    dense
                    type="text"
                    label="BulkSMS NG API Token"
                  ></v-text-field>
                </div>
              </div>
            </v-col>
          </v-row>

          <div class="buttons text-right">
            <v-btn
              color="success"
              @click="saveSettings"
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
  </div>
</template> 

<script>
export default {
  data() {
    return {
      valid: false,
      loading: false,
      type: '',
      date_range: '',
      days: '',
      site_name: '',
      filename: '',
      settings: [],
      details: {},
      nameRules: [
        v => !!v || 'Name is required',
        v => (v && v.length <= 10) || 'Name must be less than 10 characters',
      ],
      email: '',
      emailRules: [
        v => !!v || 'E-mail is required',
        v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
      ],      
    }
  },
  methods: {  
    validate () {
      this.$refs.form.validate()
    },
    reset () {
      this.$refs.form.reset()
    },
    resetValidation () {
      this.$refs.form.resetValidation()
    },
    saveSettings() {
      this.loading = true
      this.$http.post('/admin/settings', this.details)
      .then(response => {
        this.loading = false
        if(response.data.status === true) {
          this.details = response.data.data
          this.$toast.success(response.data.data)
          window.location.reload()
        }
      });
    },
    getSettings() {
      this.$http.get('/admin/settings')
      .then(response => {
        if(response.data.status === true && response.data.data != null) {
          this.details = response.data.data
        }
        else {
          this.details = {};
        }
      });
    },
  },  
  mounted() {
    this.getSettings()
  },
}
</script>

<style lang="scss">
.col-form-label {
  text-align: left;
  //margin-top: 10px;
}
.no-padding {
  padding: 0 !important;
}

// .col-sm-4 {
//   padding: 0 !important;
//   margin-left: 8px;
// }
// .content-wrapper {
//   margin: 0 auto;
//   text-align: center;
// }
</style>