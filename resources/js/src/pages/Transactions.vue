<template>
    <div class="p-4">
      <v-card>
        <div class="card-header">
          <h5>Transactions</h5>
        </div>
        <v-card-title class="row justify-content-between p-4">
          <span>Filter by:</span>
          <button class="btn btn-primary">Donwload Transactions</button>
        </v-card-title>
        <div class="px-4 py-2">
          <v-row align="center">
            <v-text-field
              label="By Site"
              dense
              solo
              class="mx-2"
              v-model="search.site"
            ></v-text-field>
            
            <v-text-field
              label="Customer Name"
              dense
              solo
              class="mx-2"
              v-model="search.customer"
            ></v-text-field>  

            <v-text-field
              label="Phone Number"
              dense
              solo
              class="mx-2"
              v-model="search.phone_number"
            ></v-text-field>

            <v-text-field
              label="Type"
              dense
              solo
              class="mx-2"
              v-model="search.type"
            ></v-text-field>  

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
          :items="transactions"
        ></v-data-table>
      </v-card>
    </div>
</template>
<script>
  export default {
    data () {
      return {
        search: {},
        headers: [
          {
            text: 'Timestamp',
            align: 'start',
            filterable: false,
            value: 'formatted_created_at',
          },
          {
            text: 'Site',
            align: 'start',
            filterable: false,
            value: 'site',
          },
          { text: 'Customer', value: 'customer', align: 'center' },
          { text: 'Amount', value: 'amount', align: 'center' },
          { text: 'Type', value: 'type', align: 'center' },
          { text: 'Reference', value: 'reference', align: 'center' },
          { text: 'Action', value: 'intID', align: 'center' },
        ],
        transactions: [],
      }
    },
    methods: {
      getTransaction() {
        this.$http.get('/admin/settings/transactions')
        .then(response => {
          console.log(`response`, response)
          this.transactions = response.data.data
        })
      },
    },
    mounted() {
      this.getTransaction()
    },
  }
</script>