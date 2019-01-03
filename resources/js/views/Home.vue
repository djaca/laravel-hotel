<template>
    <section>
        <div class="columns">
            <div class="column is-8">
                <div class="box">
                    <b-tabs v-model="activeTab" type="is-toggle-rounded">
                        <template v-for="(tab, tabIndex) in tabs">
                            <b-tab-item :label="capitalizeFirstLetter(tab.label)" :key="tabIndex">
                                <div class="columns is-multiline">
                                    <b-loading :is-full-page="false" :active.sync="loading"></b-loading>

                                    <div v-if="tab.data.length === 0" style="height: 10rem">
                                        No {{ tab.label }} today
                                    </div>

                                    <div class="column is-3" v-for="(data, dataIndex) in tab.data">
                                        <v-card :reservation="data" :key="dataIndex"
                                                @click.native="showReservation(data)"></v-card>
                                    </div>
                                </div>
                            </b-tab-item>
                        </template>
                    </b-tabs>
                </div>
            </div>

            <div class="column is-4">
                <stats-widget :rows="rows"></stats-widget>
            </div>
        </div>
    </section>
</template>

<script>
    import VCard from './../components/VCard'
    import StatsWidget from '../components/StatsWidget'

    export default {
        name: 'Home',

        components: {VCard, StatsWidget},

        data () {
            return {
                loading: false,
                activeTab: 0,
                reservations: null,
                stats: []
            }
        },

        computed: {
            tabs () {
                if (this.reservations) {
                    let tabs = []

                    for (let key in this.reservations) {
                        tabs.push({
                            label: key,
                            data: this.reservations[key]
                        })
                    }

                    return tabs
                }
            },

            rows () {
                return [
                    {
                        label: 'Rooms booked today:',
                        type: 'is-primary',
                        data: this.stats.bookedToday
                    },
                    {
                        label: 'Pending rooms today:',
                        type: 'is-info',
                        data: this.stats.pendingRooms
                    }
                ]
            }
        },

        methods: {
            capitalizeFirstLetter (string) {
                return string.charAt(0).toUpperCase() + string.slice(1)
            },

            showReservation (data) {
                this.$router.push({path: `/reservations/${data.id}`})
            },

            getReservations () {
                this.loading = true

                axios.get(`/api/reservations?day=${this.$moment().format('YYYY-MM-DD')}`)
                    .then(({data}) => {
                        this.reservations = data
                    })
                    .catch(err => console.log(err))
                    .finally(() => {
                        this.loading = false
                    })
            },

            getStats () {
                axios.get('/api/rooms/stats')
                    .then(({data}) => {
                        this.stats = data
                    })
                    .catch(err => console.log(err))
            }
        },

        mounted () {
            this.getReservations()
            this.getStats()
        }
    }
</script>
