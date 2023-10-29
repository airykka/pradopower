<template>
  <div>

    <!--Stats cards-->
    <div class="row p-3">
      <div class=" card-items" v-for="stats in statsCards" :key="stats.title">
        <stats-card>
          <div class="icon-big text-center" :class="`icon-${stats.type}`" slot="header">
            <i :class="stats.icon"></i>
          </div> 
          <div class="numbers" slot="content">
            <p>{{stats.title}}</p>
            {{stats.value}}
          </div>
          <div class="stats" slot="footer">
            <i :class="stats.footerIcon"></i> {{stats.footerText}}
          </div>
        </stats-card>
      </div>
    </div>

    <!--Charts-->
    <div class="row">

      <div class="col-md-4 col-lg-4">
        <div class="card">
          <h4 class="card-header no-margin">Site Performance Filter</h4>
          <div class="card-body">
            <v-form>
              <v-row>
                <v-col :md="12" :lg="12">
                  <div class="form-row">
                    <div class="col-sm-12">
                      <v-select
                        v-model="details.site"
                        required
                        outlined
                        label="Site"
                        dense
                        :items="sites"
                        @change="updateChart"
                      ></v-select>
                    </div>                    
                  </div>
                </v-col>
              </v-row>
              <v-row>
                <v-col :md="12" :lg="12">
                  <div class="form-row">
                    <div class="col-sm-12">
                      <v-select
                        v-model="details.type"
                        required
                        outlined
                        label="By"
                        dense
                        :items="items"
                        @change="updateChart"
                      ></v-select>
                    </div>                    
                  </div>
                </v-col>
              </v-row>
              <v-row>
                <v-col :md="12" :lg="12">
                  <div class="form-row">
                    <div class="col-sm-12">
                      <v-select
                        v-model="details.period"
                        required
                        outlined
                        label="Select Period"
                        dense
                        :items="periods"
                      ></v-select>
                    </div>                    
                  </div>
                </v-col>
              </v-row>
            </v-form>
          </div>
        </div>
      </div>

      <div class="col-md-8 col-lg-8">
        <chart-card title="Site Performance"
          :sub-title="`By ${details.type}`"
          :chart-data=" dataResults.data"
          :chart-options="dataResults.options"
          :key="details.type === 'Electricity' ? 1 : details.type === 'Revenue' ? 2 : 3"
        >
          <span slot="footer">
            <i class="ti-reload"></i> Updated 3 minutes ago
          </span>
        </chart-card>
      </div>

      <!-- <div class="col-md-6 col-12">
        <chart-card title="Meter Statistics"
                    sub-title="Last campaign performance"
                    :chart-data="preferencesChart.data"
                    chart-type="Pie">
          <span slot="footer">
            <i class="ti-timer"></i> Days ago</span>
          <div slot="legend">
            <i class="fa fa-circle text-info"></i> Online
            <i class="fa fa-circle text-danger"></i> Offline
            <i class="fa fa-circle text-warning"></i> Unsubscribe
          </div>
        </chart-card>
      </div> -->

      <!-- <div class="col-md-6 col-12">
        <chart-card title="March Revenue"
            sub-title="All products Services"
            :chart-data="activityChart.data"
            :chart-options="activityChart.options">
          <span slot="footer">
            <i class="ti-check"></i> Data information certified
          </span>
          <div slot="legend">
            <i class="fa fa-circle text-info"></i> Meters
            <i class="fa fa-circle text-warning"></i> Cores
          </div>
        </chart-card>
      </div> -->

    </div>

  </div>
</template>
<script>
import { StatsCard, ChartCard } from "../components/index";
import Chartist from 'chartist';
export default {
  components: {
    StatsCard,
    ChartCard
  },
  computed: {
    dataResults() {
      if(this.details.type === 'Electricity') {
        return {
          data: this.sitesElectricity.data,
          options: this.sitesElectricity.options
        }
      }
      else {
        return {
          data: this.sitesChart.data,
          options: this.sitesChart.options
        }
      }
      
    }
  },
  data() {
    return {
      loading: false,
      chartKey: 1,
      dashboardData: [],
      details: {
        type: '',
        site: '',
      },
      sites: [],
      items: ['Revenue','Electricity'],
      periods: ['7 days','1 month', '1 year' ],
      graphData: '',
      statsCards: [
        {
          type: "warning",
          icon: "ti-server",
          title: "Sites",
          value: "0",
          footerText: "Updated now",
          footerIcon: "ti-reload"
        },
        {
          type: "success",
          icon: "ti-harddrive",
          title: "Meters Online",
          value: "0",
          footerText: "Updated now",
          footerIcon: "ti-reload"
        },
        {
          type: "danger",
          icon: "ti-harddrives",
          title: "Meters Offline",
          value: "0",
          footerText: "Updated now",
          footerIcon: "ti-timer"
        },
        {
          type: "warning",
          icon: "ti-bolt",
          title: "KWh",
          value: "0",
          footerText: "Last updated yesterday",
          footerIcon: "ti-timer"
        },
        {
          type: "info",
          icon: "ti-wallet",
          title: "Revenue",
          value: "0",
          footerText: "Last updated yesterday",
          footerIcon: "ti-reload"
        }
      ],
      sitesChart: {
        data: {
          labels: [
            "2021-04-01",
            "2021-04-02",
            "2021-04-03",
            "2021-04-04",
            "2021-04-05",
            "2021-04-06",
            "2021-04-07",
            "2021-04-08"
          ],
          series: [
            [5, 30, 10, 17, 39, 26, 15, 3, 22],
            [10, 40, 17, 27, 5, 38, 19, 45, 12],
          ]
        },
        options: {
          low: 0,
          high: 45,
          showArea: false,
          height: "300px",
          axisX: {
            showGrid: false
          },
          lineSmooth: Chartist.Interpolation.simple({
            divisor: 2
          }),
          showLine: true,
          showPoint: true
        }
      },
      sitesElectricity: {
        data: {
          labels: [
            "2021-04-01",
            "2021-04-02",
            "2021-04-03",
            "2021-04-04",
            "2021-04-05",
            "2021-04-06",
            "2021-04-07",
            "2021-04-08"
          ],
          series: [
            [7, 10, 32, 13, 28, 45, 21, 30, 19],
            [10, 40, 17, 27, 5, 38, 19, 45, 12],
          ]
        },
       options: {
          low: 0,
          high: 45,
          showArea: false,
          height: "300px",
          axisX: {
            showGrid: false
          },
          lineSmooth: Chartist.Interpolation.simple({
            divisor: 2
          }),
          showLine: true,
          showPoint: true
        }
      },
      activityChart: {
        data: {
          labels: [
            "Jan",
            "Feb",
            "Mar",
            "Apr",
            "Mai",
            "Jun",
            "Jul",
            "Aug",
            "Sep",
            "Oct",
            "Nov",
            "Dec"
          ],
          series: [
            [542, 543, 520, 680, 653, 753, 326, 434, 568, 610, 756, 895],
            [230, 293, 380, 480, 503, 553, 600, 664, 698, 710, 736, 795]
          ]
        },
        options: {
          seriesBarDistance: 10,
          axisX: {
            showGrid: false
          },
          height: "245px"
        }
      },
      preferencesChart: {
        data: {
          labels: ["62%", "32%", "6%"],
          series: [62, 32, 6]
        },
        options: {}
      }
    };
  },
  methods: {
    updateChart() {
      if(this.details.type === 'Electricity') {
        this.chartKey = 2
      }
      else if(this.details.type === 'Revenue'){
        this.chartKey = 3
      }
      else {
        this.chartKey = 1
      }
      
    },    

    getData() {
      this.$http.get('/admin/settings/analytic')
      .then(response => {
        console.log(`response`, response)
        if(response.data.status === true) {
          this.dashboardData = response.data.data
          this.sites = this.dashboardData.sites.map(site => {
            return site.name
          })
          this.statsCards.filter(stat => stat.title === 'Sites')[0].value =  this.sites.length
          this.statsCards.filter(stat => stat.title.toLowerCase() === 'meters online')[0].value =  this.dashboardData.meters.filter(meter => meter.status == 1).length
          this.statsCards.filter(stat => stat.title.toLowerCase() === 'meters offline')[0].value =  this.dashboardData.meters.filter(meter => meter.status == 0).length
          this.statsCards.filter(stat => stat.title.toLowerCase() === 'kwh')[0].value =  this.dashboardData.kwh
          this.statsCards.filter(stat => stat.title.toLowerCase() === 'revenue')[0].value =  this.dashboardData.revenue
        }
      })
    },     

  },
  mounted() {
    this.graphData = this.sitesChart
    this.getData()
  },
};
</script>

<style lang="scss">
  .v-messages, .v-text-field__details {
    display: none !important;
  }
</style>
