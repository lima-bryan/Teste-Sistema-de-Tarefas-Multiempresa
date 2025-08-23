import Vue from "vue";
import VueRouter from "vue-router";
import LoginPage from "../views/LoginPage.vue";
import RegisterPage from "../views/RegisterPage.vue";
import TasksPage from "../views/TasksPage.vue";

Vue.use(VueRouter);

const routes = [
  {
    path: "/login",
    name: "LoginPage", 
    component: LoginPage,
  },
  {
    path: "/register",
    name: "RegisterPage", 
    component: RegisterPage,
  },
  {
    path: "/tasks",
    name: "TasksPage", 
    component: TasksPage,
    meta: {
      requiresAuth: true,
    },
  },
  //Rota coringa serve para lidar com rotas n encontradas
  {
    path: "*",
    redirect: () => {
      const loggedin = localStorage.getItem("user_token");
      if (loggedin) {
        return { name: "TasksPage" }; 
      }
      return { name: "LoginPage" }; 
    },
  },
];

const router = new VueRouter({
  mode: "history",
  base: process.env.BASE_URL,
  routes,
});

// Comentário para desabilitar o linter para esta linha
// eslint-disable-next-line no-unused-vars
router.beforeEach((to, from, next) => {
  const loggedin = localStorage.getItem("user_token");
  const requiresAuth = to.matched.some((record) => record.meta.requiresAuth);

  if (requiresAuth && !loggedin) {
    next({ name: "LoginPage" }); 
  } else if (to.name === "LoginPage" && loggedin) { 
    next({ name: "TasksPage" }); 
  } else {
    next();
  }
});

export default router;