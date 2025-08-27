<template>
  <div>

    <div>
      <b-modal id="confirm-creation-modal" title="AVISO!" @ok="createTask" ok-only ok-title="OK">
        Tarefa Criada Com Sucesso!
      </b-modal>
    </div>

    <b-modal id="edit-success-modal" title="AVISO!" ok-only ok-title="OK">
      Tarefa Atualizada Com Sucesso!
    </b-modal>



    <div class="tasks-page">
      <header class="header">
        <div class="user-info" v-if="user">
          <span>{{ user.first_name }} {{ user.last_name }}</span>
          <span v-if="user.company"> ({{ user.company.company_name }}) </span> <br>
          <span>{{ translateTaskField(user.role) }}</span>
        </div>

        <base-button class="logout-btn" @click="handleLogout">Sair <b-icon icon="x-square"></b-icon></base-button>
      </header>

      <main class="content">
        <h1>Gerenciamento de Tarefas</h1>

        <ConfirmModal modal-id="confirm-delete-modal" title="Confirmar Exclusão" ok-text="Sim" cancel-text="Não"
          ok-variant="danger" @confirmed="deleteTaskConfirmed">
          Você tem certeza que deseja excluir esta tarefa?
        </ConfirmModal>

        <base-button v-if="!showForm" class="btn-toggle-form" @click="showForm = true">Adicionar Nova
          Tarefa</base-button>

        <form id="form_action" class="task-form" v-if="showForm" @submit.prevent="submitForm">
          <h3>{{ editing ? 'Editar Tarefa' : 'Adicionar Nova Tarefa' }}</h3>

          <input type="text" v-model="currentTask.title" placeholder="Título" required>
          <textarea v-model="currentTask.description" placeholder="Descrição"></textarea>

          <div class="form-group">
            <label> Prioridade: </label>
            <select v-model="currentTask.priority">
              <option value="low">Baixa</option>
              <option value="medium">Média</option>
              <option value="high">Alta</option>
            </select>
          </div>

          <div class="form-group">
            <label>Status:</label>
            <select v-model="currentTask.status">
              <option value="pending">Pendente</option>
              <option value="in progress">Em Progresso</option>
              <option value="completed">Concluída</option>
              <option value="canceled">Cancelada</option>
            </select>
          </div>

          <div class="form-group">
            <label>Prazo: </label>
            <input type="date" v-model="currentTask.due_date">
          </div>

          <base-button v-if="editing" type="submit"> Salvar <b-icon icon="save"></b-icon></base-button>
          <base-button v-else type="submit"> <b-icon icon="newspaper"></b-icon>
            Criar Tarefa
          </base-button>

          <base-button @click="hideFormAndReset">
            <b-icon icon="x-square"></b-icon>
            Fechar
          </base-button>

          <p class="error-message" v-if="errorMessage">{{ errorMessage }}</p>
        </form>

        <div class="tasks-container">
          <div class="tasks-section">
            <h2>Minhas Tarefas</h2>
            <div class="task-list">
              <template v-if="userTasks.length > 0">
                <div class="task-item" v-for="task in userTasks" :key="task.id">
                  <h3>{{ task.title }}</h3>
                  <p>{{ task.description }}</p>
                  <p>Prioridade: {{ translateTaskField(task.priority) }}</p>
                  <p>Status: {{ translateTaskField(task.status) }}</p>
                  <p>Prazo: {{ task.due_date }}</p>

                  <div class="task-actions">
                    <base-button variant="warning" @click="editTask(task)">
                      <b-icon icon="list"></b-icon>
                      Editar
                    </base-button>
                    <base-button variant="danger" @click="deleteTask(task.id)">
                      <b-icon icon="trash"></b-icon>
                      Excluir
                    </base-button>
                  </div>
                </div>
              </template>
              <p v-else>Nenhuma Tarefa sua encontrada.</p>
            </div>
          </div>

          <div class="tasks-section">
            <h2>Tarefas da Empresa</h2>
            <div class="task-list">
              <template v-if="companyTasks.length > 0">
                <div class="task-item" v-for="task in companyTasks" :key="task.id">
                  <h3>{{ task.title }}</h3>
                  <p>{{ task.description }}</p>
                  <p>Prioridade: {{ translateTaskField(task.priority) }}</p>
                  <p>Status: {{ translateTaskField(task.status) }}</p>
                  <p>Prazo: {{task.due_date}}</p>


                  <div class="task-actions" v-if="isUserAdmin">
                    <base-button variant="warning" @click="editTask(task)">
                      <b-icon icon="list"></b-icon>
                      Editar
                    </base-button>
                    <base-button variant="danger" @click="deleteTask(task.id)">
                      <b-icon icon="trash"></b-icon>
                      Excluir
                    </base-button>
                  </div>
                </div>
              </template>
              <p v-else>Nenhuma Tarefa da empresa encontrada.</p>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<script>
import BaseButton from '../components/BaseButton.vue';
import ConfirmModal from '../components/ConfirmModal.vue';
import axios from 'axios';


export default {
  name: 'TasksPage',
  components: {
    BaseButton,
    ConfirmModal,
  },
  data() {
    return {
      user: null,
      userTasks: [],
      companyTasks: [],
      currentTask: {
        id: null,
        title: '',
        description: '',
        priority: 'low',
        status: 'pending',
        due_date: ''
      },
      editing: false,
      loading: true,
      errorMessage: '',
      showForm: false,
      taskIdToDelete: null,

    };
  },
  // ✅ Posição correta da propriedade computada
  computed: {
    isUserAdmin() {
      const user = JSON.parse(localStorage.getItem('user'));
      return user && user.role === 'admin';
    },
   

  },
  created() {
    this.fetchUserInfo();
    this.fetchTasks();
  },

  mounted() {
    this.fetchUserInfo();
    this.fetchTasks();
  },

  methods: {
    getAuthHeaders() {
      const token = localStorage.getItem('user_token');
      if (!token) {
        this.$router.push('/login');
        return {};
      } else {
        return {
          headers: {
            Authorization: `Bearer ${token}`,
            Accept: 'application/json'
          }
        };
      }
    },
    translateTaskField(value) {
      // traduções para os campos de tarefa
      const translations = {
        'admin': 'ADM',
        'employee': 'Funcionário',
        'high': 'Alta',
        'low': 'Baixa',
        'pending': 'Pendente',
        'in progress': 'Em Progresso',
        'completed': 'Concluída',
        'canceled': 'Cancelada'
      };
      // Retorna a tradução se ela existir, caso contrário, retorna o valor original
      return translations[value.toLowerCase()] || value;
    },
    fetchUserInfo() {
      const storedUser = localStorage.getItem('user');
      const token = localStorage.getItem('user_token');
      if (storedUser && token) {
        this.user = JSON.parse(storedUser);
      } else {
        this.$router.push('/login');
      }
    },
    submitForm() {
      this.errorMessage = '';
      if (this.editing) {
        this.updateTask();
      } else {
        this.$bvModal.show('confirm-creation-modal');
      }
    },
    async fetchTasks() {
      this.loading = true;
      try {
        const response = await axios.get('http://127.0.0.1:8000/api/tasks', this.getAuthHeaders());
        this.userTasks = [];
        this.companyTasks = [];
        this.user = response.data.user;

        response.data.tasks.data.forEach(task => {
          if (task.user_id === this.user.id) {
            this.userTasks.push(task);
          } else {
            this.companyTasks.push(task);
          }
        });
      } catch (error) {
        this.errorMessage = 'Erro ao carregar as tarefas.';
        console.error('Erro detalhado ao buscar tarefas:', error.response);
      } finally {
        this.loading = false;
      }
    },
    async createTask() {
      try {
        await axios.post('http://127.0.0.1:8000/api/tasks', this.currentTask, this.getAuthHeaders());
        this.resetForm();
        this.fetchTasks();
        this.$bvModal.show('confirm-creation-modal'); // ✅ Usando o ID do modal correto
        this.showForm = false;
      } catch (error) {
        this.errorMessage = 'Erro ao criar a tarefa.';
        console.error('Erro ao criar tarefa:', error.response);
      }
    },
    async updateTask() {
      try {
        await axios.put(`http://127.0.0.1:8000/api/tasks/${this.currentTask.id}`, this.currentTask, this.getAuthHeaders());
        this.resetForm();
        this.fetchTasks();
        this.$bvModal.show('edit-success-modal');
        this.hideFormAndReset();
      } catch (error) {
        this.errorMessage = 'Erro ao atualizar a tarefa';
        console.error('Erro ao atualizar tarefa:', error.response);
      }
    },
    deleteTask(id) {
      this.taskIdToDelete = id;
      this.$bvModal.show('confirm-delete-modal');
    },
    async deleteTaskConfirmed() {
      try {
        await axios.delete(`http://127.0.0.1:8000/api/tasks/${this.taskIdToDelete}`, this.getAuthHeaders());
        this.fetchTasks();
      } catch (error) {
        this.errorMessage = 'Erro ao excluir a tarefa.';
        console.error('Erro ao excluir a tarefa:', error.response);
      }
    },
    handleLogout() {
      const token = localStorage.getItem('user_token');
      try {
        axios.post('http://127.0.0.1:8000/api/auth/logout', null, {
          headers: { 'Authorization': `Bearer ${token}` }
        });
      } catch (error) {
        this.errorMessage = 'Erro ao fazer Logout.';
        console.log('Erro ao fazer logout:', error.response);
      } finally {
        localStorage.removeItem('user_token');
        localStorage.removeItem('user');
        this.$router.push('/login');
      }
    },
    resetForm() {
      this.currentTask = {
        id: null,
        title: '',
        description: '',
        priority: 'low',
        status: 'pending',
        due_date: ''
      };
      this.editing = false;
      this.errorMessage = '';
    },
    cancelEdit() {
      this.resetForm();
      this.showForm = false;
    },
    editTask(task) {
      this.editing = true;
      this.currentTask = { ...task };
      this.showForm = true;
      this.$nextTick(() => {
        const form_action = document.getElementById('form_action');
        if (form_action) {
          form_action.scrollIntoView({ behavior: 'smooth' });
        }
      });
    },
    hideFormAndReset() {
      this.showForm = false;
      this.resetForm();
    }
  }
};
</script>

<style scoped>
.tasks-page {
  font-family: Arial, sans-serif;
  max-width: 900px;
  margin: 0 auto;
  padding: 20px;
}

.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
  border-bottom: 1px solid #ccc;
  padding-bottom: 10px;
}

.user-info {
  font-size: 1.2rem;
  font-weight: bold;
  text-transform: uppercase;
}

.logout-btn {
  padding: 8px 15px;
  background-color: #dc3545;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.content {
  padding: 20px;
  background-color: #f8f9fa;
  border-radius: 8px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

h1 {
  text-align: center;
  margin-bottom: 20px;
  color: #333;
}

.task-form {
  display: flex;
  flex-direction: column;
  gap: 10px;
  margin-bottom: 20px;
  padding: 20px;
  border: 1px solid #ddd;
  border-radius: 8px;
}

.task-form h3 {
  margin-top: 0;
}

.task-form input,
.task-form textarea,
.task-form select {
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
}

.task-form button {
  padding: 10px 20px;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.task-form button:hover {
  background-color: #0056b3;
}

.task-list {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.task-form textarea {
  resize: none;
}

.task-item {
  border: 1px solid #e0e0e0;
  padding: 15px;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
  background-color: #ffffff;
}

.task-item h3 {
  margin-top: 0;
  margin-bottom: 5px;
}

.task-item p {
  margin: 5px 0;
  color: #666;
}

.task-actions {
  margin-top: 10px;
}

.task-actions button {
  margin-right: 10px;
  padding: 8px 12px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

/* .task-actions button:first-child {
  background-color: #ffc107;
}

.task-actions button:last-child {
  background-color: #dc3545;
  color: white;
} */

.btn-toggle-form {
  padding: 15px 25px;
  font-size: 1rem;
  font-weight: bold;
  background-color: #28a745;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  margin-top: 20px;
  width: 100%;
  transition: background-color 0.3s ease;
}

/* .btn-toggle-form:hover {
  background-color: #218838;
} */

/* NOVO: Estilos para as duas colunas */
.tasks-container {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.tasks-section {
  border: 1px solid #ddd;
  padding: 15px;
  border-radius: 8px;
}

.tasks-section h2 {
  text-align: center;
  margin-bottom: 15px;
  color: #555;
  border-bottom: 1px solid #eee;
  padding-bottom: 10px;
}

@media (min-width: 768px) {
  .tasks-container {
    flex-direction: row;
    justify-content: space-between;
  }

  .tasks-section {
    width: 48%;
    /* Para dar espaço entre as colunas */
  }
}
</style>
