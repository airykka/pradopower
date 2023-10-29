<template>
  <div class="p-4 content-wrapper">
    <v-card>
      <div class="card-header"><h5>Reports</h5></div>
      <div class=" p-4 ">
        <v-form  ref="form">
          <div class="form-row">
            <label for="type" class="col-sm-2 col-form-label" >Report Type:</label>
            <div class="col-sm-4">
              <v-select
                v-model="type" 
                required
                outlined
                dense
                :items="types"
                label="Report Type"
              ></v-select>
            </div>
          </div>
          <div class="form-row">
            <label for="type" class="col-sm-2 col-form-label">Site Name:</label>
            <div class="col-sm-4">
              <v-select
                v-model="site_name"
                required
                outlined
                :items="sites"
                dense
                label="Site Name"
              ></v-select>
            </div>
          </div>
          <div class="form-row">
            <label for="type" class="col-sm-2 col-form-label">Days:</label>
            <div class="col-sm-4">
              <v-text-field
                v-model="days"
                required
                outlined
                dense
                label="Days"
              ></v-text-field>
            </div>
          </div>
          <div class="form-row">
            <label for="date" class="col-sm-2 col-form-label">Date Range (UTC):</label>
            <div class="col-sm-4">
              <v-text-field
                v-model="days"
                required
                outlined
                dense
                label="Date Range"
                type="date"
              ></v-text-field>
            </div>
          </div>
          <div class="form-row">
            <label for="date" class="col-sm-2 col-form-label">File Name:</label>
            <div class="col-sm-4">
              <v-text-field
                v-model="filename"
                required
                outlined
                dense
                label="Filename"
              ></v-text-field>
            </div>
          </div>

          <div class="buttons text-center">
            <v-btn
              color="error"
              class="mr-4"
              @click="reset"
            >
              Reset
            </v-btn>
            <v-btn
              color="success"
              @click="resetValidation"
            >
              Download
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
      type: '',
      date_range: '',
      days: '',
      site_name: '',
      filename: '',
      sites: [],
      cores: [],
      types: ['Electricity', 'Revenue'],
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
    getSites() {
      this.$http.get('/api/v1/sites')
      .then(response => {
        if(response.data.status === true) {
          this.cores = response.data.data
          this.sites = this.cores.map(site => 
            {
             return  site.name
            }
          )
        }
      })
    },
  }, 
  mounted() {
    this.getSites()
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

.col-sm-4 {
  padding: 0 !important;
  margin-left: 8px;
}
// .content-wrapper {
//   margin: 0 auto;
//   text-align: center;
// }
</style>