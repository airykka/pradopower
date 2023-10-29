<template>
    <div class="p-4">
      <v-card class="mb-4 mt-4" v-show="showForm === true">
        <div class="card-header">
          <h5>New User</h5>
        </div>
        <div class=" p-4 ">
          <v-form  ref="form">

            <v-row align="center">
              <v-col :md="6" :lg="6" >
                <v-text-field
                  v-model="details.first_name"
                  required
                  outlined
                  dense
                  label="First Name"
                ></v-text-field>
              </v-col>

              <v-col :md="6" :lg="6">
                <v-text-field
                  v-model="details.last_name"
                  required
                  outlined
                  dense
                  label="Last Name"
                ></v-text-field>
              </v-col>

            </v-row>

            <v-row>
              <v-col :md="6" :lg="6">
                <v-text-field
                  v-model="details.phone_number"
                  required
                  outlined
                  dense
                  type="tel"
                  label="Telephone Number"
                ></v-text-field>
              </v-col>

              <v-col :md="6" :lg="6">
                <v-text-field
                  v-model="details.email"
                  required
                  outlined
                  dense
                  label="Email"
                  type="email"
                ></v-text-field>
              </v-col>
            </v-row>

            <v-row>
              <v-col :md="6" :lg="6">
                <v-text-field
                  v-model="details.username"
                  required
                  outlined
                  dense
                  label="User Name"
                ></v-text-field>
              </v-col>              
              <v-col :md="6" :lg="6">
                <v-select
                  :items="roles"
                  v-model="details.user_role"
                  required
                  outlined
                  dense
                  label="User Role"
                ></v-select>
              </v-col>              
            </v-row>

            <v-row>
              <v-col :md="6" :lg="6">
                <v-text-field
                  v-model="details.password"
                  required
                  outlined
                  dense
                  type="password"
                  label="Password"
                ></v-text-field>
              </v-col>

              <v-col :md="6" :lg="6">
                <v-text-field
                  v-model="details.password_confirmation"
                  required
                  outlined
                  dense
                  label="Confirm Password"
                  type="password"
                ></v-text-field>
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
                @click="createUser"
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
        <div class="card-header"><h5>PEMS Admin Users</h5></div>
        <v-card-title class="d-flex justify-content-between">
          <span>Filter by:</span>
          <v-btn
            color="info"
            @click="toggleForm"
          >
           <i class="ti-plus"></i> Add User 
          </v-btn>
        </v-card-title>
        <div class="px-4 py-2">
          <v-row align="center">
            <v-text-field
              label="Username"
              dense
              solo
              class="mx-2"
            ></v-text-field>
          </v-row>        
        </div>
        <v-data-table
          :headers="headers"
          :items="users"
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
        details: {},
        showForm: false,
        loading: false,
        roles: ['User','Support'],
        headers: [
          {
            text: 'Username',
            align: 'start',
            filterable: false,
            value: 'username',
          },
          { text: 'Name', value: 'name' },
          { text: 'Email', value: 'email' },
          { text: 'Is Active?', value: 'isActive' },
        ],
        users: [
          {
            username: 'timefoe',
            name: 'Tim Uzua',
            email: 'tchosen@pradopowermgt.com',
            isActive: 'True',
          },
          {
            username: 'peterdrews',
            name: 'Peter Andrew',
            email: 'peterdrews@proadopowermgt.com',
            isActive: 'False',
          },
        ],
      }
    },
    methods: {
      notifyVue(type, message, icon, horizontalAlign, verticalAlign) {
        this.$notify({
          message: message,
          icon: icon,
          horizontalAlign: horizontalAlign,
          verticalAlign: verticalAlign,
          type: type
        });
      },       
      toggleForm() {
        this.showForm = !this.showForm
      },
      reset () {
        this.$refs.form.reset()
      },      
      createUser() {
        this.loading = true
        this.$http.post('/admin/profile', this.details)
        .then(response => {
          this.loading = false
          console.log(`response`, response)
          if(response.data.status) {
            this.notifyVue('success', response.data.message, 'ti-check', 'right', 'top')
            this.users .push(response.data.data)
            this.reset()
          }
          else {
            this.notifyVue('danger', response.data.message, 'ti-close', 'right', 'top')
          }
        })
      },
      getUsers() {
        this.$http.get('/admin/profile')
        .then(response => {
          if(response.data.status) {
            this.users = response.data.data
          }
        })
      },
    },
    mounted() {
      this.getUsers()
    },
  }
</script>