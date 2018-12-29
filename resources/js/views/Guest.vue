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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    export default {
        name: 'Guest',

        data () {
            return {
                loading: false,
                guest: null
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
            }
        },

        watch: {
            '$route': 'getGuest'
        },

        mounted () {
            this.getGuest()
        }
    }
</script>

<style scoped>
    .table td {
        border: 0;
    }
</style>
