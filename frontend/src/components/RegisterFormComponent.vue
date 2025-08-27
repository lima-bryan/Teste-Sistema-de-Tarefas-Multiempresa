<template>
    <div>
      <!-- ✨ Solução: Usamos um proxy (propriedade computada) para vincular diretamente aos dados do pai, evitando o loop infinito -->
      <b-form-group label="Nome" label-for="first_name">
        <b-form-input id="first_name" v-model="userProxy.first_name" required></b-form-input>
      </b-form-group>
  
      <b-form-group label="Sobrenome" label-for="last_name">
        <b-form-input id="last_name" v-model="userProxy.last_name" required></b-form-input>
      </b-form-group>
      
      <b-form-group label="Nome da Empresa" label-for="company_name" :state="errors.company_name ? false : null" v-if="showCompanyField">
        <b-form-input id="company_name" v-model="userProxy.company_name" required :state="errors.company_name ? false : null"></b-form-input>
        <b-form-invalid-feedback v-if="errors.company_name">
          {{ errors.company_name[0] }}
        </b-form-invalid-feedback>
      </b-form-group>
      
      <b-form-group label="E-mail" label-for="email" :state="errors.email ? false : null">
        <b-form-input id="email" type="email" v-model="userProxy.email" required :state="errors.email ? false : null"></b-form-input>
        <b-form-invalid-feedback v-if="errors.email">
          {{ errors.email[0] }}
        </b-form-invalid-feedback>
      </b-form-group>
  
      <b-form-group label="Telefone" label-for="phone">
        <b-form-input id="phone" type="tel" v-model="userProxy.phone"></b-form-input>
      </b-form-group>
  
      <b-form-group label="Senha" label-for="password" :state="errors.password ? false : null">
        <b-form-input id="password" type="password" v-model="userProxy.password" required :state="errors.password ? false : null"></b-form-input>
        <b-form-invalid-feedback v-if="errors.password">
          {{ errors.password[0] }}
        </b-form-invalid-feedback>
      </b-form-group>
  
      <!-- O 'errors.password_confirmation' valida a confirmação de senha -->
      <b-form-group label="Confirmar Senha" label-for="password_confirmation" :state="errors.password_confirmation ? false : null">
        <b-form-input 
          id="password_confirmation" 
          type="password" 
          v-model="userProxy.password_confirmation" 
          required 
          :state="errors.password_confirmation ? false : null">
        </b-form-input>
        <b-form-invalid-feedback v-if="errors.password_confirmation">
          {{ errors.password_confirmation[0] }}
        </b-form-invalid-feedback>
      </b-form-group>
    </div>
  </template>
  
  <script>
  export default {
    name: 'RegisterFormComponent',
    props: {
      value: {
        type: Object,
        required: true,
      },
      errors: {
        type: Object,
        default: () => ({}),
      },
      showCompanyField: {
        type: Boolean,
        default: false,
      }
    },
    // ✨ Solução: Removemos o 'data()' e o 'watch' para evitar o loop.
    // Usamos uma propriedade computada para o binding bidirecional.
    computed: {
      userProxy: {
        get() {
          return this.value;
        },
        set(newValue) {
          // Quando o valor muda, emitimos o evento 'input' para o componente pai.
          this.$emit('input', newValue);
        }
      }
    }
  };
  </script>
  
  <style scoped>
  .form-card {
    max-width: 500px;
    width: 100%;
    padding: 1.5rem;
  }
  </style>
  