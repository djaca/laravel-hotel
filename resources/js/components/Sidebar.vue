<template>
    <aside class="menu">
        <ul class="menu-list">
            <li><router-link to="/" exact>Dashboard</router-link></li>
            <li><router-link to="/reservations">Reservations</router-link></li>
            <li><router-link to="/rooms">Rooms</router-link></li>
            <li><router-link to="/guests">Guests</router-link></li>
        </ul>
    </aside>
</template>

<script>
    export default {
        name: 'Sidebar'
    }
</script>

<style scoped lang="scss">
    @import "./../../sass/variables";

    .menu {
        cursor: pointer;
    }
    .menu .menu-list {
        line-height: 1.5;
    }
    .menu-list a:hover {
        background-color: #e9edf0;
    }
    .menu-list a {
        color: #0F1D38;
        font-size: 14px;
        font-weight: 700;
    }
    .menu-list li{
        padding: 5px;
    }
    .menu-list a.router-link-active {
        background-color: transparent;
        color: $blue;
        font-weight: 700;
    }
</style>
