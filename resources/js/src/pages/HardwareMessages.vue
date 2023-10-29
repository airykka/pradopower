<template>
    <div class="p-4">
      <v-card>
        <div class="card-header row justify-content-between">
          <h5>Hardware Messages</h5>
          <span class="btn-group">
            <button class="btn btn-primary">Download</button>
            <button class="btn btn-primary">Send Message</button>
          </span>
        </div>
        <v-card-title>
          <span>Filter by:</span>
        </v-card-title>
        <div class="px-4 py-2">
          <v-row align="center">
            <v-text-field
              label="By Core"
              dense
              solo
              class="mx-2"
              v-model="search.core"
            ></v-text-field>
            
            <v-text-field
              label="Message"
              dense
              solo
              class="mx-2"
              v-model="search.message"
            ></v-text-field>

            <v-select
              :items="status"
              label="By Type"
              dense
              solo
              class="mx-2"
              v-model="search.type"
            ></v-select>  

            <v-text-field
              label="Date"
              dense
              solo
              class="mx-2"
              v-model="search.created_at"
            ></v-text-field>  
          </v-row>       
        </div>
        <v-data-table
          dense
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
        search: {
          type: '',
          message: '',
          core: ''
        },
        status: ['Sent','Received','Pending'],
        headers: [
          {
            text: 'Timestamp',
            align: 'start',
            filterable: false,
            value: 'created_at',
          },
          { text: 'hardware', value: 'hardware', align: 'left' },
          { text: 'Message', value: 'message', align: 'left' },
          { text: 'Type', value: 'type', align: 'center' },
        ],
        messages: [],
      }
    },
    computed: {
      filteredMessages() {
        return this.messages.filter(message => {
          message.created_at.toLowerCase() === this.search.date.toLowerCase() 
          || message.type.toLowerCase() === this.search.type.toLowerCase() 
          || message.message.toLowerCase() === this.search.message.toLowerCase()
          || message.meter.toLowerCase() === this.search.core.toLowerCase()
        })
      }
    },
    methods: {
      getMessages() {
        this.$http.get('/admin/messages')
        .then(response => {
          this.messages = response.data.data.filter(message => message.type.toLowerCase() === 'customer')
        })
      }
    },
    mounted() {
      this.getMessages()
    },    
  }
</script>