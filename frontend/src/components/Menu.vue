<template>
  <div>
    <v-app-bar
        color="primary"
        dense
        dark
      >
      <v-app-bar-nav-icon @click="drawer = true"></v-app-bar-nav-icon>
        <v-toolbar-title>Todos Contra o Covid</v-toolbar-title>
      </v-app-bar>

      <v-navigation-drawer
        v-model="drawer"
        absolute
        temporary
      >
        <v-list
          nav
          dense
        >
          <v-list-item-group
            active-class="primary--text text--accent-4"
          >

            <v-list-item active to="home">
              <v-list-item-icon>
                <v-icon>mdi-home</v-icon>
              </v-list-item-icon>
              <v-list-item-title>Home</v-list-item-title>
            </v-list-item>

            <v-list-item to="atendimento">
              <v-list-item-icon>
                <v-icon>mdi-account</v-icon>
              </v-list-item-icon>
              <v-list-item-title>Atendimento</v-list-item-title>
            </v-list-item>

          </v-list-item-group>
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