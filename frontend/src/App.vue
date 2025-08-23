<template>
  <div id="app">
    <button @click="toggleTheme" class="theme-toggle-button">

      <i class="theme-icon"
        :class="currentTheme === 'light' ? ['fas fa-moon', 'icon-light'] : ['far fa-sun', 'icon-dark']">
      </i>
    </button>


    <router-view />
  </div>
</template>

<script>
export default {
  name: 'App',
  data() {
    return {
      currentTheme: 'light',
    };
  },
  mounted() {
    document.documentElement.setAttribute('data-theme', this.currentTheme);
  },
  methods: {
    toggleTheme() {
      this.currentTheme = this.currentTheme === 'light' ? 'dark' : 'light';
      document.documentElement.setAttribute('data-theme', this.currentTheme);
      localStorage.setItem('theme', this.currentTheme);
    },
  },
};
</script>



<style>
/* Estilos globais para a altura total da tela */
html,
body {
  height: 100%;
  margin: 0;
  padding: 0;
}

#app {
  height: 100%;
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}

.theme-toggle-button {
  z-index: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  position: fixed;
  right: 30px;
  top: 30px;
  width: 50px;
  height: 50px;
  border-radius: 50%;
  border: none;
  outline: none;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.986);
  transition: all 0.3s ease;
  cursor: pointer;
 
}

.theme-icon {
  font-size: 2.0em;
  transition: color 0.3s ease;
}

.icon-light {
  color: #000;
}
.icon-light:hover {
  color: #555;
  
}
.icon-dark {
  color: #ffa600;
}
.icon-dark:hover {
  color: #ffd966;
}
</style>
