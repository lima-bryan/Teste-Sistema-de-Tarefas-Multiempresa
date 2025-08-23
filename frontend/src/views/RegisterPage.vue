<template>
  <div class="register-container">
    <div class="register-box">
      <h1>Criar Conta</h1>
      <form @submit.prevent="handleRegister">

        <div class="form-group">
          <label for="first_name">Nome</label>
          <input type="text" id="first_name" v-model="first_name" required>
        </div>

        <div class="form-group">
          <label for="last_name">Sobrenome</label>
          <input type="text" id="last_name" v-model="last_name" required>
        </div>

        <div class="form-group">
          <label for="company_name">Nome da Empresa</label>
          <input type="text" id="company_name" v-model="company_name" :class="{'input-error': errors.company_name}" required>
          <p v-if="errors.company_name" class="field-error">{{ errors.company_name[0] }}</p>
        </div>

        <div class="form-group">
          <label for="email">E-mail</label>
          <input type="email" id="email" v-model="email" :class="{'input-error': errors.email}" required>
          <p v-if="errors.email" class="field-error">{{ errors.email[0] }}</p>
        </div>

        <div class="form-group">
          <label for="phone">Telefone</label>
          <input type="tel" id="phone" v-model="phone">
        </div>

        <div class="form-group">
          <label for="password">Senha</label>
          <input type="password" id="password" v-model="password" :class="{'input-error': errors.password}" required>
        </div>

        <div class="form-group">
          <label for="password_confirmation">Confirmar Senha</label>
          <input type="password" id="password_confirmation" v-model="password_confirmation" :class="{'input-error': errors.password}" required>
          <p v-if="errors.password" class="field-error">{{ errors.password[0] }}</p>
        </div>

        <button type="submit">Cadastrar</button>
      </form>
      <p>Já tem uma conta? <router-link to="/login">Faça login aqui</router-link>.</p>
      <p v-if="error" class="error-message">{{ error }}</p>
    </div>

    <div v-if="showSuccessPopup" class="success-popup">
      <div class="popup-content">
        <span class="close-btn" @click="closeSuccessPopup">&times;</span>
        <p>Cadastro realizado com sucesso!</p>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'RegisterPage',
  data() {
    return {
      first_name: '',
      last_name: '',
      email: '',
      phone: '',
      password: '',
      password_confirmation: '',
      company_name: '',
      error: '',
      errors: {},
      showSuccessPopup: false,
    };
  },
  methods: {
    handleRegister() {
      this.error = '';
      this.errors = {};

      const userData = {
        first_name: this.first_name,
        last_name: this.last_name,
        email: this.email,
        phone: this.phone,
        password: this.password,
        password_confirmation: this.password_confirmation,
        company_name: this.company_name,
      };

      const apiUrl = 'http://127.0.0.1:8000/api/register';

      axios.post(apiUrl, userData)
        .then(response => {
          console.log('Usuário e empresa registrados com sucesso:', response.data);
          this.showSuccessPopup = true;
          setTimeout(() => {
            this.closeSuccessPopup();
            this.$router.push('/login');
          }, 3000);
        })
        .catch(error => {
          console.error('Erro no registro:', error.response);
          if (error.response && error.response.data) {
            if (error.response.data.message) {
              this.error = error.response.data.message;
            } else {
              this.errors = error.response.data;
              // AQUI: A tradução de email foi removida. O backend agora é responsável por isso.
            }
          } else {
            this.error = 'Erro ao tentar registrar o usuário. Por favor, tente novamente.';
          }
        });
    },
    closeSuccessPopup() {
      this.showSuccessPopup = false;
    }
  },
};
</script>

<style scoped>
.register-container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  background-color: #f0f2f5;
}

.register-box {
  background: white;
  padding: 40px;
  border-radius: 8px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  max-width: 30%;
  width: 90%;
  text-align: center;
}

.register-box h1 {
  margin-bottom: 20px;
}

.form-group {
  margin-bottom: 15px;
  text-align: left;
}

.form-group label {
  display: block;
  margin-bottom: 5px;
  font-weight: bold;
}

.form-group input {
  width: 100%;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 4px;
}

button {
  width: 100%;
  padding: 10px;
  border: none;
  border-radius: 4px;
  background-color: #28a745;
  color: white;
  font-size: 16px;
  cursor: pointer;
  margin-top: 10px;
}

button:hover {
  background-color: #218838;
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

/* Estilos para o pop-up de sucesso */
.success-popup {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
}

.popup-content {
  background-color: white;
  padding: 30px;
  border-radius: 8px;
  text-align: center;
  position: relative;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
  transform: scale(0.95);
  animation: pop-in 0.3s forwards;
}

@keyframes pop-in {
  to { transform: scale(1); }
}

.popup-content p {
  color: #28a745;
  font-size: 1.2rem;
  font-weight: bold;
}

.close-btn {
  position: absolute;
  top: 10px;
  right: 15px;
  font-size: 24px;
  cursor: pointer;
  color: #aaa;
}

.input-error {
  border: 1px solid #dc3545;
  background-color: #fff5f5;
}

.field-error {
  color: #dc3545;
  font-size: 0.85rem;
  margin-top: 5px;
}
</style>
