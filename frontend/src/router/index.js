import Vue from "vue";
import VueRouter from "vue-router";
import LoginPage from "../views/LoginPage.vue";
import RegisterPage from "../views/RegisterPage.vue";
import TasksPage from "../views/TasksPage.vue";
import RegisterAdminPage from "../views/RegisterAdminPage.vue";


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
  {
    path: "/register-admin",
    name: "RegisterAdminPage",
    component: RegisterAdminPage,
  },

  //rota coringa serve para lidar com rotas n encontradas
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
  const requiresAdmin = to.matched.some((record) => record.meta.requiresAdmin);
  const user = JSON.parse(localStorage.getItem("user"));

  //proteção de rotas para usuarios logados
  if (requiresAuth && !loggedin) {
    return next({ name: "LoginPage" });
    
  } else if (requiresAdmin && (!user || user.role !== 'admin')){
    return next({ name: "TasksPage" });

  }else if (to.name === "LoginPage" && loggedin) {
    next({ name: "TasksPage" });
  } else {
    next();
  }
});

export default router;
