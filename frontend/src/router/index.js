import Vue from "vue";
import VueRouter from "vue-router";

Vue.use(VueRouter);

const routes = [
  {
    path: "/login",
    name: 'login',
    component: () => import("../views/Login")
  },
  {
    path: "/home",
    name: "home",
    component: () => import("../views/Home"),
    meta: {
      requiresAuth: true
    },
    children: [
      {
        path: "/atendimento:paciente",
        name: "atendimento",
        component: () => import("../views/Atendimento")
      }
    ]
  }
];

const router = new VueRouter({
  mode: "history",
  base: process.env.BASE_URL,
  routes
});

export default router;
