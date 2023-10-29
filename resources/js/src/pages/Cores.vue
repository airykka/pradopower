<template>
    <div class="p-4">
      <v-card>
        <div class="card-header"><h5>Cores</h5></div>
        <v-card-title>
          <span>Filter by:</span>
        </v-card-title>
        <div class="px-4 py-2">
          <v-row align="center">
            <div class="col-md-4">
              <v-text-field
                label="By Site Name"
                dense
                solo
                class="mx-2"
              ></v-text-field>
            </div>
            
            <div class="col-md-4">
              <v-select
                :items="items"
                label="By Core"
                dense
                solo
                class="mx-2"
              ></v-select>
            </div>

            <div class="col-md-4">
              <v-text-field
                label="By Reference"
                dense
                solo
                class="mx-2"
              ></v-text-field>
            </div>
          </v-row>          
          <v-row align="center">   
            <div class="col-md-4">
              <v-select
                :items="items"
                label="Version"
                dense
                solo
                class="mx-2"
              ></v-select>
            </div>

            <div class="col-md-4">
              <v-select
                :items="items"
                label="Assigned to active site?"
                dense
                solo
                class="mx-2"
              ></v-select>
            </div>

            <div class="col-md-4">
              <v-select
                :items="items"
                label="Communication indicator"
                dense
                solo
                class="mx-2"
              ></v-select>
            </div>
          </v-row>        
        </div>
        <v-data-table
          
          :headers="headers"
          :items="cores"
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
        cores: [],
        sites: [],
        items: [],        
        headers: [
          {
            text: 'Name',
            align: 'start',
            filterable: false,
            value: 'name',
          },
          {
            text: 'Site',
            align: 'start',
            filterable: false,
            value: 'site',
          },
          { text: 'Meters', value: 'meters', align: 'center' },
          { text: 'Telephone', value: 'telephone', align: 'center' },
          { text: 'Serial Number', value: 'serialNo', align: 'center' },
          { text: 'Version', value: 'version', align: 'center' },
          { text: 'Logging Interval (minutes)', value: 'loggingInt' },
          { text: 'Approval Date', value: 'approval' },
          { text: 'Uptime Last 7-Days', value: 'uptime' },
        ],
      }
    },
    methods: {
      getSites() {
        this.$http.get('/api/v1/sites')
        .then(response => {
          console.log(`response`, response)
          if(response.data.status === true) {
            this.sites = response.data.data
            this.cores = this.sites.map(core => {
              return {
                name: core.name,
                site: core.name,
                meters: core.MeterNo,
                telephone: core.phone_number,
                serialNo: core.id,
                version: core.version,
                loggingInt: '1%',
                approval: core.created_at,
                uptime: '100%'
              }
            })
          }
        })
      },
    },
    mounted() {
      this.getSites()
    },
  }
</script>