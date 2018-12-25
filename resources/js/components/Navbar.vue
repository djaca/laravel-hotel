<template>
    <nav class="navbar is-white topNav">
        <div class="container">
            <div class="navbar-brand">
                <div class="navbar-item">
                    <v-clock></v-clock>
                </div>
            </div>
        </div>
    </nav>
</template>

<script>
    import VClock from './VClock'

    export default {
        name: 'Navbar',

        components: {VClock}
    }
</script>

<style scoped lang="scss">
    @import "./../../sass/variables";

    nav {
        border-top: 4px solid $blue;
        border-bottom: 1px solid #dadee1;
    }
</style>
