<template>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand ps-2" href="#">Autosoftware </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="#">
            <router-link class="router-link" to="/app/">List</router-link>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">
            <router-link class="router-link" to="/app/create">Create</router-link>
          </a>
        </li>

      </ul>
    </div>
  </nav>

  <router-view></router-view>
</template>

<script>

export default {};
</script>


<style scoped>
.router-link {
  color: black;
}
</style>