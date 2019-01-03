<template>
    <div class="box">
        <div class="card-header-title is-centered">Stats</div>

        <table class="stats">
            <tbody>
            <template v-for="(row, index) in rows">
                <tr :key="index">
                    <td>{{ row.label }}</td>
                    <td><b-tag :type="row.type">{{ row.data }}</b-tag></td>
                </tr>
            </template>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        name: 'StatsWidget',

        props: ['rows']
    }
</script>

<style scoped>
    .stats td {
        padding: 0 5rem 0 0
    }
</style>
