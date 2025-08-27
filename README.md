# ToDo List Multitenant

Projeto de gerenciamento de tarefas multiempresa (multitenant) desenvolvido com **Laravel 8 (API com JWT)** no backend e **Vue.js 2** no frontend.

Obs.: Criei um email para vocês verem a notificação das tarefas criada e concluidas no Mailtrap <br>
email: desafiotecnico67@gmail.com <br>
senha: des@fio123 <br>
https://mailtrap.io/inboxes/3992733/messages <br>

Empresa1 / Empresa2 <br>
Pra fazer login adm <br>
adm1@teste.com<br>
senha:123456<br>

adm2@teste.com<br>
senha:123456<br>

Funcionario:<br>
funcionario@teste.com <br>
senha:123456 <br>

Funcionario:<br>
funcionario2@teste.com <br>
senha:123456


---

## 🚀 Tecnologias

* **Backend:** Laravel 8, Composer, JWT Auth
* **Frontend:** Vue.js 2, Vue Router, Axios, BootstrapVue
* **Banco de Dados:** MySQL/MariaDB

---

## 📦 Pré-requisitos

Antes de rodar o projeto, instale:

* [PHP ^7.4 ou ^8.0](https://www.php.net/)
* [Composer](https://getcomposer.org/)
* [MySQL ou MariaDB](https://www.mysql.com/)
* [Node.js ^14 ou ^16](https://nodejs.org/)
* [NPM](https://www.npmjs.com/) ou [Yarn](https://yarnpkg.com/)

---

## ⚙️ Configuração do Backend (Laravel API)

1. Acesse a pasta do backend:

   ```bash
   cd backend
   ```

2. Instale as dependências:

   ```bash
   composer install
   npm install
   ```

3. Configure o arquivo `.env`:

   ```bash
   cp .env.example .env
   ```

   * Configure as variáveis de ambiente (banco, JWT\_SECRET, etc.)
   * php artisan jwt:secret


4. Gere a chave da aplicação e secret JWT:

   ```bash
   php artisan key:generate
   php artisan jwt:secret
   ```

5. Rode as migrações e seeders:

   ```bash
   php artisan migrate --seed
   ```

6. Inicie o servidor backend:

   ```bash
   php artisan serve
   ```

   O backend rodará em: **[http://127.0.0.1:8000](http://127.0.0.1:8000)**

---

## 🎨 Configuração do Frontend (Vue.js)

1. Acesse a pasta do frontend

   ```bash
   cd frontend
   ```

2. Instale as dependências:

   ```bash
   npm install
   ```

3. Configure a URL da API no arquivo de variáveis (ex: `.env` ou `config.js`).

4. Rode o servidor de desenvolvimento:

   ```bash
   npm run serve
   ```

   O frontend rodará em: **[http://localhost:8080](http://localhost:8080)**

---

## 🔑 Acesso

* **Admin**: Criado automaticamente via seeder.
* **Funcionários**: Criado automaticamente via seeder.

---

## 📂 Estrutura do Projeto

```
backend/   -> API Laravel (autenticação JWT, rotas, models, controllers)
frontend/  -> Aplicação Vue.js 2 (interface do usuário)
```


## 👨‍💻 Autor

Projeto desenvolvido por Bryan Lima.

