<template>
  <div>
    <b-navbar toggleable="lg" type="dark" variant="dark">
      <b-navbar-nav>
        <b-nav-item v-if="['/login', '/dashboard'].includes($route.path)" to="/register-admin">
          Criar Empresa
        </b-nav-item>
      </b-navbar-nav>  
    </b-navbar>
  </div>
</template>

<script>
export default {
  name: "NavbarComponent",
  computed: {
    user() {
      try {
        const userData = localStorage.getItem("user");
        return userData ? JSON.parse(userData) : null;
      } catch (e) {
        console.error("Erro ao parsear o objeto do usuário no localStorage", e);
        return null;
      }
    },
    isLogged() {
      return !!localStorage.getItem("user_token");
    },
    isAdmin() {
      return this.user && this.user.role === "admin";
    },
    isEmployee() {
      return this.user && this.user.role === "employee";
    }
  },
  methods: {
    logout() {
      localStorage.removeItem("user_token");
      localStorage.removeItem("user");
      this.$router.push("/login");
    }
  },
};
</script>

<style scoped>
.navbar {
  /* display: flex;
  justify-content: space-between;
  align-items: center; */
  padding: 12px 20px;
  background: #333;
  color: #fff;
}

</style>