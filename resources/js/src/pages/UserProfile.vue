<template>
    <div class="row">
      <div class="col-12">
        <card :title="table1.title" :subTitle="table1.subTitle">
          <div slot="raw-content" class="table-responsive">
            <paper-table :data="table1.data" :columns="table1.columns">

            </paper-table>
          </div>
        </card>
      </div>
    </div>
</template>
<script>
import { PaperTable } from "../components";
const tableColumns = ["Id", "Core", "Utility", "Ref", "Customer", "Status"];
const tableData = [];

export default {
  components: {
    PaperTable
  },
  data() {
    return {
      table1: {
        title: "Clusters",
        subTitle: "Clusters",
        columns: [...tableColumns],
        data: [...tableData]
      },
      table2: {
        title: "Cluster",
        subTitle: "Clusters",
        columns: [...tableColumns],
        data: [...tableData]
      }
    };
  }
};
</script>
<style>
</style>
