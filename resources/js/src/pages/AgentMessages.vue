<template>
    <div class="p-4">
      <v-card>
        <div class="card-header"><h5>Agent Messages</h5></div>
        <v-card-title>
          <span>Filter by:</span>
        </v-card-title>
        <div class="px-4 py-2">
          <v-row align="center">
            <v-text-field
              label="Agent Name"
              dense
              solo
              class="mx-2"
              v-model="search.agent"
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
              v-model="search.created_at"
              dense
              solo
              class="mx-2"
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
        searchString: '',
        search: {
          agent: '',
          message: '',
          type: '',
          date: ''
        },
        status: ['Sent', 'Received', 'Pending'],
        headers: [
          {
            text: 'Timestamp',
            align: 'start',
            filterable: false,
            value: 'created_at',
          },

          { text: 'Agent', value: 'agent', align: 'left' },
          { text: 'Message', value: 'message', align: 'left' },
          { text: 'Type', value: 'type', align: 'center' },
        ],
        messages: [
          {
            created_at: 'March 20, 2021 12:12 AM',
            agent: 'Tim Uzua',
            message: 'Your account has been updated with -1000.00 NgN. Your new balance is 29000.00 NgN.',
            type: 'sent',
          },
          {
            created_at: 'March 20, 2021 12:15 AM',
            agent: 'Peter Andrew',
            message: 'Your account has been updated with -1000.00 NgN. Your new balance is 29000.00 NgN.',
            type: 'sent',
          },
        ],
        //messages: [],
      }
    },
    computed: {
      filteredMessages() {
        return this.messages.filter(message => {
          message.created_at.toLowerCase() === this.search.date.toLowerCase() 
          || message.type.toLowerCase() === this.search.type.toLowerCase() 
          || message.message.toLowerCase() === this.search.message.toLowerCase()
          || message.agent.toLowerCase() === this.search.agent.toLowerCase()
        })
      }
    },
    methods: {
      getMessages() {
        this.$http.get('/admin/messages')
        .then(response => {
          console.log('response', response)
          this.messages = response.data.data.filter(message => message.type.toLowerCase() === 'agent')
        })
      }
    },
    mounted() {
      this.getMessages()
    },
  }
</script>