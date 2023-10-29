<template>
  <div class="p-4">
    <v-card>
      <div class="card-header"><h5>Alerts</h5></div>
      <v-card-title>
        <span>Filter by:</span>
      </v-card-title>
      <div class="px-4 py-2">
        <v-row align="center">
          <v-text-field
            label="Alert Description"
            dense
            solo
            class="mx-2"
            v-model="search.message"
          ></v-text-field>
          
          <v-select
            :items="status"
            label="Status"
            dense
            solo
            class="mx-2"
            v-model="search.status"
          ></v-select>  

          <v-text-field
            label="Date"
            dense
            solo
            class="mx-2"
            v-model="search.date"
          ></v-text-field>
        </v-row>         
      </div>
      <v-data-table
        :headers="headers"
        :items="messages"
      ></v-data-table>
    </v-card>
  </div>
</template>
<script>
  export default {
    data () {
      return {
        search: {},
        status: ['Sent', 'Received', 'Pending', 'Failed'],        
        headers: [
          {
            text: 'Alert Description',
            align: 'start',
            filterable: false,
            value: 'description',
          },
          {
            text: 'Priority',
            align: 'start',
            filterable: false,
            value: 'priority',
          },
          { text: 'Created', value: 'created' },
          { text: 'Time to Resolution', value: 'time' },
          { text: 'Site', value: 'site' },
          { text: 'Resolved', value: 'resolved' },
        ],
        messages: [],
      }
    },
    computed: {
      filteredMessages() {
        return this.messages.filter(message => {
          message.created_at.toLowerCase() === this.search.date.toLowerCase() 
          || message.status_note.toLowerCase() === this.search.status.toLowerCase() 
          || message.message.toLowerCase() === this.search.message.toLowerCase()
        })
      }
    },
    methods: {
      getMessages() {
        this.$http.get('/admin/messages')
        .then(response => {
          this.messages = response.data.data
        })
      }
    },
    mounted() {
      this.getMessages()
    },     
  }
</script>