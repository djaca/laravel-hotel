<template>
    <section>
        <b-loading :active.sync="loading"></b-loading>

        <div class="columns" v-if="guest">
            <div class="column is-8">
                <div class="box">
                    <div class="card-header-title is-centered">Guest Details</div>

                    <div class="columns">
                        <div class="column is-6">
                            <table class="table">
                                <tbody>
                                <tr>
                                    <td>Guest ID:</td>
                                    <td>{{ guest.id }}</td>
                                </tr>
                                <tr>
                                    <td>Name:</td>
                                    <td>{{ guest.name }}</td>
                                </tr>
                                <tr>
                                    <td>Phone:</td>
                                    <td>{{ guest.phone }}</td>
                                </tr>
                                <tr>
                                    <td>Email:</td>
                                    <td>{{ guest.email}}</td>
                                </tr>
                                </tbody>
                            </table>

                            <button
                                class="button is-medium is-info is-outlined"
                                style="border-radius: 50%"
                                @click="openEditGuestModal"
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

                <div class="box">
                    <div class="card-header-title is-centered">Reservations History</div>

                    <b-table :data="reservations">
                        <template slot-scope="props">
                            <b-table-column field="id" label="ID" width="60">
                                {{ props.row.id }}
                            </b-table-column>

                            <b-table-column field="room" label="Room">
                                <b-taglist>
                                    <template v-for="room in props.row.rooms">
                                        <b-tag type="is-primary">{{ room.name }}</b-tag>
                                    </template>
                                </b-taglist>
                            </b-table-column>

                            <b-table-column field="start_date" label="Check In" centered>
                                {{ new Date(props.row.start_date).toLocaleDateString() }}
                            </b-table-column>

                            <b-table-column field="end_date" label="Check Out" centered>
                                {{ new Date(props.row.end_date).toLocaleDateString() }}
                            </b-table-column>
                        </template>
                    </b-table>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    import GuestForm from '../components/guests/GuestForm'

    export default {
        name: 'Guest',

        data () {
            return {
                loading: false,
                guest: null,
                reservations: [],
            }
        },

        methods: {
            getGuest () {
                this.loading = true

                axios.get(`/api/guests/${this.$route.params.id}`)
                    .then(({data}) => {
                        this.guest = data
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

            openEditGuestModal () {
                this.$modal.open({
                    parent: this,
                    component: GuestForm,
                    hasModalCard: true,
                    width: 960,
                    events: {
                        'guest-updated': guest => {
                            this.guest = guest
                        }
                    },
                    props: {
                        guest: this.guest
                    }
                })
            },

            confirmDelete () {
                this.$dialog.confirm({
                    title: `Deleting guest: ${this.guest.name}`,
                    message: 'Are you sure you want to <b>delete</b> this guest? This action cannot be undone.',
                    confirmText: 'Delete Guest',
                    type: 'is-danger',
                    hasIcon: true,
                    onConfirm: () => {
                        this.delete()
                    }
                })
            },

            delete () {
                axios.delete(`/api/guests/${this.guest.id}`)
                    .then(({data}) => {
                        this.$toast.open({
                            message: data.message,
                            type: 'is-success'
                        })

                        this.$router.push({ path: '/guests' })
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

            getReservations () {
                axios.get(`/api/reservations?guest=${this.$route.params.id}`)
                    .then(({data}) => {
                        this.reservations = data
                    })
                    .catch(err => {
                        this.$toast.open({
                            message: err.response.data.message,
                            type: 'is-danger'
                        })
                    })
            }
        },

        watch: {
            '$route': 'getGuest'
        },

        mounted () {
            this.getGuest()
            this.getReservations()
        }
    }
</script>

<style scoped>
    .table td {
        border: 0;
    }
</style>
