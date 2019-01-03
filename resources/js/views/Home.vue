<template>
    <section>
        <div class="columns">
            <div class="column is-8">
                <div class="box">
                    <b-tabs v-model="activeTab" type="is-toggle-rounded">
                        <template v-for="(tab, tabIndex) in tabs">
                            <b-tab-item :label="tab.label" :key="tabIndex">
                                <div class="columns is-multiline">
                                    <b-loading :is-full-page="false" :active.sync="loading"></b-loading>

                                    <div v-if="tab.data.length === 0" style="height: 10rem">No {{
                                        tab.label.toLowerCase() }}
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
                <rooms-stats></rooms-stats>
            </div>
        </div>
    </section>
</template>

<script>
    import VCard from './../components/VCard'
    import RoomsStats from './../components/RoomsStats'

    export default {
        name: 'Home',

        components: {VCard, RoomsStats},

        data () {
            return {
                loading: false,
                activeTab: 0,
                tabs: [
                    {
                        label: 'Arrivals',
                        data: []
                    },
                    {
                        label: 'Departures',
                        data: []
                    }
                ]
            }
        },

        methods: {
            showReservation (data) {
                this.$router.push({path: `/reservations/${data.id}`})
            },

            getReservations () {
                this.loading = true

                axios.get(`/api/reservations?day=${this.$moment().format('YYYY-MM-DD')}`)
                    .then(({data}) => {
                        this.tabs = data
                    })
                    .catch(err => console.log(err))
                    .finally(() => {
                        this.loading = false
                    })
            }
        },

        mounted () {
            this.getReservations()
        }
    }
</script>

<style scoped>

</style>
