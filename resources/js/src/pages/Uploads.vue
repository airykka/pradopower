<template>
  <!-- <v-app> -->
    <div class="p-4">
      <v-card>
        <div class="card-header"><h5>Data Uploads</h5></div>
        <v-card-title>
          <span>Filter by:</span>
        </v-card-title>
        <v-data-table
          :headers="headers"
          :items="desserts"
          :search="search"
        ></v-data-table>
      </v-card>
    </div>
  <!-- </v-app> -->
</template>
<script>
  export default {
    data () {
      return {
        search: '',
        headers: [
          {
            text: 'Date',
            align: 'start',
            filterable: false,
            value: 'date',
          },
          { text: 'Name', value: 'name' },
          { text: 'Progress', value: 'progress' },
          { text: 'Status', value: 'status' },
          { text: 'Logs', value: 'logs' },
        ],
        desserts: [
          {
            date: '20th March, 2021',
            name: 'Meter list',
            progress: '100%',
            status: 'True',
            logs: 'NULL'
          },
          {
            date: '20th March, 2021',
            name: 'Agents',
            progress: '100%',
            status: 'False',
            logs: 'NULL'
          },
        ],
      }
    },
  }
</script>