<template>
  <div id="app">
    <navbar-component> </navbar-component>

    <router-view>
      
    </router-view>
  </div>
</template>

<script>
import NavbarComponent from "./components/NavbarComponent.vue";


export default {
  name: 'App',
  components: { 
    NavbarComponent,
  
  },
  data() {
    return {
      currentTheme: 'light'
    };
  },
};
</script>

<style>

.theme-toggle-button {
  z-index: 999;
  position: fixed;
  right: 30px;
  top: 10px;
  width: 40px;
  height: 40px;
  border-radius: 50%;
}
.theme-toggle-button .b-icon.bi {
    width: 20px;
    height: 20px;
}
</style>
