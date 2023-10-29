<template>
  <div class="p-4 content-wrapper">
    <v-card>
      <div class="card-header"><h5>My Account</h5></div>
      <div class=" p-4 ">
        <v-form >
          <v-row align="center">
            <v-col
              :md="6"
              :lg="6"
            >
              <div class="form-row">
                <label for="type" class="col-sm-2 col-form-label" >First Name:</label>
                <div class="col-sm-8">
                  <v-text-field
                    v-model="user.first_name"
                    required
                    solo
                    dense
                    label="First Name"
                  ></v-text-field>
                </div>
              </div>
              <div class="form-row">
                <label for="type" class="col-sm-2 col-form-label">Last Name:</label>
                <div class="col-sm-8">
                  <v-text-field
                    v-model="user.last_name"
                    required
                    solo
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
                <label for="type" class="col-sm-2 col-form-label">Username:</label>
                <div class="col-sm-8">
                  <v-text-field
                    v-model="user.username"
                    required
                    solo
                    dense
                    label="Username"
                  ></v-text-field>
                </div>
              </div>
              <div class="form-row">
                <label for="date" class="col-sm-2 col-form-label">Email:</label>
                <div class="col-sm-8">
                  <v-text-field
                    v-model="user.email"
                    required
                    solo
                    dense
                    label="Email"
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
              @click="updateDetails"
            >
              Save
                <v-progress-circular
                  width="3"
                  size="20"
                  color="white"
                  indeterminate
                  class="ml-2"
                  v-show="spinner === true"
                >                
                </v-progress-circular>                
            </v-btn>
          </div>
        </v-form>
      </div>
    </v-card>

    <v-card class="mt-4">
      <v-card-title><h5>Change Password</h5></v-card-title>
      <div class=" p-4 ">
        <v-form >
          <v-row align="center">
            <v-col
              :md="6"
              :lg="6"
            >
              <div class="form-row">
                <label for="type" class="col-sm-3 col-form-label" >Old Password:</label>
                <div class="col-sm-7">
                  <v-text-field
                    v-model="details.old_password"
                    required
                    solo
                    dense
                    label="Old Password"
                  ></v-text-field>
                </div>
              </div>
              <div class="form-row"></div>
            </v-col>

            <v-col
              :md="6"
              :lg="6"
            >
              <div class="form-row">
                <label for="type" class="col-sm-3 col-form-label">New Password:</label>
                <div class="col-sm-7">
                  <v-text-field
                    v-model="details.password"
                    required
                    solo
                    dense
                    label="New Password"
                  ></v-text-field>
                </div>
              </div>
              <div class="form-row">
                <label for="date" class="col-sm-3 col-form-label">Confirm Password:</label>
                <div class="col-sm-7">
                  <v-text-field
                    v-model="details.confirm_password"
                    required
                    solo
                    dense
                    label="Confirm Password"
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
              @click="changePassword"
            >
              Change Password
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

    <!-- <v-card class="mt-4">
      <v-card-title><h5>Preferences</h5></v-card-title>
      <div class=" p-4 ">
        <v-form >
          <v-row align="center">
            <v-col
              :md="6"
              :lg="6"
            >
              <div class="form-row">
                <label for="type" class="col-sm-3 col-form-label" >Default Currency:</label>
                <div class="col-sm-7">
                  <v-select
                    v-model="currency"
                    required
                    solo
                    dense
                    label="Default Currency"
                  ></v-select>
                </div>
              </div>
              <div class="form-row p-4"></div>
            </v-col>

            <v-col
              :md="6"
              :lg="6"
            >
              <div class="form-row">
                <label for="type" class="col-sm-3 col-form-label">Default Timezone:</label>
                <div class="col-sm-7">
                  <v-select
                    v-model="timezone"
                    required
                    solo
                    dense
                    label="Default Timezone"
                  ></v-select>
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
              @click="resetValidation"
            >
              Save
            </v-btn>
          </div>
        </v-form>
      </div>
    </v-card> -->
  </div>
</template> 

<script>
export default {
  data() {
    return {
      user: {},
      details: {},
      valid: false,
      first_name: '',
      date_range: '',
      username: '',
      last_name: '',
      confirm_password: '',
      password: '',
      old_password: '',
      currency: '',
      timezone: '',
      loading: false,
      spinner: false,
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
    updateDetails() {
      this.spinner = true
      this.$http.put(`/admin/profile/${this.user.id}`, this.user)
      .then(response => {
        this.spinner = false
        if(response.data.status) {
          this.user = response.data.data
        }
      })
    },
    changePassword() {
      this.loading = true
      this.$http.post(`/admin/auth/change-password}`, this.details)
      .then(response => {
        this.loading = false
        if(response.data.status) {
          this.user = response.data.data
        }
      })
    },
    getUser() {
      let user = localStorage.getItem('user')
      if(user != undefined || user != null) {
        this.user = user
        this.details.user_id = user.id
      }
    }
  }, 
  mounted() {
    this.getUser()
  }, 
}
</script>

<style lang="scss">
.col-form-label {
  text-align: right;
   //margin-top: 10px;
}
.no-padding {
  padding: 0 !important;
}

.col-sm-8, .col-sm-7 {
  padding: 0 !important;
  margin-left: 8px;
}
// .content-wrapper {
//   margin: 0 auto;
//   text-align: center;
// }
</style>