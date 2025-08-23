<template>
  <div class="tasks-page">
    <header class="header">
      <!--pra exibir o nome do usuario e da empresa-->
      <div class="user-info" v-if="user">
        <span>Olá, {{ user.first_name }} {{ user.last_name }}</span>
        <span v-if="user.company">({{ user.company.name }})</span>
      </div>

      <button class="logout-btn" @click="handleLogout">Sair</button>
    </header>

    <main class="content">
      <!--listar tarefas-->
      <h1>Tarefas</h1>
      <div class="task-list">
        <p v-if="loading">Carregando Tarefas</p>
        <p v-else-if="tasks.length === 0">Nenhuma Tarefa Encontrada.</p>
        
        <div class="task-item" v-else v-for="task in tasks" :key="task.id">
          <h3>{{ task.title }}</h3>
          <p>{{ task.description }}</p>
          <p>Prioridade: {{ task.priority }}</p>
          <p>Status: {{ task.status }}</p>
          <p>Prazo: {{ task.due_date }}</p>

          <div class="task-actions">
            <button @click="editTask(task)">Editar</button> <!--mudar para componente de botao-->
            <button @click="deleteTask(task.id)">Excluir</button>
          </div>
        </div>
      </div>
      <form class="task-form" @submit.prevent="submitForm">
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
          <label>Prazo</label>
          <input type="date" v-model="currentTask.due_date">
        </div>

        <button type="submit">{{ editing ? 'Salvar Edição' : 'Criar Tarefa' }} </button>
        <button type="button" @click="cancelEdit" v-if="editing">Cancelar</button>

        <!--se caso acontecer algum erro-->
        <p class="error-message" v-if="errorMessage">{{ errorMessage }}</p>
      </form>



    </main>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'TasksPage',
  data() {
    return {
      user: null, //pra exibir o nome do usuario
      tasks: [], //armazenar as tarefas

      //para exibir as informações da tarefa
      currentTask: {
        id: null,
        title: '',
        description: '',
        priority: 'low',
        status: 'pending',
        due_date: ''
      },
      editing: false,
      loading: true,  //estado de carregamento
      errorMessage: '' //msg de erro
    };
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
    //pegar o token do localstorage
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

    //busca os dados do usuario no localstorage
    fetchUserInfo() {
      // Esta função não é mais necessária pois os dados do usuário
      // são buscados junto com as tarefas no método fetchTasks.
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
        this.createTask();
      }
    },

    //para carregar as tarefas
    async fetchTasks() {
      this.loading = true;
      try {
        const response = await axios.get('http://127.0.0.1:8000/api/tasks', this.getAuthHeaders());
        this.tasks = response.data.tasks.data;
        this.user = response.data.user;
      } catch (error) {
        this.errorMessage = 'Erro ao carregar as tarefas.';
        console.error('Erro detalhado ao buscar tarefas:', error.response); // Adicionado para depuração
      } finally {
        this.loading = false;
      }
    },

    async createTask() {
      try {
        await axios.post('http://127.0.0.1:8000/api/tasks', this.currentTask, this.getAuthHeaders());
        this.resetForm();
        this.fetchTasks()
      } catch (error) {
        this.errorMessage = 'Erro ao criar a tarefa.';
        console.error('Erro ao criar tarefa:', error.response);
      }
    },

    async updateTask() {
      try {
        await axios.put(`http://127.0.0.1:8000/api/tasks/${this.currentTask.id}`, this.currentTask, this.getAuthHeaders());
        this.resetForm();
        this.fetchTasks() //recarrega a lista para mostrar a edição
      } catch (error) {
        this.errorMessage = 'Erro ao atualizar a tarefa';
        console.error('Erro ao atualizar tarefa:', error.response);
      }
    },

    async deleteTask(id) {
      if (!confirm('Tem certeza que deseja excluir esta tarefa?')) {
        return;
      }
      try {
        await axios.delete(`http://127.0.0.1:8000/api/tasks/${id}`, this.getAuthHeaders());
        this.fetchTasks() //recarrega a lsita pra remover a tarefa
      } catch (error) {
        this.errorMessage = 'Erro ao excluir a tarefa.';
        console.error('Erro ao excluir a tarefa:', error.response);
      }
    },

    //logout
    async handleLogout() {
      const token = localStorage.getItem('user_token');
      try {
        await axios.post('http://127.0.0.1:8000/api/auth/logout', null, {
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

    //resetar o formulario 
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
    },
    editTask(task) {
      this.editing = true;
      // Clonar a tarefa para não modificar o objeto original
      this.currentTask = { ...task };
    }

  
  } //fim do methodo
}; //export default,
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

.task-actions button:first-child {
  background-color: #ffc107;
}

.task-actions button:last-child {
  background-color: #dc3545;
  color: white;
}

.error-message {
  color: #dc3545;
  margin-top: 10px;
  font-weight: bold;
}
</style>
