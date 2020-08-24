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
      },

      // Usuário
      {
        path: "/usuario/listagem",
        name: "usuario_listagem",
        component: () => import("../views/Usuario/Listagem")
      },
      {
        path: "/usuario/cadastro/:id",
        name: "usuario_cadastro",
        component: () => import("../views/Usuario/Cadastro"),
        props: {
          default: true
        }
      },

      // Unidades de Saúde
      {
        path: "/unidades_saude/listagem",
        name: "unidades_saude_listagem",
        component: () => import("../views/UnidadesSaude/Listagem")
      },
      {
        path: "/unidades_saude/cadastro/:id",
        name: "unidades_saude_cadastro",
        component: () => import("../views/UnidadesSaude/Cadastro"),
        props: {
          default: true
        }
      },

      // Unidades Sintomática
      {
        path: "/unidades_sintomatica/listagem",
        name: "unidades_sintomatica_listagem",
        component: () => import("../views/UnidadesSintomatica/Listagem")
      },
      {
        path: "/unidades_sintomatica/cadastro/:id",
        name: "unidades_sintomatica_cadastro",
        component: () => import("../views/UnidadesSintomatica/Cadastro"),
        props: {
          default: true
        }
      },

    ]
  }
];

const router = new VueRouter({
  mode: "history",
  base: process.env.BASE_URL,
  routes
});

export default router;
