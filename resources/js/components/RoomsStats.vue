<template>
    <div class="box">
        <div class="card-header-title is-centered">Stats</div>

        <table class="stats">
            <tbody>
            <tr>
                <td>Rooms booked today:</td>
                <td>
                    <b-tag type="is-primary">{{ bookedToday }}</b-tag>
                </td>
            </tr>
            <tr>
                <td>Pending rooms today:</td>
                <td>
                    <b-tag type="is-info">{{ pending }}</b-tag>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        name: 'RoomsStats',

        data () {
            return {
                bookedToday: 0,
                pending: 0
            }
        },

        methods: {
            getStats () {
                axios.get('/api/rooms/stats')
                    .then(({data}) => {
                        this.bookedToday = data.bookedToday
                        this.pending = data.pendingRooms
                    })
                    .catch(err => console.log(err))
            }
        },

        mounted () {
            this.getStats()
        }
    }
</script>

<style scoped>
    .stats td {
        padding: 0 5rem 0 0
    }
</style>
