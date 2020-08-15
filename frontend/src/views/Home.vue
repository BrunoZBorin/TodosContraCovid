<template>
    <v-main>
        <Menu />
        <router-view></router-view>
    </v-main>
</template>

<script>
    import Menu from '../components/Menu';

    export default {
        components: {
            Menu
        }
    }
</script>