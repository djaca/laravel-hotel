<template>
    <section>
        <div class="columns">
            <div class="column is-8">
                <div class="box">
                    <div class="card-header-title is-centered">Reservations</div>

                    <b-table
                        :data="reservations"
                        :loading="loading"
                        selectable
                        striped
                        hoverable
                        paginated
                        backend-pagination
                        :total="total"
                        :per-page="perPage"
                        @page-change="onPageChange"
                        @select="selected"
                    >
                        <template slot-scope="props">
                            <b-table-column field="id" label="ID" width="60">
                                {{ props.row.id }}
                            </b-table-column>

                            <b-table-column field="guest" label="Guest">
                                {{ props.row.guest.name }}
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

                    <button
                        class="button is-medium is-info is-outlined"
                        style="border-radius: 50%"
                        @click="createReservation"
                    >
                        <b-icon icon="plus" size="is-small"></b-icon>
                    </button>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    export default {
        name: 'Reservations',

        data () {
            return {
                loading: false,
                reservations: [],
                total: 0,
                page: 1,
                perPage: 0
            }
        },

        methods: {
            onPageChange (page) {
                this.page = page

                this.getReservations()
            },

            getReservations () {
                this.loading = true

                axios.get(`/api/reservations?page=${this.page}`)
                    .then(({data}) => {
                        this.reservations = data.data
                        this.perPage = data.per_page
                        this.total = data.total
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

            selected (reservation) {
                this.$router.push({ path: `/reservations/${reservation.id}` })
            },

            createReservation () {
                this.$router.push({ path: '/reservations/create' })
            }
        },

        mounted () {
            this.getReservations()
        }
    }
</script>

<style scoped>
    table td {
        cursor: pointer;
    }
</style>
