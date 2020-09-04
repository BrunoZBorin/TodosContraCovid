import Vue from "vue";
import VueRouter from "vue-router";

Vue.use(VueRouter);

const routes = [
  {
    path: "/inicio",
    name: 'inicio',
    component: () => import("../views/Inicio")
  },
  {
    path: "/login",
    name: 'login',
    component: () => import("../views/Login")
  },
  {
    path: "/dashboard",
    name: 'dashboard',
    component: () => import("../views/Dashboard")
  },
  {
    path: "/",
    name: "principal",
    component: () => import("../views/Principal"),
    meta: {
      requiresAuth: true
    },
    children: [
      // Home
      {
        path: "/Home",
        name: "home",
        component: () => import("../views/Home")
      },

      // Atendimento
      {
        path: "/atendimento/listagem",
        name: "atendimento_listagem",
        component: () => import("../views/Atendimento/Listagem")
      },
      {
        path: "/atendimento/cadastro/:id/:paciente_id",
        name: "atendimento_cadastro",
        component: () => import("../views/Atendimento/Cadastro"),
        props: {
          default: true
        }
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
        path: "/unidades_sintomaticas/listagem",
        name: "unidades_sintomaticas_listagem",
        component: () => import("../views/UnidadesSintomatica/Listagem")
      },
      {
        path: "/unidades_sintomaticas/cadastro/:id",
        name: "unidades_sintomaticas_cadastro",
        component: () => import("../views/UnidadesSintomatica/Cadastro"),
        props: {
          default: true
        }
      },

      // Relatório de Atendimento
      {
        path: "/relatorio/atendimento",
        name: "relatorio_atendimento",
        component: () => import("../views/Relatorio/Atendimento")
      },

      // Relatório de Paciente
      {
        path: "/relatorio/paciente",
        name: "relatorio_paciente",
        component: () => import("../views/Relatorio/Paciente")
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
