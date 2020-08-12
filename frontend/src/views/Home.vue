<template>
    <v-content>
        <Menu />
        <router-view></router-view>
    </v-content>
</template>

<script>
    import Menu from '../components/Menu';

    export default {
        components: {
            Menu
        }
    }
</script>