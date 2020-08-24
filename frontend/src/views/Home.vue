<template>
    <v-main>
        <Menu />
        <router-view></router-view>
        <div style="margin: 80px 0;"></div>
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