<template>
  <div class="register-container">
    <b-card title="Cadastro de Funcionário" class="register-box shadow-sm">
      
      <form @submit.prevent="handleRegister">  
        <RegisterFormComponent v-model="formData" :errors="errors"
        :showCompanyField="true"></RegisterFormComponent>
        <b-button type="submit" variant="success" class="w-100 mt-2">Cadastrar</b-button>
      </form>
      
      <b-button to="/dashboard" variant="secondary" class="w-100 mt-2">Voltar</b-button>

      <p v-if="error" class="text-danger mt-2 text-center">{{ error }}</p>
    </b-card>
    <b-modal v-model="showSuccessPopup" centered hide-header hide-footer no-close-on-backdrop no-close-on-esc>
      <div class="text-center py-4">
        <p class="text-success font-weight-bold">Usuário cadastrado com sucesso!</p>
      </div>
    </b-modal>
  </div>
</template>

<script>
import axios from 'axios';
import RegisterFormComponent from '../components/RegisterFormComponent.vue';

export default {
  name: 'CadastroFuncionario',
  components: { 
    RegisterFormComponent
   },
  data() {
    return {
      formData: {  
        first_name: '',
        last_name: '',
        company_name: '',
        email: '',
        phone: '',
        password: '',
        password_confirmation: ''
      },
      error: '',
      errors: {},
      showSuccessPopup: false,

    };
  },
  methods: {
    handleRegister() {
      this.error = '';
      this.errors = {};

      const apiUrl = 'http://127.0.0.1:8000/api/register';

      axios.post(apiUrl, this.formData)
        .then(response => {
          console.log('Funcionário registrado com sucesso:', response.data);
          this.showSuccessPopup = true;
          setTimeout(() => {
            this.showSuccessPopup = false;
            this.$router.push('/tasks');
          }, 3000);
        })
        .catch(error => {
          console.error('Erro no registro do funcionário:', error.response);
          if (error.response && error.response.data) {
            if (error.response.status === 422 && error.response.data.errors) {
              this.errors = error.response.data.errors;
            } else if (error.response.data.message) {
              this.error = error.response.data.message;
            } else {
              this.error = 'Ocorreu um erro ao tentar registrar o funcionário. Por favor, tente novamente.';
            }
          } else {
            this.error = 'Erro de conexão';
          }
        });
    },
  },
};
</script>


 <style scoped>
.register-container {
  display: flex;
  justify-content: center;
  align-items: center;
  padding-top: 5rem;
  min-height: 100vh;
  background-color: #f8f9fa;
}

.register-box {
  width: 90%;
  max-width: 500px;
}
</style> 
