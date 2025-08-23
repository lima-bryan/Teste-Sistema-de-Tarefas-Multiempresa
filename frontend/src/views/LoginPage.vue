<template>
  <div class="login-container">
    <div class="login-box">
      <h1>Login</h1>
      <form @submit.prevent="handleLogin"> <!-- submi.prevent é um ouvinte de eventos do vue-->
        <div class="form-group">
          <label for="email">E-mail</label>
          <input type="email" id="email" v-model="email" required>
        </div>

        <div class="form-group password-group">
          <label for="password">Senha</label>
          <div class="password-input-container">
            <input :type="passwordVisible ? 'text' : 'password'" id="password" v-model="password" required>
            <span @click="togglePasswordVisibility" class="toggle-password">
              <i v-if="passwordVisible" class="fa-solid fa-eye"></i>
              <i v-else class="fa-solid fa-eye-slash"></i>
            </span>
          </div>
        </div>

        <!--msg de erro q aparece para o usuario-->
        <p v-if="errorMessage" class="error-message">{{ errorMessage }}</p>

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
      errorMessage: '',
      passwordVisible: false,  //para controlar a visibilidade da senha
    };
  },
  methods: {
    async handleLogin() {
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

        this.$router.push('/tasks');

      } catch (error) {
        console.error('Erro no login:', error.response);

        // Acessa a mensagem de erro específica da API, a de AVISO 
        if (error.response && error.response.data && error.response.data.AVISO) {
          this.errorMessage = error.response.data.AVISO;
        } else if (error.response && error.response.data && error.response.data.message) {
          this.errorMessage = error.response.data.message;
        } else {
          this.errorMessage = 'Erro ao tentar fazer login. Tente novamente.'; // Mensagem genérica para outros tipos de erro
        }
      }
    },
    // pra mostrar a senha 
    togglePasswordVisibility() {
      this.passwordVisible = !this.passwordVisible;
    },
  },
};
</script>

<style scoped>
/*login*/
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

.password-group,
.password-input-container {
  position: relative;
}

.password-input-container input {
  padding-right: 40px;
}

.toggle-password {
  position: absolute;
  right: 10px;
  top: 50%;
  transform: translateY(-50%);
  cursor: pointer;
  color: #888;
  font-size: 1.25rem;
}

.toggle-password:hover {
  color: #333;
}

/*-----*/

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