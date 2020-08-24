<template>
  <div>
    <v-app-bar
        color="primary"
        dense
        dark
        fixed
        app
      >
      <v-app-bar-nav-icon @click="drawer = true"></v-app-bar-nav-icon>
        <v-toolbar-title>Todos Contra o Covid</v-toolbar-title>
      </v-app-bar>

      <v-navigation-drawer
        v-model="drawer"
        app
        temporary
      >
        <v-list
          nav
          dense
        >
          <v-list-item active to="/home">
            <v-list-item-icon>
              <v-icon>mdi-home</v-icon>
            </v-list-item-icon>
            <v-list-item-title>Home</v-list-item-title>
          </v-list-item>
        </v-list>
        <v-divider></v-divider>
        <v-list
          nav
          dense
        >
          <v-list-group
            active-class="primary--text text--accent-4"
            no-action
          >
            <template v-slot:activator>
              <v-list-item>
                <v-list-item-icon>
                  <v-icon>mdi-account</v-icon>
                </v-list-item-icon>
                <v-list-item-title>Atendimento</v-list-item-title>
              </v-list-item>
            </template>
            <v-list-item to="/atendimento/listagem">
              <v-list-item-title>Listagem</v-list-item-title>
            </v-list-item>
            <v-list-item to="/atendimento/cadastro/0">
              <v-list-item-title>Cadastro</v-list-item-title>
            </v-list-item>
          </v-list-group>

          <v-list-group
            active-class="primary--text text--accent-4"
            no-action
          >
            <template v-slot:activator>
              <v-list-item>
                <v-list-item-icon>
                  <v-icon>mdi-account</v-icon>
                </v-list-item-icon>
                <v-list-item-title>Usuário</v-list-item-title>
              </v-list-item>
            </template>
            <v-list-item to="/usuario/listagem">
              <v-list-item-title>Listagem</v-list-item-title>
            </v-list-item>
            <v-list-item to="/usuario/cadastro/0">
              <v-list-item-title>Cadastro</v-list-item-title>
            </v-list-item>
          </v-list-group>

          <v-list-group
            active-class="primary--text text--accent-4"
            no-action
          >
            <template v-slot:activator>
              <v-list-item>
                <v-list-item-icon>
                  <v-icon>mdi-account</v-icon>
                </v-list-item-icon>
                <v-list-item-title>Unidades de Saúde</v-list-item-title>
              </v-list-item>
            </template>
            <v-list-item to="/unidades_saude/listagem">
              <v-list-item-title>Listagem</v-list-item-title>
            </v-list-item>
            <v-list-item to="/unidades_saude/cadastro/0">
              <v-list-item-title>Cadastro</v-list-item-title>
            </v-list-item>
          </v-list-group>

          <v-list-group
            active-class="primary--text text--accent-4"
            no-action
          >
            <template v-slot:activator>
              <v-list-item>
                <v-list-item-icon>
                  <v-icon>mdi-account</v-icon>
                </v-list-item-icon>
                <v-list-item-title>Unidades Sintomática</v-list-item-title>
              </v-list-item>
            </template>
            <v-list-item to="/unidades_sintomatica/listagem">
              <v-list-item-title>Listagem</v-list-item-title>
            </v-list-item>
            <v-list-item to="/unidades_sintomatica/cadastro/0">
              <v-list-item-title>Cadastro</v-list-item-title>
            </v-list-item>
          </v-list-group>

        </v-list>
      </v-navigation-drawer>
    </div>
</template>

<script>
export default {
  data: () => ({
    drawer: false
  })
}
</script>

<style>
  .v-list-group .v-list-item.theme--light {
    padding-left: 4px;
  }

  .v-list-item__icon {
    margin-right: 8px !important;
  }

  .v-list-item__icon.v-list-group__header__append-icon {
    margin-left: 0 !important;
  }
</style>