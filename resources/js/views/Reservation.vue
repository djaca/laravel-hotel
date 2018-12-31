<template>
    <section>
        <b-loading :active.sync="loading"></b-loading>

        <div class="columns" v-if="reservation">
            <div class="column is-8">
                <div class="box">
                    <div class="card-header-title is-centered">Reservation Details</div>

                    <div class="columns">
                        <div class="column is-6">
                            <table class="table">
                                <tbody>
                                <tr>
                                    <td>Reservation #:</td>
                                    <td>{{ reservation.id }}</td>
                                </tr>
                                <tr>
                                    <td>Guest:</td>
                                    <td>{{ reservation.guest.name }}</td>
                                </tr>
                                <tr>
                                    <td>Check In:</td>
                                    <td>{{ new Date(reservation.start_date).toLocaleDateString() }}</td>
                                </tr>
                                <tr>
                                    <td>Check Out:</td>
                                    <td>{{ new Date(reservation.end_date).toLocaleDateString() }}</td>
                                </tr>
                                </tbody>
                            </table>

                            <button
                                class="button is-medium is-info is-outlined"
                                style="border-radius: 50%"
                                @click="editReservation"
                            >
                                <b-icon icon="pencil" size="is-small"></b-icon>
                            </button>

                            <button
                                class="button is-medium is-danger is-outlined"
                                style="border-radius: 50%"
                                @click="confirmDelete"
                            >
                                <b-icon icon="delete" size="is-small"></b-icon>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="column is-4">
                <div class="box">
                    <div class="card-header-title is-centered">Reserved Rooms</div>

                    <div class="card" v-for="room in reservation.rooms" :key="room.id">
                        <div class="card-content">
                            <table class="table">
                                <tbody>
                                <tr>
                                    <td>Name:</td>
                                    <td>{{ room.name }}</td>
                                </tr>
                                <tr>
                                    <td>Type:</td>
                                    <td>{{ room.type.name }}</td>
                                </tr>
                                <tr>
                                    <td>Capacity:</td>
                                    <td>{{ room.type.capacity }}</td>
                                </tr>
                                <tr>
                                    <td>Price:</td>
                                    <td>${{ room.type.price }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    export default {
        name: 'Reservation',

        data () {
            return {
                loading: false,
                reservation: null
            }
        },

        methods: {
            getReservation () {
                this.loading = true

                axios.get(`/api/reservations/${this.$route.params.id}`)
                    .then(({data}) => {
                        this.reservation = data
                    })
                    .catch(err => {
                        this.$toast.open({
                            message: err.response.data.message,
                            type: 'is-danger'
                        })
                    })
                    .finally(() => {
                        this.loading = false
                    })
            },

            editReservation () {
                this.$router.push({ path: `/reservations/${this.reservation.id}/edit` })
            },

            confirmDelete () {
                this.$dialog.confirm({
                    title: `Deleting reservation: ${this.reservation.name}`,
                    message: 'Are you sure you want to <b>delete</b> this reservation? This action cannot be undone.',
                    confirmText: 'Delete Reservation',
                    type: 'is-danger',
                    hasIcon: true,
                    onConfirm: () => {
                        this.delete()
                    }
                })
            },

            delete () {
                axios.delete(`/api/reservations/${this.reservation.id}`)
                    .then(({data}) => {
                        this.$toast.open({
                            message: data.message,
                            type: 'is-success'
                        })

                        this.$router.push({path: '/reservations'})
                    })
                    .catch(err => {
                        this.$toast.open({
                            message: err.response.data.message,
                            type: 'is-danger'
                        })
                    })
                    .finally(() => {
                        this.loading = false
                    })
            }
        },

        watch: {
            '$route': 'getReservation'
        },

        mounted () {
            this.getReservation()
        }
    }
</script>

<style scoped>
    .table td {
        border: 0;
    }
</style>
