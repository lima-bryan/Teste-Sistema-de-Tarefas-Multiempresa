<template>
    <div class="register-container">
      <b-card title="Cadastro de Adm e Empresa" class="register-box shadow-sm">
        
        <RegisterFormComponent v-model="formData" :errors="errors" :showCompanyField="true"> </RegisterFormComponent>
  
        <b-button variant="success" class="w-100 mt-2" @click="handleRegister">Cadastrar</b-button>
        <b-button to="/dashboard" variant="secondary" class="w-100 mt-2">Voltar</b-button>
  
        <p v-if="error" class="text-danger mt-2 text-center">{{ error }}</p>
      </b-card>
  
      <b-modal v-model="showSuccessPopup" centered hide-header hide-footer no-close-on-backdrop no-close-on-esc>
        <div class="text-center py-4">
          <p class="text-success font-weight-bold">Administrador cadastrado com sucesso!</p>
        </div>
      </b-modal>
    </div>
  </template>

<script>
// A lógica do script permanece a mesma
import axios from 'axios';
import RegisterFormComponent from '../components/RegisterFormComponent.vue';

export default {
    name: 'RegisterAdminPage',
    components: {
        RegisterFormComponent,
    },
    data() {
        return {
            formData: {
                first_name: '',
                last_name: '',
                email: '',
                phone: '',
                password: '',
                password_confirmation: '',
                company_name: '',
            },
            error: '',
            errors: {},
            showSuccessPopup: false,
            isLoading: false,
        };
    },
    methods: {
        handleRegister() {
            this.error = '';
            this.errors = {};
            this.isLoading = true;

            const apiUrl = 'http://127.0.0.1:8000/api/register-admin';

            axios.post(apiUrl, this.formData)
                .then(response => {
                    localStorage.setItem('user_token', response.data.token);
                    localStorage.setItem('user_info', JSON.stringify(response.data.user));
                    this.showSuccessPopup = true;
                    setTimeout(() => {
                        this.closeSuccessPopup();
                        this.$router.push('/login');
                    }, 3000);
                })
                .catch(error => {
                    if (error.response && error.response.data) {
                        this.error = error.response.data.message || 'Erro ao tentar registrar o usuário.';
                        this.errors = error.response.data.errors || {};
                    } else {
                        this.error = 'Erro ao tentar registrar o usuário. Por favor, tente novamente.';
                    }
                })
                .finally(() => {
                    this.isLoading = false;
                });
        },
        closeSuccessPopup() {
            this.showSuccessPopup = false;
        }
    },
};
</script>

<style scoped>
/* O estilo deve ser copiado do seu código */
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