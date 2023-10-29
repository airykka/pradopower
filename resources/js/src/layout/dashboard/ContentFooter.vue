<template>
  <footer class="footer">
    <div class="container-fluid d-flex flex-wrap justify-content-between">
      <nav>
        <ul>
          <li>
            <router-link :to="{path:'/admin'}">Dashboard</router-link>
          </li>
        </ul>
      </nav>
      <div class="copyright d-flex flex-wrap">
        &copy;Prado Energy Management System (PEMS) 2021
      </div>
    </div>
  </footer>
</template>
<script>
export default {};
</script>
<style>
</style>
