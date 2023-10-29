<template>
  <v-row class="mt-5 mb-5">
    <v-col md="12" lg="12">
      <v-data-table
        :headers="headers"
        :items="siteData"
        sort-by="name"
        class="elevation-1"
      >
        <template v-slot:top>
          <v-toolbar
            flat
          >
            <v-toolbar-title>Sites</v-toolbar-title>
            <v-divider
              class="mx-4"
              inset
              vertical
            ></v-divider>
            <v-spacer></v-spacer>
            <v-dialog
              v-model="dialog"
              max-width="500px"
            >
              <template v-slot:activator="{ on, attrs }">
                <v-btn
                  color="primary"
                  dark
                  class="mb-2"
                  v-bind="attrs"
                  v-on="on"
                >
                  New Item
                </v-btn>
              </template>
              <v-card>
                <v-card-title>
                  <span class="headline">{{ formTitle }}</span>
                </v-card-title>

                <v-card-text>
                  <v-container>
                    <v-row>
                      <v-col
                        cols="12"
                        sm="6"
                        md="4"
                      >
                        <v-text-field
                          v-model="editedItem.name"
                          label="Site Name"
                        ></v-text-field>
                      </v-col>
                      <v-col
                        cols="12"
                        sm="6"
                        md="4"
                      >
                        <v-text-field
                          v-model="editedItem.currency"
                          label="NGN"
                        ></v-text-field>
                      </v-col>
                      <v-col
                        cols="12"
                        sm="6"
                        md="4"
                      >
                        <v-text-field
                          v-model="editedItem.phone_number"
                          label="080123456789"
                        ></v-text-field>
                      </v-col>
                      <v-col
                        cols="12"
                        sm="6"
                        md="4"
                      >
                      </v-col>
                    </v-row>
                  </v-container>
                </v-card-text>

                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn
                    color="blue darken-1"
                    text
                    @click="close"
                  >
                    Cancel
                  </v-btn>
                  <v-btn
                    color="blue darken-1"
                    text
                    @click="save"
                  >
                    Save 
                    <v-progress-circular
                      width="3"
                      size="20"
                      color="primary"
                      indeterminate
                      class="ml-2"
                      v-show="loading === true"
                    >                
                    </v-progress-circular>                    
                  </v-btn>
                </v-card-actions>
              </v-card>
            </v-dialog>
            <v-dialog v-model="dialogDelete" max-width="500px">
              <v-card>
                <v-card-title class="headline">Are you sure you want to delete this item?</v-card-title>
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn color="blue darken-1" text @click="closeDelete">Cancel</v-btn>
                  <v-btn color="blue darken-1" text @click="deleteItemConfirm">OK</v-btn>
                  <v-spacer></v-spacer>
                </v-card-actions>
              </v-card>
            </v-dialog>
          </v-toolbar>
          <v-col md="6" lg="6">
            <v-text-field
              v-model="search"
              append-icon="mdi-magnify"
              label="Search"
              single-line
              hide-details
            ></v-text-field>           
          </v-col>         
        </template>
        <template v-slot:item.actions="{ item }">
          <v-icon
            small
            class="mr-2"
            @click="editItem(item)"
          >
            mdi-pencil
          </v-icon>
          <v-icon
            small
            @click="deleteItem(item)"
          >
            mdi-delete
          </v-icon>
        </template>
      </v-data-table>
    </v-col>
  </v-row>
</template>

<script>
  export default {
    data: () => ({
      loading: false,
      showForm: false,
      details: {},
      sites: [],
      search: '',
      dialog: false,
      dialogDelete: false,
      headers: [
        {
          text: 'Site',
          align: 'start',
          filterable: false,
          value: 'name',
        },
        { text: 'Meter Count', value: 'Meterno', align: 'center' },
        { text: 'Currency', value: 'currency', align: 'center' },
        { text: '30-Days Revenue', value: 'revenue', align: 'center' },
        { text: '30-Days Utility use (kWh)', value: 'utility', align: 'center' },
        { text: 'Actions', value: 'actions', sortable: false },
      ],      
      sites: [],
      editedIndex: -1,
      editedItem: {
        name: '',
        currency: '',
        phone_number: '',
      },
      defaultItem: {
        name: '',
        currency: '',
        phone_number: '',
      },
    }),

    computed: {
      formTitle () {
        return this.editedIndex === -1 ? 'New Site' : 'Edit Site'
      },
      siteData() {
        return this.sites.filter(site => site.name.toLowerCase().includes(this.search))
      }
    },

    watch: {
      dialog (val) {
        val || this.close()
      },
      dialogDelete (val) {
        val || this.closeDelete()
      },
    },

    created () {
      this.getSites()
    },

    methods: {      
      editItem (item) {
        this.editedIndex = this.sites.indexOf(item)
        this.editedItem = Object.assign({}, item)
        this.details = this.editedItem
        this.dialog = true
      },

      deleteItem (item) {
        this.editedIndex = this.sites.indexOf(item)
        this.editedItem = Object.assign({}, item)
        this.details = this.editedItem
        this.dialogDelete = true
      },

      deleteItemConfirm () {
        this.sites.splice(this.editedIndex, 1)
        this.deleteSite()
        this.closeDelete()
      },

      close () {
        this.dialog = false
        this.$nextTick(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
          this.editedIndex = -1
        })
      },

      closeDelete () {
        this.dialogDelete = false
        this.$nextTick(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
          this.editedIndex = -1
        })
      },

      save () {
        if (this.editedIndex > -1) {
          Object.assign(this.sites[this.editedIndex], this.editedItem)
          this.editSite()
        } else {
          // this.sites.push(this.editedItem)
          this.createSite()
        }
        // this.close()
      },
      getSites() {
        this.$http.get('/api/v1/sites')
        .then(response => {
          if(response.data.status) {
            this.sites = response.data.data
          }
        })
      }, 
      createSite() {
        console.log("details", this.editedItem)
        this.loading = true
        this.$http.post('/api/v1/sites', this.editedItem)
        .then(response => {
          if(response.data.status) {
            this.sites .push(response.data.data)
            this.$toast.success(response.data.message)
            this.getSites()
          }
        })
        .finally(() => {
          this.loading = false 
          this.close()
        })
      },             
      editSite() {
        this.loading = true
        this.$http.put(`/api/v1/sites/${this.details.id}`, this.details)
        .then(response => {
          if(response.data.status) {
            this.$toast.success(response.data.message)
            this.getSites()
          }
        })
        .finally(() => {
          this.loading = false 
          this.close()
        })
      },             
      deleteSite() {
        this.loading = true
        this.$http.delete(`/api/v1/sites/${this.details.id}`)
        .then(response => {
          if(response.data.status) {
            this.$toast.success(response.data.message)
            this.getSites()
          }
        })
        .finally(() => {
          this.loading = false 
          this.close()
        })
      },             
    },
  }
</script>