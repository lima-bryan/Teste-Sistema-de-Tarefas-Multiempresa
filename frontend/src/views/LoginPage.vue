<template>
  <div class="login-container">
    <div class="login-box">
      <h1>Login</h1>
      <form @submit.prevent="handleLogin"> <!-- submi.prevent é um ouvinte de eventos do vue-->

        <div class="form-group">
          <label for="email">E-mail</label>
          <input type="email" id="email" v-model="email" required>
        </div>

        <div class="form-group">
          <label for="Password">Senha</label>
          <input type="password" id="password" v-model="password" required>
          
         
        </div>

        <button type="submit" class="btn-submit">Entrar</button>
      </form>
      <p>Ainda não tem uma conta? <router-link to="/register">Cadastre-se aqui</router-link>.</p>
    </div>
  </div>
</template>


<script>
import axios from 'axios';
export default {
  name: 'LoginPage',
  data() {
    return {
      email: '',
      password: '',
      // Nome mais descritivo para a variável de erro
      errorMessage: '',
    };
  },
  methods: {
    // Usamos 'async' para poder usar 'await' dentro da função
    async handleLogin() {
      // Limpa qualquer erro anterior
      this.errorMessage = '';

      const credentials = {
        email: this.email,
        password: this.password,
      };

      const apiUrl = 'http://127.0.0.1:8000/api/auth/login';

      try {

        const response = await axios.post(apiUrl, credentials);


        const { token, user } = response.data;
        localStorage.setItem('user_token', token);
        localStorage.setItem('user', JSON.stringify(user));

        console.log('Login bem-sucedido:', response.data);

        // Redireciona para a página de tarefas
        this.$router.push('/tasks');

      } catch (error) {
        // O bloco 'catch' é executado se a requisição falhar
        console.error('Erro no login:', error.response);

        // Acessa a mensagem de erro específica da API, se disponível
        if (error.response && error.response.data && error.response.data.message) {
          this.errorMessage = error.response.data.message;
        } else {
          // Mensagem genérica para outros tipos de erro
          this.errorMessage = 'Erro ao tentar fazer login. Tente novamente.';
        }
      }
    },
  },
};
</script>


<style scoped>
.login-container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  background-color: var(--primary-bg-color);
}

.login-box {
  text-align: center;
  padding: 40px;
  border-radius: 8px;
  box-shadow: 0 4px 6px;
  width: 90%;
  max-width: 350px;
  background: var(--login-bg-color);
}

.login-box h1 {
  margin-bottom: 30px;
  text-align: center;
  font-size: 40px;
  color: var(--text-color);

}

.form-group {
  margin-bottom: 15px;
  text-align: left;

}

.form-group label {
  display: block;
  text-align: center;
  margin-bottom: 5px;
  font-weight: bold;
  color: var(--text-color);
}

.form-group input {
  width: 100%;
  padding: 10px;
  border: 1px solid #C7C8C9;
  ;
  border-radius: 8px;
  box-sizing: border-box;
}

.btn-submit {
  padding: 15px 30px;
  font-size: 1.2rem;
  border: none;
  border-radius: 30px;
  outline: none;
  position: relative;
  overflow: hidden;
  font-weight: bold;

  color: var(--btn-text-color);
  background-color: var(--btn-bg-color);

  transition: all 0.4s ease;
  cursor: pointer;
}

.btn-submit:hover {
  background: var(--btn-bg2-color);
}

p {
  margin-top: 20px;
  font-size: 14px;
}

a {
  color: #007bff;
  text-decoration: none;
}

a:hover {
  text-decoration: underline;
}

.error-message {
  color: red;
  margin-top: 10px;
}
</style>